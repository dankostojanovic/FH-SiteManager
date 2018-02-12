<?php
define("SYSTEM_NAME", 'fischer_api');
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Respect\Validation\Validator as Validator;
use \DavidePastore\Slim\Validation\Validation as Validation;
use \SyncPervasive as SyncPervasive;
use \FischerHomes\Components\PervasiveApiComponent;
use \FischerHomes\Components\EventEmitterComponent;
use \FischerHomes\Components\QuerySapphireDbComponent;

require_once '../fischer_api/vendor/autoload.php';
require_once '../fischer_api/generated-conf/config.php';
require_once 'functions.inc.php';

$dotenv = new Dotenv\Dotenv(dirname(__DIR__));
$dotenv->load();

$configuration  = [
    'settings' => [
        'addContentLengthHeader' => false,
        'displayErrorDetails' => true
        ]
    ];
$customConfiguration = [
    'page' => 1,
    'perPage' => 25,
    'isDeletedFilter' => 0,
    'orderByAsc' => 'asc'
    ];
$container = new \Slim\Container($configuration);
$container['errorHandler'] = function ($c) {
    return function ($request, $response, $exception) use ($c) {
        $sentryClient = (new Raven_Client(getenv('SENTRY_DSN')))->install();
        $sentryClient->captureException($exception);
        return $response;
    };
};

$app = new \Slim\App($container);

$publicKey = file_get_contents('../oauth-public.key');

$app->add(new \Slim\Middleware\JwtAuthentication([
    "path" => ["/slimapi/v1/"],
    "passthrough" => ["/slimapi/v1/sites/bySiteNumber"],
    "secret" => $publicKey,
    "algorithm" => ["RS256"],
    "callback" => function ($request, $response, $arguments) use ($container, $app) {
        $container["jwt"] = $arguments["decoded"];
    }
]));

$app->add(function($request, $response, $next) {
    $user = \UsersQuery::create()
        ->findOneByFischerUsername($request->getHeader('username'));

    $request = $request->withAttribute('userId', $user->getId());
    
    $response= $next($request, $response);
    
    return $response;
});

$isId = Validator::numeric()->positive();
$validateId = ['id' => $isId];
$validateSite = [
    ['SectionId' => $isId, 'LegalSectionId' => $isId]
];

/*
 * Test API function
 *
 * @param string String to be posted back in response
 *
 */
$app->get('/slimapi/hello/{name}', function (Request $request, Response $response) {

    $emitter = new EventEmitterComponent;
    $emitter->emit('site-created', [33551, 33552]);

    $name = $request->getAttribute('name');
    $response->getBody()->write("Hello, $name");
    return $response;
});

/*
 * Community
 *
 */
$app->get('/slimapi/v1/communities', function (Request $request, Response $response) use ($customConfiguration) {
    $onlyActiveFilter = 0;
    $withDivision = 0;
    $withBudgetNeighborhood = 0;
    $arrCommunities = [];

    $queryParams = $request->getQueryParams();

    if(isset($queryParams['page'])) {
        $customConfiguration['page'] = $queryParams['page'];
    }
    if(isset($queryParams['per_page'])) {
        $customConfiguration['perPage'] = $queryParams['per_page'];
    }
    if(isset($queryParams['active'])) {
        $onlyActiveFilter = $queryParams['active'];
    }
    if(isset($queryParams['include'])) {
        $withArray = explode(',', $queryParams['include']);
        if(isset($withArray) && count($withArray) > 0) {
            foreach ($withArray as $value) {
                if($value == 'Division') {
                    $withDivision = 1;
                } elseif($value == 'BudgetNeighborhood') {
                    $withBudgetNeighborhood = 1;
                }
            }
        }
    }

    $filter = [];
    if (isset($queryParams['filter'])) {
        $filter = $queryParams['filter'];
    }

    $orderBy = 'Code';
    if(isset($queryParams['sortBy'])) {
        $orderBy = $queryParams['sortBy'];
    }
    $customConfiguration['sortByAsc'] = 'ASC';
    if(isset($queryParams['sortByAsc'])) {
        $customConfiguration['sortByAsc'] = $queryParams['sortByAsc'];
    }

    if(isset($queryParams['is_deleted'])) {
        $customConfiguration['isDeletedFilter'] = $queryParams['is_deleted'];
    }
    if(isset($queryParams['fields'])) {
        $fields = explode(',', $queryParams['fields']);
    } else {
        $fields = ['Id', 'Code', 'Name', 'IsActive'];
    }
    $communities = \CommunityQuery::create()
            ->addDivision($withDivision)
            ->addBudgetNeighborhood($withBudgetNeighborhood)
            ->addActiveFilter($onlyActiveFilter)
            ->filterByIsDeleted($customConfiguration['isDeletedFilter'])
            ->filterByArray($filter)
            ->orderBy($orderBy, $customConfiguration['sortByAsc'])
            ->paginate($customConfiguration['page'], $customConfiguration['perPage']);

    foreach ($communities as $community) {
        $tempCommunity = [];
        foreach ($fields as $field) {
            if (array_key_exists($field, $community->toArray())) {
                $tempCommunity[$field] = $community->toArray()[$field];
            } elseif ($community->hasVirtualColumn($field)) {
                $tempCommunity[$field] = $community->getVirtualColumn($field);
            }
        }
        if (in_array('FischerSection', $withArray)) {
            $tempCommunity['CommunitySections'] = $community->loadCommunitySections();
        }
        if (in_array('LegalSection', $withArray)) {
            $tempCommunity['CommunitySectionLegals'] = $community->loadCommunitySectionLegals();
        }
        $arrCommunities[] = $tempCommunity;
    }
    $data = [
        'page' => $communities->getPage(),
        'rows' => $arrCommunities,
        'totalRecords' => $communities->getNbResults(),
        'totalPages' => $communities->getLastPage()
        ];

    return $response->withJson($data);
});

// Example how to load belonging entities
$app->get('/slimapi/v1/communities/{id}', function (Request $request, Response $response, $args) {

    $queryParams = $request->getQueryParams();
    $id = $args['id'];

    $community = \CommunityQuery::create()->findPk($id);
    $arrCommunity = $community->toArray();
    if(isset($queryParams['include'])) {
        $withArray = explode(',', $queryParams['include']);
        if(isset($withArray) && count($withArray) > 0) {
            foreach ($withArray as $value) {
                if($value == 'CommunitySite') {
                    $arrCommunity['CommunitySites'] = $community->loadCommunitySites();
                } elseif ($value == 'CommunitySection') {
                    $arrCommunity['CommunitySections'] = $community->loadCommunitySections();
                } elseif ($value == 'CommunitySectionLegal') {
                    $arrCommunity['CommunitySectionLegals'] = $community->loadCommunitySectionLegals();
                }
            }
        }
    }

    return $response->withJson($arrCommunity);
})->add(new Validation($validateId));

/*
 * Site
 *
 */
// FIXME - brittle and not optimized
$app->get('/slimapi/v1/sites', function (Request $request, Response $response) use ($customConfiguration) {
    // Initialization
    $queryParams = $request->getQueryParams();
    $filterSite = [];
    $filterSiteInventory = [];
    $filterFischerSection = [];
    $filterLegalSection = [];
    $filterCommunity = [];
    $filterSpecLevel = [];
    $filterPricingGroup = [];
    $filterContract = [];
    $arrSites = [];
    $tables = null;
    $fields = null;
    $filters = null;
    $pagination = null;
    $siteInventoryFilter = null;
    $orderBy = 'SiteNumber';
    $showAll = false;

    // Get sections of query.
    if(isset($queryParams['tables'])) {
        $tables = $queryParams['tables'];
    }

    if(isset($queryParams['fields'])) {
        $fields = $queryParams['fields'];
    }

    if(isset($queryParams['filters'])) {
        $filters = $queryParams['filters'];
    }

    if(isset($queryParams['pagination'])) {
        $pagination = $queryParams['pagination'];
    }

    // Set up filters
    if(isset($filters['Site']) && is_array($filters['Site'])) {
        foreach ($filters['Site'] as $key => $value) {
            if($key == "PurchaseDate") {
                unset($filters['Site'][$key]);
                $siteInventoryFilter = $value;
            }
        }

        $filterSite = $filters['Site'];
    }

    if(isset($pagination) && is_array($pagination)) {
        foreach($pagination as $key => $value) {
            if($key == "Page") {
                $customConfiguration['page'] = $value;
            }
            else if($key == "PerPage") {
                if($value == -1) {
                    $showAll = true;
                }
                else {
                    $customConfiguration['perPage'] = $value;
                }
            }
            else if($key == "OrderByAsc") {
                $customConfiguration['orderByAsc'] = $value;
            }
            else if($key == "OrderBy") {
                $orderBy = $value;
            }
        }
    }

    if(isset($filters['FischerSection'])) {
        $filterFischerSection = $filters['FischerSection'];
    }

    if(isset($filters['LegalSection'])) {
        $filterLegalSection = $filters['LegalSection'];
    }

    if(isset($filters['Community'])) {
        $filterCommunity = $filters['Community'];
    }

    if(isset($filters['SpecLevel'])) {
        $filterSpecLevel = $filters['SpecLevel'];
    }

    if(isset($filters['PricingGroup'])) {
        $filterPricingGroup = $filters['PricingGroup'];
    }

    if(isset($filters['Contract'])) {
        $filterContract = $filters['Contract'];
    }

    if(isset($tables) && count($tables) > 0) {
        foreach($tables as $table) {
            // If select fields are there show them.
            if(isset($fields[$table])) {
                $fields[$table] = explode(',', $fields[$table]);
            }

            // If community add community table.
            if($table == "Community") {
                $addCommunity = true;
            }
            else if($table == "FischerSection") {
                $addFischerSection = true;
            }
            else if($table == "LegalSection") {
                $addLegalSection = true;
            }
            else if($table == "SpecLevel") {
                $addSpecLevel = true;
            }
            else if($table == "PricingGroup") {
                $addPricingGroup = true;
            }
        }
    }

    if(!isset($fields['Community'])) {
        $fields['Community'] = [];
    }

    if(!isset($fields['FischerSection'])) {
        $fields['FischerSection'] = [];
    }

    if(!isset($fields['LegalSection'])) {
        $fields['LegalSection'] = [];
    }

    if(!isset($fields['SpecLevel'])) {
        $fields['SpecLevel'] = [];
    }

    if(!isset($fields['PricingGroup'])) {
        $fields['PricingGroup'] = [];
    }

    $sites = \CommunitySiteQuery::create()
            ->join('CommunitySiteInventory')
            ->addCommunity($addCommunity, $fields['Community'])
            ->addFischerSection($addFischerSection, $fields['FischerSection'])
            ->addLegalSection($addLegalSection, $fields['LegalSection'])
            ->addSpecLevel($addSpecLevel, $fields['SpecLevel'])
            ->addPricingGroup($addPricingGroup, $fields['PricingGroup'])
            ->addSiteInventory(1)
            ->addSiteFilters($filterSite)
            ->addFischerSectionFilters($filterFischerSection)
            ->addCommunityFilters($filterCommunity)
            ->addLegalSectionFilters($filterLegalSection)
            ->addSpecLevelFilters($filterSpecLevel)
            ->addPricingGroupFilters($filterPricingGroup)
            ->addContractFilters($filterContract)
            ->useCommunitySiteInventoryQuery()
            ->addPurchaseDateFilter($siteInventoryFilter)
            ->endUse()
            ->orderBy($orderBy, $customConfiguration['orderByAsc'])
            ->paginate($customConfiguration['page'], $customConfiguration['perPage']);

    if($showAll) {
        $customConfiguration['perPage'] = $sites->getNbResults();
        $customConfiguration['page'] = 1;

        $sites = \CommunitySiteQuery::create()
            ->join('CommunitySiteInventory')
            ->addCommunity($addCommunity, $fields['Community'])
            ->addFischerSection($addFischerSection, $fields['FischerSection'])
            ->addLegalSection($addLegalSection, $fields['LegalSection'])
            ->addSpecLevel($addSpecLevel, $fields['SpecLevel'])
            ->addPricingGroup($addPricingGroup, $fields['PricingGroup'])
            ->addSiteInventory(1)
            ->addSiteFilters($filterSite)
            ->addFischerSectionFilters($filterFischerSection)
            ->addCommunityFilters($filterCommunity)
            ->addLegalSectionFilters($filterLegalSection)
            ->addSpecLevelFilters($filterSpecLevel)
            ->addPricingGroupFilters($filterPricingGroup)
            ->addContractFilters($filterContract)
            ->useCommunitySiteInventoryQuery()
            ->addPurchaseDateFilter($siteInventoryFilter)
            ->endUse()
            ->orderBy($orderBy, $customConfiguration['orderByAsc'])
            ->paginate($customConfiguration['page'], $customConfiguration['perPage']);
    }

    // FIXME fields for primary entity
    // $fields['Site'] = explode(',', $queryParams['fields']['Site']);
    foreach ($sites as $site) {
        $tempSite = $site->toArray();
        // Add 1-N related data when needed
//        if (in_array('Sites', $withArray)) {
//            $tempSite['Sites'] = $site->loadSites();
//        }
        $arrSites[] = $tempSite;
    }
    $data = [
        'page' => $sites->getPage(),
        'rows' => $arrSites,
        'totalRecords' => $sites->getNbResults(),
        'totalPages' => $sites->getLastPage()
        ];

    return $response->withJson($data);
});

$app->get('/slimapi/v1/sites/{id}', function (Request $request, Response $response, $args) {

    $queryParams = $request->getQueryParams();
    $id = $args['id'];
	$fields = [
		'Site' => [],
        'SiteMortgageAmmendment' => [],
        'Community' => [],
        'FischerSection' => [],
        'LegalSection' => []
    ];
    $withCommunity = false;
    $withFischerSection = false;
    $withLegalSectionp = false;

    // Includes
    if(isset($queryParams['include'])) {
        $withArray = explode(',', $queryParams['include']);
        if(isset($withArray) && count($withArray) > 0) {
            foreach ($withArray as $with) {
                if(isset($queryParams['fields'][$with])) {
                    $fields[$with] = explode(',', $queryParams['fields'][$with]);
                }
                if ($with == 'Community') {
                    $withCommunity = true;
                } elseif ($with == 'FischerSection') {
                    $withFischerSection = true;
                } elseif ($with == 'LegalSection') {
                    $withLegalSection = true;
                }
            }
        }
    }
    if(isset($queryParams['fields'])) {
        // FIXME add validation
        $fields = explode(',', $queryParams['fields']);
    } else {
        $siteTable = \Map\CommunitySiteTableMap::getTableMap();
        foreach ($siteTable->getColumns() as $column) {
            $fields[] = $column->getPhpName();
        }
    }
    $site = \CommunitySiteQuery::create()
            ->join('CommunitySiteInventory')
            ->addSiteInventory(1)
            ->addCommunity($withCommunity, $fields['Community'])
            ->addFischerSection($withFischerSection, $fields['FischerSection'])
            ->addLegalSection($withLegalSection, $fields['LegalSection'])
            ->findPK($id);

$arrSite = $site->toArray();
$arrSite['SiteMortgageAmmendments'] = $site->loadSiteMortgageAmmendments();

    return $response->withJson($arrSite);
})->add(new Validation($validateId));

$app->post('/slimapi/v1/communities/{id}/sites', function (Request $request, Response $response, $args) {
    $result = [];
    $siteData = [];
    $errors = [];
    $communityId = (int)$args['id'];

    // !!! FIXME FIXME FIXME !!!
    $community = \CommunityQuery::create()->addDivision(true)->findPK($communityId)->toArray();
    $division = $community['Division'];

    $allPostPutVars = $request->getParsedBody();
    // Validate input
    if($request->getAttribute('has_errors')){
            $errors = $request->getAttribute('errors');
            $data = [];
            $data["status"] = "error";
            $data["message"] = $errors;
            return $response->withJson($data, 422);
    }
    foreach ($allPostPutVars as $row) {
        $site = new \CommunitySite();
        $siteTable = \Map\CommunitySiteTableMap::getTableMap();
        $siteInventory = new \CommunitySiteInventory();
        $siteInventoryTable = \Map\CommunitySiteInventoryTableMap::getTableMap();
        foreach ($row as $fieldName => $fieldValue) {
            if ($siteTable->hasColumnByPhpName($fieldName) ) {
                $siteColumn = $siteTable->getColumnByPhpName($fieldName);
            // Parse all empty strings as null if cell is integer
                if ($siteColumn->isNumeric() && $fieldValue == "") {
                    $fieldValue = null;
                }
                $site->setByName($fieldName, $fieldValue);
                // Pervasive sync - CommunitySiteNotes special parsing
                if ($fieldName == 'ConstructionNotes') {
                    for ($i = 0; $i < 2; $i++) {
                        $name = $siteColumn->getName() . '-part-' . ($i + 1);
                        if (substr($fieldValue, $i * 254, 254)) {
                            $siteData[$name] = substr($fieldValue, $i * 254, 254);
                        } else {
                            $siteData[$name] = '';
                        }
                    }
                } elseif ($fieldName == 'GeneralNotes') {
                    for ($i = 0; $i < 4; $i++) {
                        $name = $siteColumn->getName() . '-part-' . ($i + 1);
                        if(substr($fieldValue, $i * 254, 254)) {
                            $siteData[$name] = substr($fieldValue, $i * 254, 254);
                        } else {
                            $siteData[$name] = '';
                        }
                    }
                } elseif ($fieldName == 'SiteNotes') {
                    for ($i = 0; $i < 10; $i++) {
                        $name = $siteColumn->getName() . '-part-' . ($i + 1);
                        if (substr($fieldValue, $i * 254, 254)) {
                            $siteData[$name] = substr($fieldValue, $i * 254, 254);
                        } else {
                            $siteData[$name] = '';
                        }
                    }
                } elseif ($fieldName == 'GcJobNumber') {
                    $siteData[$siteColumn->getName()] = $fieldValue;
                    if ($fieldValue > '') {
                        $siteData['gc_job_number_new'] = substr($fieldValue, 0, 5) . '/' . substr($fieldValue, 5, 3) . '/' . substr($fieldValue, 8);
                    } else {
                        $siteData['gc_job_number_new'] = '';
                    }
                } else {
                    $siteData[$siteColumn->getName()] = $fieldValue;
                }
            } elseif ($siteInventoryTable->hasColumnByPhpName($fieldName) ) {
                $siteInventoryColumn = $siteInventoryTable->getColumnByPhpName($fieldName);
                if ($siteInventoryColumn->isNumeric() && $fieldValue === "") {
                    $fieldValue = null;
                }
                $siteInventory->setByName($fieldName, $fieldValue);
                // Pervasive sync
                $siteData[$siteInventoryColumn->getName()] = $fieldValue;
            }
        }

        $site->setCommunityId($communityId);

        $site->setCreatedBy($request->getAttribute('userId'));
        $site->setCreatedDate('now');
        $site->setLastModifiedBy($user->getId());
        $site->setLastModifiedDate('now');
        $site->save();

        $siteInventory->setSiteId($site->getSiteId());
        $siteInventory->setSiteNumber($site->getSiteNumber());
        $siteInventory->setJobNumber($site->getJobNumber());
        $siteInventory->setDivision($division);
        $siteInventory->save();

        $siteData['site_id'] = $site->getSiteId();
        // Force Site Available Flag
        $siteData['available_flag'] = (int)$site->getAvailableFlag();
        // Must add SiteNumber and JobNumber to be able to update SiteAddl, Site_Inventory and SiteNotes
        $siteData['division'] = $division;
        $siteData['site_number'] = $site->getSiteNumber();
        $siteData['job_number'] = $site->getJobNumber();
        $siteData['site_selling_price'] = $siteInventory->getSiteCost();
        $siteData['SiteCostAdjustment'] = 0;

        if($siteInventory->getFee1() > 0 && $siteInventory->getFee1AppSca() === true) {
            $siteData['SiteCostAdjustment'] += $siteInventory->getFee1();
        }

        if($siteInventory->getFee2() > 0 && $siteInventory->getFee2AppSca() === true) {
            $siteData['SiteCostAdjustment'] += $siteInventory->getFee2();
        }

        if($siteInventory->getFee3() > 0 && $siteInventory->getFee3AppSca() === true) {
            $siteData['SiteCostAdjustment'] += $siteInventory->getFee3();
        }

        if($siteInventory->getFee4() > 0 && $siteInventory->getFee4AppSca() === true) {
            $siteData['SiteCostAdjustment'] += $siteInventory->getFee4();
        }

        $siteData['created_by'] = $site->getCreatedBy();
        $siteData['created_date'] = $site->getCreatedDate();
        $siteData['last_modified_by'] = $site->getLastModifiedBy();
        $siteData['last_modified_date'] = $site->getLastModifiedDate();
        $siteData['site_margin_factor'] = .9;
        $pervasiveMapSite = new SyncPervasive(SYSTEM_NAME, 'site-manager', 'Fischer Management', 'community_site', 'create');

        try {
            $pervasiveMapSite->run($siteData, false);
        } catch (Exception $exc) {
            // Roll back changes
            $site->setIsDeleted(1);
            $site->save();

            // Trigger event
            $emitter = new EventEmitterComponent;
            $emitter->emit('site-creation-failed', [$site->getSiteId()]);

            // Set error response
            $errors[] = [
                'resource' => $site->getSiteId(),
                'field' => 'N/A',
                'message' => $exc->getDefaultMessage()
                ];
        }
        // Adds site_inventory data to result
        $result[] = array_merge($site->toArray(), $siteInventory->toArray());
    }

    if (count($errors) > 0) {
        $res = [
            'message' => 'Pervasive update failed',
            'errors' => $errors
            ];
        return $response->withJson($res, 502);
    }

    // Trigger event
    $sites = [];
    foreach ($result as $val) {
        $sites[] = $val['SiteId'];
    }

    $emitter = new EventEmitterComponent;
    $emitter->emit('site-created', $sites);

    return $response->withJson($result);
})->add(new Validation($validateSite));

$app->patch('/slimapi/v1/sites', function (Request $request, Response $response) {
    $result = [];
    $errors = [];
    $allPostPutVars = $request->getParsedBody();

    foreach ($allPostPutVars as $row) {
        $site = \CommunitySiteQuery::create()->findOneBySiteId($row['SiteId']);
        $siteInventory = \CommunitySiteInventoryQuery::create()->findOneBySiteId($row['SiteId']);
        $siteTable = \Map\CommunitySiteTableMap::getTableMap();
        $siteInventoryTable = \Map\CommunitySiteInventoryTableMap::getTableMap();
        // !!! FIXME FIXME FIXME !!!
        $community = \CommunityQuery::create()->addDivision(true)->findPK($site->getCommunityId())->toArray();
        $division = $community['Division'];

        foreach ($row as $fieldName => $fieldValue) {
            // AvailableFlag is managed by Pervasive
            if ($fieldName == 'AvailableFlag') {
                $boolFieldValue = ($fieldValue === true);
                if ($boolFieldValue === $site->getAvailableFlag()) {
                    continue;
                }
                try {
                    if ($fieldValue === true) {
                        $status = 'available';
                    } 
                    else {
                        // TODO How do we handle status HOLD ?
                        $status = 'new';
                    }

                    $pervasiveApi = new PervasiveApiComponent;
                    $pervasiveApi->updateSiteAvailableFlag($site->getSiteNumber(), (int)$site->getAvailableFlag(), (int)$fieldValue, $status);
                } catch (Exception $exc) {
                    $errors[] = [
                        'resource' => $row['SiteId'],
                        'field' => 'AvailableFlag',
                        'message' => $exc->getDefaultMessage()
                        ];

                    // Update with data from source of truth (Pervasive)
                    $pervasiveResponse = $exc->getExtraData();

                    $responseBody = json_decode($pervasiveResponse['Response body']);

                    foreach ($responseBody->messages as $message) {
                        switch ($message->messageCode) {
                            case 200:
                                $site->setAvailableFlag(0);
                                break;
                            case 201:
                                $site->setAvailableFlag(1);
                                break;
                            case 202:
                                $site->setIsAvailableFlagEditable(0);
                                break;
                            default:
                                break;
                        }
                    }

                    continue;
                }

            }
            // PATCH does not change primary key
            if ($fieldName !== 'SiteId') {
                if ($siteTable->hasColumnByPhpName($fieldName) ) {
                    $siteColumn = $siteTable->getColumnByPhpName($fieldName);
                    if ($siteColumn->isNumeric() && $fieldValue === "") {
                        $fieldValue = null;
                    }
                    $site->setByName($fieldName, $fieldValue);
                    // Pervasive sync - CommunitySiteNotes special parsing
                    if ($fieldName == 'ConstructionNotes') {
                        for ($i = 0; $i < 2; $i++) {
                            $name = $siteColumn->getName() . '-part-' . ($i + 1);
                            if(substr($fieldValue, $i * 254, 254)) {
                                $siteData[$name] = substr($fieldValue, $i * 254, 254);
                            } else {
                                $siteData[$name] = '';
                            }
                        }
                    } elseif ($fieldName == 'GeneralNotes') {
                        for ($i = 0; $i < 4; $i++) {
                            $name = $siteColumn->getName() . '-part-' . ($i + 1);
                            if (substr($fieldValue, $i * 254, 254)) {
                                $siteData[$name] = substr($fieldValue, $i * 254, 254);
                            } else {
                                $siteData[$name] = '';
                            }
                        }
                    } elseif ($fieldName == 'SiteNotes') {
                        for ($i = 0; $i < 10; $i++) {
                            $name = $siteColumn->getName() . '-part-' . ($i + 1);
                            if (substr($fieldValue, $i * 254, 254)) {
                                $siteData[$name] = substr($fieldValue, $i * 254, 254);
                            } else {
                                $siteData[$name] = '';
                            }
                        }
                    } elseif ($fieldName == 'GcJobNumber') {
                        $siteData[$siteColumn->getName()] = $fieldValue;
                        if ($fieldValue > '') {
                            $siteData['gc_job_number_new'] = substr($fieldValue, 0, 5) . '/' . substr($fieldValue, 5, 3) . '/' . substr($fieldValue, 8);
                        } else {
                            $siteData['gc_job_number_new'] = '';
                        }
                    } else {
                        $siteData[$siteColumn->getName()] = $fieldValue;
                    }
                } elseif ($siteInventoryTable->hasColumnByPhpName($fieldName) ) {
                    $siteInventoryColumn = $siteInventoryTable->getColumnByPhpName($fieldName);
                    if ($siteInventoryColumn->isNumeric() && $fieldValue == "") {
                        $fieldValue = null;
                    }
                    if ($siteInventoryColumn->getType() == "DATE" && $fieldValue !== "") {
                        $fieldValue = date_create($fieldValue);
                    }
                    $siteInventory->setByName($fieldName, $fieldValue);
                    // Pervasive sync
                    $siteData[$siteInventoryColumn->getName()] = $fieldValue;
                }
            }
        }

        $site->setLastModifiedBy($request->getAttribute('userId'));
        $site->setLastModifiedDate('now');
        $site->save();

        $siteData['site_id'] = $site->getSiteId();
        // Must add SiteNumber and JobNumber to be able to update SiteAddl
        $siteData['division'] = $division;
        $siteData['site_number'] = $site->getSiteNumber();
        $siteData['job_number'] = $site->getJobNumber();
        $siteData['site_selling_price'] = $siteInventory->getSiteCost();
        $siteData['SiteCostAdjustment'] = 0;

        if($siteInventory->getFee1() > 0 && $siteInventory->getFee1AppSca() === true) {
            $siteData['SiteCostAdjustment'] += $siteInventory->getFee1();
        }

        if($siteInventory->getFee2() > 0 && $siteInventory->getFee2AppSca() === true) {
            $siteData['SiteCostAdjustment'] += $siteInventory->getFee2();
        }

        if($siteInventory->getFee3() > 0 && $siteInventory->getFee3AppSca() === true) {
            $siteData['SiteCostAdjustment'] += $siteInventory->getFee3();
        }

        if($siteInventory->getFee4() > 0 && $siteInventory->getFee4AppSca() === true) {
            $siteData['SiteCostAdjustment'] += $siteInventory->getFee4();
        }

        $siteData['last_modified_by'] = $site->getLastModifiedBy();
        $siteData['last_modified_date'] = $site->getLastModifiedDate();

        $pervasiveMapSite = new SyncPervasive(SYSTEM_NAME, 'site-manager', 'Fischer Management', 'community_site', 'update');
        $pervasiveMapSite->run($siteData);

        $siteInventory->save();

        $result[] = array_merge($site->toArray(), $siteInventory->toArray());
    }

    if (count($errors) > 0) {
        $res = [
            'message' => 'Pervasive update failed',
            'errors' => $errors
            ];
        return $response->withJson($res, 502);
    }

    return $response->withJson($result);
});

/*
 * Custom endpoint to be used by Matt's API
 * Client does not support PATCH
 */
$app->post('/slimapi/v1/sites/bySiteNumber', function (Request $request, Response $response) {
    $result = [];
    $allPostPutVars = $request->getParsedBody();
    $headers = $request->getHeaders();
    // check authorization
    if (!isset($headers['HTTP_AUTHORIZATION'][0]) || $headers['HTTP_AUTHORIZATION'][0] != '1fc7674f9e28738878555e5e295e366f4ab9ee3b5c5ebcccaae6f1abc05d581d') {
        $data = [];
        $data["status"] = "error";
        $data["message"] = 'Authorization failed';
        return $response->withJson($data, 401);
    }

    $site = \CommunitySiteQuery::create()->findOneBySiteNumber($allPostPutVars['siteNumber']);
    if (!$site) {
        return $response->withStatus(404);
    }
    try {
        $site->setAvailableFlag((int)$allPostPutVars['availableFlag']);
        $site->setIsAvailableFlagEditable((int)$allPostPutVars['isEditable']);

// FIXME Find current user_id
//        $headers = $request->getHeaders();
        // FIXME Matt's API doesn't have a user
        // Should set special user or ask Matt to pass user info too
//        $site->setLastModifiedBy($headers['HTTP_UID'][0]);
        $site->setLastModifiedDate('now');
        if ($site->save()) {
            $result = [
                'siteNumber' => $site->getSiteNumber(),
                'availableFlag' => $site->getAvailableFlag(),
                'isEditable' => $allPostPutVars['isEditable']
            ];
        }
    } catch (Exception $exc) {
        $res = [
            'message' => 'Available Flag update failed for Site ' . $allPostPutVars['siteNumber'],
            'errors' => $exc->getTraceAsString()
            ];
        return $response->withJson($res, 400);
    }

    return $response->withJson($result);
});

$app->delete('/slimapi/v1/sites/{id}', function (Request $request, Response $response, $args) {
    $id = (int)$args['id'];
    $site = \CommunitySiteQuery::create()->findOneBySiteId($id);
    if ($site) {
        $site->setIsDeleted(1);
        $site->save();
    } else {
        return $response->withStatus(404);
    }

    try {
        $siteData['site_id'] = $id;
        // FIXME Add CommunitySiteNotes delete trigger?
        $siteData['site_number'] = $site->getSiteNumber();
        $pervasiveMapSite = new SyncPervasive(SYSTEM_NAME, 'site-manager', 'Fischer Management', 'community_site', 'delete');
        $pervasiveMapSite->run($siteData);
        // FIXME Add Site_Inventory delete trigger?
    } catch (Exception $e) {
        // TBD log failure
//        echo $e->getTraceAsString();
    }

    return $response->withStatus(204);
});

$app->get('/slimapi/v1/legalSections', function (Request $request, Response $response) use ($customConfiguration) {

    $queryParams = $request->getQueryParams();
    $fields = [
        'LegalSection' => [],
        'Community' => []
        ];

    if(isset($queryParams['page'])) {
        $customConfiguration['page'] = $queryParams['page'];
    }
    if(isset($queryParams['per_page'])) {
        $customConfiguration['perPage'] = $queryParams['per_page'];
    }
    $orderBy = 'LegalSectionName';
    if(isset($queryParams['orderBy'])) {
        $orderBy = $queryParams['orderBy'];
    }
    if(isset($queryParams['orderByAsc'])) {
        $customConfiguration['orderByAsc'] = $queryParams['orderByAsc'];
    }

    // Filters
    $filterLegalSection = [];
    if (isset($queryParams['filter']['LegalSection'])) {
        $filterLegalSection = $queryParams['filter']['LegalSection'];
    }
    $filterCommunity = [];
    if (isset($queryParams['filter']['Community'])) {
        $filterCommunity = $queryParams['filter']['Community'];
    }
    // Fields
    if(isset($queryParams['fields']['LegalSection'])) {
        $fields['LegalSection'] = explode(',', $queryParams['fields']['LegalSection']);
    } else {
        $fields['LegalSection'] = ['LegalSectionId', 'LegalSectionName'];
    }
    if(isset($queryParams['include'])) {
        $withArray = explode(',', $queryParams['include']);
        if(isset($withArray) && count($withArray) > 0) {
            foreach ($withArray as $with) {
                if(isset($queryParams['fields'][$with])) {
                    $fields[$with] = explode(',', $queryParams['fields'][$with]);
                }
                if($with == 'Community') {
                    $withCommunity = 1;
                }
            }
        }
    }

    $legalSections = \CommunitySectionLegalQuery::create()
            ->addCommunity($withCommunity, $fields['Community'])
            ->addCommunityFilters($filterCommunity)
            ->filterByIsDeleted($customConfiguration['isDeletedFilter'])
            ->filterByArray($filterLegalSection)
            ->orderBy($orderBy, $customConfiguration['orderByAsc'])
            ->paginate($customConfiguration['page'], $customConfiguration['perPage']);

    foreach ($legalSections as $legalSection) {
        $tempLegalSection = $legalSection->toArray();
        if (in_array('Sites', $withArray)) {
            $tempLegalSection['Sites'] = $legalSection->loadSites();
        }
        $arrLegalSection[] = $tempLegalSection;
    }
    $data = [
        'page' => $legalSections->getPage(),
        'rows' => $arrLegalSection,
        'totalRecords' => $legalSections->getNbResults(),
        'totalPages' => $legalSections->getLastPage()
        ];

    return $response->withJson($data);
});

// Example how to load many-to-many entities (can be improved)
// Example of improved "select" parameter
$app->get('/slimapi/v1/legalSections/{id}', function (Request $request, Response $response, $args) {

    $queryParams = $request->getQueryParams();
    $id = $args['id'];
    $withCommunity = false;
    $withMortgage = false;
    $fields = false;
    // Should be middleware, or at least parsed the same way on all calls
    if(isset($queryParams['include'])) {
        $withArray = explode(',', $queryParams['include']);
        if(isset($withArray) && count($withArray) > 0) {
            foreach ($withArray as $value) {
                if($value == 'Community') {
                    $withCommunity = true;
                } elseif ($value == 'Mortgage') {
                    $withMortgage = true;
                }
            }
        }
    }

    if(isset($queryParams['fields'])) {
        $fields = explode(',', $queryParams['fields']);
    }

    $legalSection = \CommunitySectionLegalQuery::create()->addCommunity($withCommunity)->findPk($id);
    $arrLegalSection = $legalSection->toArray();

    // Parsing select parameter
    // Should be middleware, or at least parsed the same way on all calls
    foreach ($arrLegalSection as $key => $value) {
        if ($fields && !in_array($key, $fields)) {
            unset($arrLegalSection[$key]);
        }
    }

    // Should be middleware, or at least parsed the same way on all calls
    // With if defined user_func or something similar
    if($withMortgage) {
        $arrLegalSection['Mortgages'] = $legalSection->loadMortgages();
    }

    return $response->withJson($arrLegalSection);
})->add(new Validation($validateId));

$app->post('/slimapi/v1/communities/{communityId}/legalSections', function (Request $request, Response $response, $args) {
    $result = [];
    $legalSectionData = [];

    $communityId = (int)$args['communityId'];
    // validate community - FIXME make it middleware or at least utility
    // check the existance of community too, not just the id
    if ($communityId < 1) {
        return $response->withJson(['message' => 'Invalid Community'], 422);
    }
    $allPostPutVars = $request->getParsedBody();
    foreach ($allPostPutVars as $row) {
        $legalSection = new \CommunitySectionLegal();
        $legalSectionTable = \Map\CommunitySectionLegalTableMap::getTableMap();
        foreach ($row as $fieldName => $fieldValue) {
            // Parse all empty strings as null if cell is integer
            $pascalCaseName = ltrim(strtolower(preg_replace('/[A-Z]([A-Z](?![a-z]))*/', '_$0', $fieldName)), '_');
            // POST does not change primary key
            if ($fieldName !== 'LegalSectionId') {
                $legalSectionColumn = $legalSectionTable->getColumn($pascalCaseName);
                if ($legalSectionColumn->isNumeric() && $fieldValue == "") {
                    $fieldValue = null;
                }
                $legalSection->setByName($fieldName, $fieldValue);
                if ($legalSectionColumn->getType() == "DATE") {
                    if ($fieldValue !== '') {
                        $fieldValue = date_create($fieldValue);
                    } else {
                        $fieldValue = null;
                    }
                }
                $legalSectionData[$legalSectionColumn->getName()] = $fieldValue;
            }
        }
        $legalSection->setCommunityId($communityId);

        $legalSection->setCreatedBy($request->getAttribute('userId'));
        $legalSection->setCreatedDate('now');
        $legalSection->setLastModifiedBy($user->getId());
        $legalSection->setLastModifiedDate('now');
        $legalSection->save();
        $legalSectionData['legal_section_id'] = $legalSection->getLegalSectionId();
        $legalSectionData['created_by'] = $user->getId();
        $legalSectionData['created_date'] = $legalSection->getCreatedDate();
        $legalSectionData['last_modified_by'] = $user->getId();
        $legalSectionData['last_modified_date'] = $legalSection->getLastModifiedDate();

        $pervasiveMap = new SyncPervasive(SYSTEM_NAME, 'site-manager', 'Fischer Management', 'community_section_legal', 'create');
        $pervasiveMap->run($legalSectionData);

        // Trigger event to notify users
        $emitter = new EventEmitterComponent;
        $emitter->emit('legal-section-created', [$legalSection->getLegalSectionId()]);

        $result[] = $legalSection->toArray();
    }

    return $response->withJson($result);
})->add(new Validation($validateId));

$app->patch('/slimapi/v1/legalSections', function (Request $request, Response $response) {
    $result = [];
    $legalSectionData = [];

    $allPostPutVars = $request->getParsedBody();
    foreach ($allPostPutVars as $row) {
        // validate community - FIXME make it middleware or at least utility
        // check the existance of community too, not just the id
        if (isset($row['CommunityId']) && $row['CommunityId'] < 1) {
            return $response->withJson(['message' => 'Invalid Community'], 422);
        }
        $legalSection = \CommunitySectionLegalQuery::create()->findOneByLegalSectionId($row['LegalSectionId']);
        $legalSectionTable = \Map\CommunitySectionLegalTableMap::getTableMap();
        foreach ($row as $fieldName => $fieldValue) {
            // Parse all empty strings as null if cell is integer
            $pascalCaseName = ltrim(strtolower(preg_replace('/[A-Z]([A-Z](?![a-z]))*/', '_$0', $fieldName)), '_');
            $legalSectionColumn = $legalSectionTable->getColumn($pascalCaseName);
            // PATCH does not change primary key
            if ($fieldName !== 'LegalSectionId') {
                if ($legalSectionColumn->isNumeric() && $fieldValue == "") {
                    $fieldValue = null;
                }
                $legalSection->setByName($fieldName, $fieldValue);
            }
            if ($legalSectionColumn->getType() == "DATE" && $fieldValue !== "") {
                $fieldValue = date_create($fieldValue);
            }
            $legalSectionData[$legalSectionColumn->getName()] = $fieldValue;
        }

        $legalSection->setLastModifiedBy($request->getAttribute('userId'));
        $legalSection->setLastModifiedDate('now');
        $legalSection->save();
        $legalSectionData['last_modifed_by'] = $user->getId();
        $legalSectionData['last_modified_date'] = $legalSection->getLastModifiedDate();

        $pervasiveMap = new SyncPervasive(SYSTEM_NAME, 'site-manager', 'Fischer Management', 'community_section_legal', 'update');
        $pervasiveMap->run($legalSectionData);

        $result[] = $legalSection->toArray();
    }

    return $response->withJson($result);
});

$app->delete('/slimapi/v1/legalSections/{id}', function (Request $request, Response $response, $args) {
    $id = (int)$args['id'];
    $legalSection = \CommunitySectionLegalQuery::create()->findOneByLegalSectionId($id);
    if ($legalSection) {
        $legalSection->setIsDeleted(1);
        $legalSection->save();
    } else {
        return $response->withStatus(404);
    }

    try {
        $legalSectionData['legal_section_id'] = $id;
        $pervasiveMap = new SyncPervasive(SYSTEM_NAME, 'site-manager', 'Fischer Management', 'community_section_legal', 'delete');
        $pervasiveMap->run($legalSectionData);
    } catch (Exception $exc) {
        // TBD log failure
        // echo $exc->getTraceAsString();
        return $response->withStatus(404);
    }

    return $response->withStatus(204);
})->add(new Validation($validateId));

$app->get('/slimapi/v1/fischerSections', function (Request $request, Response $response) use ($customConfiguration) {
    // Initialization
    $queryParams = $request->getQueryParams();
    $fields = [
        'FischerSection' => [],
        'Community' => [],
        'SpecLevels' => []
        ];
    // Pagination
    if(isset($queryParams['page'])) {
        $customConfiguration['page'] = $queryParams['page'];
    }
    if(isset($queryParams['per_page'])) {
        $customConfiguration['perPage'] = $queryParams['per_page'];
    }
    // Order
    $orderBy = 'SectionName';
    if(isset($queryParams['orderBy'])) {
        $orderBy = $queryParams['orderBy'];
    }
    if(isset($queryParams['orderByAsc'])) {
        $customConfiguration['orderByAsc'] = $queryParams['orderByAsc'];
    }
    // Filters
    $filterFischerSection = [];
    if (isset($queryParams['filter']['FischerSection'])) {
        $filterFischerSection = $queryParams['filter']['FischerSection'];
    }
    $filterCommunity = [];
    if (isset($queryParams['filter']['Community'])) {
        $filterCommunity = $queryParams['filter']['Community'];
    }
    $filterSpecLevels = [];
    if (isset($queryParams['filter']['SpecLevels'])) {
        $filterSpecLevels = $queryParams['filter']['SpecLevels'];
    }
    // Fields
    if(isset($queryParams['fields']['FischerSection'])) {
        $fields['FischerSection'] = explode(',', $queryParams['fields']['FischerSection']);
    } else {
        $fields['FischerSection'] = ['SectionId', 'SectionName'];
    }
    // Includes
    if(isset($queryParams['include'])) {
        $withArray = explode(',', $queryParams['include']);
        if(isset($withArray) && count($withArray) > 0) {
            foreach ($withArray as $with) {
                if(isset($queryParams['fields'][$with])) {
                    $fields[$with] = explode(',', $queryParams['fields'][$with]);
                }
                if($with == 'Community') {
                    $withCommunity = 1;
                } elseif ($with == 'SpecLevels') {
                    $withSpecLevels = 1;
                }
            }
        }
    }
    $fischerSections = \CommunitySectionQuery::create()
            ->addCommunity($withCommunity, $fields['Community'])
            ->addCommunityFilters($filterCommunity)
            ->addSpecLevel($withSpecLevels, $fields['SpecLevels'])
            ->addFischerSectionFilters($filterFischerSection)
            ->orderBy($orderBy, $customConfiguration['orderByAsc'])
            ->orderBySectionId()
            ->paginate($customConfiguration['page'], $customConfiguration['perPage']);

    foreach ($fischerSections as $fischerSection) {
        $tempFischerSection = $fischerSection->toArray();
        if (in_array('Sites', $withArray)) {
            $tempFischerSection['Sites'] = $fischerSection->loadSites();
        }
        $arrFischerSections[] = $tempFischerSection;
    }
    $data = [
        'page' => $fischerSections->getPage(),
        'rows' => $arrFischerSections,
        'totalRecords' => $fischerSections->getNbResults(),
        'totalPages' => $fischerSections->getLastPage()
        ];

    return $response->withJson($data);
});

$app->get('/slimapi/v1/fischerSections/{id}', function (Request $request, Response $response, $args) {
    $queryParams = $request->getQueryParams();
    $id = $args['id'];
    $withCommunity = 0;
    $withSpecLevel = 0;
    if(isset($queryParams['include'])) {
        $withArray = explode(',', $queryParams['include']);
        if(isset($withArray) && count($withArray) > 0) {
            foreach ($withArray as $value) {
                if ($value == 'Community') {
                    $withCommunity = 1;
                } elseif ($value == 'SpecLevel') {
                    $withSpecLevel = 1;
                }
            }
        }
    }
    if(isset($queryParams['fields'])) {
        $fields = explode(',', $queryParams['fields']);
    } else {
        $fischerSectionTable = \Map\CommunitySectionTableMap::getTableMap();
        foreach ($fischerSectionTable->getColumns() as $column) {
            $fields[] = $column->getPhpName();
        }
    }
    $fischerSection = \CommunitySectionQuery::create()
            ->select($fields)
            ->addCommunity($withCommunity)
            ->addSpecLevel($withSpecLevel)
            ->findPK($id);
    if ($fischerSection) {
        return $response->withJson($fischerSection);
    } else {
        // TBD log failure
//        echo $exc->getTraceAsString();
        return $response->withStatus(404);
    }

})->add(new Validation($validateId));

$app->post('/slimapi/v1/communities/{communityId}/fischerSections', function (Request $request, Response $response, $args) {
    $result = [];
    $fischerSectionData = [];

    $communityId = (int)$args['communityId'];
    // validate community - FIXME make it middleware or at least utility
    // check the existance of community too, not just the id
    if ($communityId < 1) {
        return $response->withJson(['message' => 'Invalid Community'], 422);
    }
    $allPostPutVars = $request->getParsedBody();
    foreach ($allPostPutVars as $row) {
        $fischerSection = new \CommunitySection();
        $fischerSectionTable = \Map\CommunitySectionTableMap::getTableMap();
        foreach ($row as $fieldName => $fieldValue) {
            // Parse all empty strings as null if cell is integer
            $pascalCaseName = ltrim(strtolower(preg_replace('/[A-Z]([A-Z](?![a-z]))*/', '_$0', $fieldName)), '_');
            // POST does not change primary key
            if ($fieldName !== 'SectionId') {
                $fischerSectionColumn = $fischerSectionTable->getColumn($pascalCaseName);
                if ($fischerSectionColumn->isNumeric() && $fieldValue == "") {
                    $fieldValue = null;
                }
                $fischerSection->setByName($fieldName, $fieldValue);
                if ($fischerSectionColumn->getType() == "DATE" && $fieldValue !== "") {
                    $fieldValue = date_create($fieldValue);
                }
                $fischerSectionData[$fischerSectionColumn->getName()] = $fieldValue;
            }
        }
        $fischerSection->setCommunityId($communityId);

        $fischerSection->setCreatedBy($request->getAttribute('userId'));
        $fischerSection->setCreatedDate('now');
        $fischerSection->setLastModifiedBy($user->getId());
        $fischerSection->setLastModifiedDate('now');
        $fischerSection->save();
        $fischerSectionData['section_id'] = $fischerSection->getSectionId();
        $fischerSectionData['created_by'] = $user->getId();
        $fischerSectionData['created_date'] = $fischerSection->getCreatedDate();
        $fischerSectionData['last_modified_by'] = $user->getId();
        $fischerSectionData['last_modified_date'] = $fischerSection->getLastModifiedDate();

        $pervasiveMap = new SyncPervasive(SYSTEM_NAME, 'site-manager', 'Fischer Management', 'community_section', 'create');
        $pervasiveMap->run($fischerSectionData);

        // Trigger event to notify users
        $emitter = new EventEmitterComponent;
        $emitter->emit('fischer-section-created', [$fischerSection->getSectionId()]);

        $result[] = $fischerSection->toArray();
    }

    return $response->withJson($result);
})->add(new Validation($validateId));

$app->patch('/slimapi/v1/fischerSections', function (Request $request, Response $response) {
    $result = [];
    $fischerSectionData = [];

    $allPostPutVars = $request->getParsedBody();
    foreach ($allPostPutVars as $row) {
        // validate community - FIXME make it middleware or at least utility
        // check the existance of community too, not just the id
        if (isset($row['CommunityId']) && $row['CommunityId'] < 1) {
            return $response->withJson(['message' => 'Invalid Community'], 422);
        }
        $fischerSection = \CommunitySectionQuery::create()->findOneBySectionId($row['SectionId']);
        $fischerSectionTable = \Map\CommunitySectionTableMap::getTableMap();
        foreach ($row as $fieldName => $fieldValue) {
            // Parse all empty strings as null if cell is integer
            $pascalCaseName = ltrim(strtolower(preg_replace('/[A-Z]([A-Z](?![a-z]))*/', '_$0', $fieldName)), '_');
            $fischerSectionColumn = $fischerSectionTable->getColumn($pascalCaseName);
            // PATCH does not change primary key
            if ($fieldName !== 'SectionId') {
                if ($fischerSectionColumn->isNumeric() && $fieldValue == "") {
                    $fieldValue = null;
                }
                $fischerSection->setByName($fieldName, $fieldValue);
            }
            if ($fischerSectionColumn->getType() == "DATE" && $fieldValue !== "") {
                $fieldValue = date_create($fieldValue);
            }
            $fischerSectionData[$fischerSectionColumn->getName()] = $fieldValue;
        }

        $fischerSection->setLastModifiedBy($request->getAttribute('userId'));
        $fischerSection->setLastModifiedDate('now');
        $fischerSection->save();
        $fischerSectionData['section_id'] = $fischerSection->getSectionId();
        $fischerSectionData['last_modified_by'] = $user->getId();
        $fischerSectionData['last_modified_date'] = $fischerSection->getLastModifiedDate();

        $pervasiveMap = new SyncPervasive(SYSTEM_NAME, 'site-manager', 'Fischer Management', 'community_section', 'update');
        $pervasiveMap->run($fischerSectionData);

        $result[] = $fischerSection->toArray();
    }

    return $response->withJson($result);
});

$app->delete('/slimapi/v1/fischerSections/{id}', function (Request $request, Response $response, $args) {
    $id = (int)$args['id'];

    $fischerSection = \CommunitySectionQuery::create()->findOneBySectionId($id);

    if ($fischerSection) {
        $fischerSection->setIsDeleted(1);
        $fischerSection->save();
    } else {
        return $response->withStatus(404);
    }

    try {
        $fischerSectionData['section_id'] = $id;
        $pervasiveMap = new SyncPervasive(SYSTEM_NAME, 'site-manager', 'Fischer Management', 'community_section', 'delete');
        $pervasiveMap->run($fischerSectionData);
    } catch (Exception $exc) {
        // TBD log failure
//        echo $exc->getTraceAsString();
        return $response->withStatus(404);
    }

    return $response->withStatus(204);
})->add(new Validation($validateId));

/*
 * Spec Level
 *
 */
$app->get('/slimapi/v1/specLevels', function (Request $request, Response $response) use ($customConfiguration) {

    $queryParams = $request->getQueryParams();

    if(isset($queryParams['page'])) {
        $customConfiguration['page'] = $queryParams['page'];
    }
    if(isset($queryParams['per_page'])) {
        $customConfiguration['perPage'] = $queryParams['per_page'];
    }
    if(isset($queryParams['is_deleted'])) {
        $customConfiguration['isDeletedFilter'] = $queryParams['is_deleted'];
    }

    $specLevels = \SpecLevelsQuery::create()->orderBy('SpecLevelDescr')->paginate($customConfiguration['page'], $customConfiguration['perPage']);
    $response->getBody()->write($specLevels->toJson());

    return $response;
});

/*
 * Division
 *
 */
$app->get('/slimapi/v1/divisions', function (Request $request, Response $response) use ($customConfiguration) {

    $queryParams = $request->getQueryParams();

    if(isset($queryParams['page'])) {
        $customConfiguration['page'] = $queryParams['page'];
    }
    if(isset($queryParams['per_page'])) {
        $customConfiguration['perPage'] = $queryParams['per_page'];
    }
    if(isset($queryParams['is_deleted'])) {
        $customConfiguration['isDeletedFilter'] = $queryParams['is_deleted'];
    }

    $divisions = \DivisionQuery::create()->paginate($customConfiguration['page'], $customConfiguration['perPage']);
    $response->getBody()->write($divisions->toJson());

    return $response;
});

/*
 * Budget Neighborhood
 *
 */
$app->get('/slimapi/v1/budgetNeighborhoods', function (Request $request, Response $response) use ($customConfiguration) {

    $queryParams = $request->getQueryParams();

    if(isset($queryParams['page'])) {
        $customConfiguration['page'] = $queryParams['page'];
    }
    if(isset($queryParams['per_page'])) {
        $customConfiguration['perPage'] = $queryParams['per_page'];
    }
    if(isset($queryParams['is_deleted'])) {
        $customConfiguration['isDeletedFilter'] = $queryParams['is_deleted'];
    }

    $budgetNeighborhoods = \test\BdgtneighborhoodQuery::create()->paginate($customConfiguration['page'], $customConfiguration['perPage']);
    $response->getBody()->write($budgetNeighborhoods->toJson());

    return $response;
});

/*
 * Company
 *
 */
$app->get('/slimapi/v1/companies', function (Request $request, Response $response) use ($customConfiguration) {
    $queryParams = $request->getQueryParams();

    if(isset($queryParams['page'])) {
        $customConfiguration['page'] = $queryParams['page'];
    }
    if(isset($queryParams['per_page'])) {
        $customConfiguration['perPage'] = $queryParams['per_page'];
    }
    if(isset($queryParams['is_deleted'])) {
        $customConfiguration['isDeletedFilter'] = $queryParams['is_deleted'];
    }

    $filter = [];
    if (isset($queryParams['filter']) && is_array($queryParams['filter'])) {
        $filter = $queryParams['filter'];
    }
    $orderBy = 'FullName';
    if(isset($queryParams['orderBy'])) {
        $orderBy = $queryParams['orderBy'];
    }
    if(isset($queryParams['orderByAsc'])) {
        $customConfiguration['orderByAsc'] = $queryParams['orderByAsc'];
    }

    $companies = \CompanyQuery::create()
            ->filterByIsDeleted($customConfiguration['isDeletedFilter'])
            ->filterByArray($filter)
            ->orderBy($orderBy, $customConfiguration['orderByAsc'])
            ->paginate($customConfiguration['page'], $customConfiguration['perPage']);
    $data = [
        'rows' => $companies->toArray(),
        'totalRecords' => $companies->getNbResults(),
        'totalPages' => $companies->getLastPage()
        ];

    return $response->withJson($data);
});

/*
 * Attached Building Catalog
 *
 */
$app->get('/slimapi/v1/attachedBuildingCatalogs', function (Request $request, Response $response) use ($customConfiguration) {
    $queryParams = $request->getQueryParams();

    if(isset($queryParams['page'])) {
        $customConfiguration['page'] = $queryParams['page'];
    }
    if(isset($queryParams['per_page'])) {
        $customConfiguration['perPage'] = $queryParams['per_page'];
    }
//    if(isset($queryParams['is_deleted'])) {
//        $customConfiguration['isDeletedFilter'] = $queryParams['is_deleted'];
//    }

    $filter = [];
    if (isset($queryParams['filter']) && is_array($queryParams['filter'])) {
        $filter = $queryParams['filter'];
    }
    $orderBy = 'BuildingName';
    if(isset($queryParams['orderBy'])) {
        $orderBy = $queryParams['orderBy'];
    }
    if(isset($queryParams['orderByAsc'])) {
        $customConfiguration['orderByAsc'] = $queryParams['orderByAsc'];
    }

    $attachedBuildingCatalogs = \AttachedBuildingCatalogQuery::create()
//            ->filterByIsDeleted($customConfiguration['isDeletedFilter'])
            ->filterByArray($filter)
            ->orderBy($orderBy, $customConfiguration['orderByAsc'])
            ->paginate($customConfiguration['page'], $customConfiguration['perPage']);
    $data = [
        'rows' => $attachedBuildingCatalogs->toArray(),
        'totalRecords' => $attachedBuildingCatalogs->getNbResults(),
        'totalPages' => $attachedBuildingCatalogs->getLastPage()
        ];

    return $response->withJson($data);
});

/*
 * User
 *
 */
$app->get('/slimapi/v1/users/{id}', function (Request $request, Response $response, $args) {

    $headers = $request->getHeaders();
    // check authorization
    if (!isset($headers['HTTP_AUTHORIZATION'][0]) || $headers['HTTP_AUTHORIZATION'][0] != '1fc7674f9e28738878555e5e295e366f4ab9ee3b5c5ebcccaae6f1abc05d581d') {
        $data = [];
        $data["status"] = "error";
        $data["message"] = 'Authorization failed';
        return $response->withJson($data, 401);
    }

    // Validate input
    if($request->getAttribute('has_errors')){
            $errors = $request->getAttribute('errors');
            $data = [];
            $data["status"] = "error";
            $data["message"] = $errors;
            return $response->withJson($data, 422);
    }

    $id = $args['id'];
    $user = \UsersQuery::create()->select(['FischerUsername', 'Email', 'FirstName', 'Groups'])->findPK($id);
    // test if $data exists
    if ($user)
    {
        return $response->withJson($user);
    }
    return $response->withStatus(404);
})->add(new Validation($validateId));

$app->get('/slimapi/v1/users/{id}/applicationConfigurations', function (Request $request, Response $response, $args) {
    // Validate input
    if($request->getAttribute('has_errors')){
            $errors = $request->getAttribute('errors');
            $data = [];
            $data["status"] = "error";
            $data["message"] = $errors;
            return $response->withJson($data, 422);
    }

    $id = $args['id'];
    $user = \UsersQuery::create()->select(['Email', 'FirstName', 'Groups'])->findPK($id);
    // test if $data exists
    $groups = json_decode($user['Groups']);
    // test if groups is traversible
    foreach ($groups as $value) {
        $exploded = explode(',', $value);

        $userConfig = substr($exploded[0], 3);

        if ($userConfig)
            return $response->withJson($userConfig);
    }
    return $response->withJson('');
})->add(new Validation($validateId));

$app->get('/slimapi/v1/sapphireUsers/{id}', function (Request $request, Response $response, $args) {
    $headers = $request->getHeaders();
    // check authorization
    if (!isset($headers['HTTP_AUTHORIZATION'][0]) || $headers['HTTP_AUTHORIZATION'][0] != '1fc7674f9e28738878555e5e295e366f4ab9ee3b5c5ebcccaae6f1abc05d581d') {
        return $response->withJson(["status" => "error", "message" => "Authorization failed"], 401);
    }

    // Validate input
    if($request->getAttribute('has_errors')){
        return $response->withJson(["status" => "error", "message" => $request->getAttribute('errors')], 422);
    }

    $arrUser = \UsersQuery::create()->select(['Id', 'FischerUsername', 'Email', 'FirstName', 'LastName'])->findOneBySapphireUserId($args['id']);
    if (!$arrUser) {
        // Query Sapphire database - use new component
        $sapphire = new QuerySapphireDbComponent;
        if (!$sapphireUser = $sapphire->getUserDetails($args['id'])) {
            return $response->withJson(["status" => "error", "message" => "Sapphire user not found"], 422);
        } else {
            // TODO Find details from LDAP
            // Save details to MySQL
            $user = \UsersQuery::create()->findOneByFischerUsername($sapphireUser['username']);
            // TODO If Sapphire user does not exist
            // throw exception (so sentry can pick up)
            // and response 422
            $user->setSapphireUserId($args['id']);
            $user->save();
            $arrUser['Id'] = $user->getId();
            $arrUser['FischerUsername'] = $user->getFischerUsername();
            $arrUser['Email'] = $user->getEmail();
            $arrUser['FirstName'] = $user->getFirstName();
            $arrUser['LastName'] = $user->getLastName();
        }
    }

    // FIXME use middleware for CORS
    // return user
    return $response->withJson($arrUser);
})->add(new Validation($validateId));

//
$app->get('/slimapi/v1/users/{id}/dashboards', function (Request $request, Response $response, $args) {
    $headers = $request->getHeaders();
    // check authorization
    if (!isset($headers['HTTP_AUTHORIZATION'][0]) || $headers['HTTP_AUTHORIZATION'][0] != '1fc7674f9e28738878555e5e295e366f4ab9ee3b5c5ebcccaae6f1abc05d581d') {
        return $response->withJson(["status" => "error", "message" => "Authorization failed"], 401);
    }

    // Validate input
    if($request->getAttribute('has_errors')){
        return $response->withJson(["status" => "error", "message" => $request->getAttribute('errors')], 422);
    }

    // TODO Add filters

    // TODO Add pagination

    // Get User Role
    $user = \UsersQuery::create()
            ->addRole(true, ['RoleId', 'Name'])
            ->findPK($args['id']);
    if(!$user) {
        $errors = $request->getAttribute('errors');
        $data = [];
        $data["status"] = "error";
        $data["message"] = 'Wrong user id.';
        return $response->withJson($data, 422);
    }
    // Get Role Dashboards
    $dashboardRoles = \DashboardRoleQuery::create()
            ->filterByRoleId($user->getRoleId())
            ->addDashboard(true, ['Name'])
            ->select(['DashboardId', 'Default'])
            ->find();

    return $response->withJson($dashboardRoles->toArray());
})->add(new Validation($validateId));

/*
 * Mortgage, Legal Section
 *
 */
$app->get('/slimapi/v1/mortgages', function (Request $request, Response $response) use ($customConfiguration) {

    $queryParams = $request->getQueryParams();
    $fields = [
        'Mortgage' => [],
        'LegalSection' => []
        ];
    $withLegalSection = 0;

    if(isset($queryParams['page'])) {
        $customConfiguration['page'] = $queryParams['page'];
    }
    if(isset($queryParams['per_page'])) {
        $customConfiguration['perPage'] = $queryParams['per_page'];
    }
    $orderBy = 'Info';
    if(isset($queryParams['orderBy'])) {
        $orderBy = $queryParams['orderBy'];
    }
    if(isset($queryParams['orderByAsc'])) {
        $customConfiguration['orderByAsc'] = $queryParams['orderByAsc'];
    }

    // Filters
    $filterMortgage = [];
    if (isset($queryParams['filter']['Mortgage'])) {
        $filterMortgage = $queryParams['filter']['Mortgage'];
    }
    $filterLegalSection = [];
    if (isset($queryParams['filter']['LegalSection'])) {
        $filterLegalSection = $queryParams['filter']['LegalSection'];
    }
    // Fields
    if(isset($queryParams['fields']['Mortgage'])) {
        $fields['Mortgage'] = explode(',', $queryParams['fields']['Mortgage']);
    } else {
        $fields['Mortgage'] = ['MortgageId', 'LegalsectionId', 'Info', 'Book', 'Page', 'Amount', 'Date', 'RelDate'];
    }
    if(isset($queryParams['include'])) {
        $withArray = explode(',', $queryParams['include']);
        if(isset($withArray) && count($withArray) > 0) {
            foreach ($withArray as $with) {
                if(isset($queryParams['fields'][$with])) {
                    $fields[$with] = explode(',', $queryParams['fields'][$with]);
                }
                if($with == 'LegalSection') {
                    $withLegalSection = 1;
                }
            }
        }
    }

    $mortgages = \MortgageQuery::create()
            ->addLegalSection($withLegalSection, $fields['LegalSection'])
            ->addLegalSectionFilters($filterLegalSection)
            ->filterByIsDeleted($customConfiguration['isDeletedFilter'])
            ->filterByArray($filterMortgage)
            ->orderBy($orderBy, $customConfiguration['orderByAsc'])
            ->paginate($customConfiguration['page'], $customConfiguration['perPage']);
    $data = [
        'page' => $mortgages->getPage(),
        'rows' => $mortgages->toArray(),
        'totalRecords' => $mortgages->getNbResults(),
        'totalPages' => $mortgages->getLastPage()
        ];

    return $response->withJson($data);
});

$app->get('/slimapi/v1/mortgages/{id}', function (Request $request, Response $response, $args) {

    $queryParams = $request->getQueryParams();
    $id = $args['id'];
    $withLegalSection = false;
    $fields = false;
    // Should be middleware, or at least parsed the same way on all calls
    if(isset($queryParams['include'])) {
        $withArray = explode(',', $queryParams['include']);
        if(isset($withArray) && count($withArray) > 0) {
            foreach ($withArray as $value) {
                if($value == 'LegalSection') {
                    $withLegalSection = true;
                }
            }
        }
    }

    if(isset($queryParams['fields'])) {
        $fields = explode(',', $queryParams['fields']);
    }

    $mortgage = \MortgageQuery::create()->addLegalSection($withLegalSection)->findPk($id);
    $arrMortgage = $mortgage->toArray();

    // Parsing select parameter
    // Should be middleware, or at least parsed the same way on all calls
    foreach ($arrMortgage as $key => $value) {
        if ($fields && !in_array($key, $fields)) {
            unset($arrMortgage[$key]);
        }
    }

    return $response->withJson($arrMortgage);
})->add(new Validation($validateId));

$app->patch('/slimapi/v1/mortgages', function (Request $request, Response $response) {
    $result = [];
    $mortgageData = [];

    $allPostPutVars = $request->getParsedBody();
    foreach ($allPostPutVars as $row) {
        // validate community - FIXME make it middleware or at least utility
        // check the existance of community too, not just the id
        if (isset($row['LegalsectionId']) && $row['LegalsectionId'] < 1) {
            return $response->withJson(['message' => 'Invalid Legal Section'], 422);
        }
        $mortgage = \MortgageQuery::create()->findOneByMortgageId($row['MortgageId']);
        $mortgageTable = \Map\MortgageTableMap::getTableMap();
        foreach ($row as $fieldName => $fieldValue) {
            // Parse all empty strings as null if cell is integer
            $pascalCaseName = ltrim(strtolower(preg_replace('/[A-Z]([A-Z](?![a-z]))*/', '_$0', $fieldName)), '_');
            $mortgageColumn = $mortgageTable->getColumn($pascalCaseName);
            // PATCH does not change primary key
            if ($fieldName !== 'MortgageId') {
                if ($mortgageColumn->isNumeric() && $fieldValue == "") {
                    $fieldValue = null;
                }
                if ($mortgageColumn->getType() == "TIMESTAMP") {
                    if ($fieldValue !== '') {
                        $fieldValue = date_create($fieldValue);
                    } else {
                        $fieldValue = null;
                    }
                }
                $mortgage->setByName($fieldName, $fieldValue);
            }
        }

        $mortgage->setLastModifiedBy($request->getAttribute('userId'));
        $mortgage->setLastModifiedDate('now');
        $mortgage->save();

        $result[] = $mortgage->toArray();
    }

    $legalSectionId = $mortgage->getLegalSectionId();
    // get all mortgages for this legal_section
    $mortgages = \MortgageQuery::create()->filterByIsDeleted(0)->findByLegalsectionId($legalSectionId)->toArray();
    // create data json
    $mortgageData['ComLegalSecRecordId']['type'] = 'integer';
    $mortgageData['ComLegalSecRecordId']['value'] = $legalSectionId;
    if (isset($mortgages[0])) {
        $mortgageData['FirstMortgageInfo']['type'] = 'varchar';
        $mortgageData['FirstMortgageInfo']['value'] = $mortgages[0]['Info'];
        $mortgageData['FirstMortgageBook']['type'] = 'varchar';
        $mortgageData['FirstMortgageBook']['value'] = $mortgages[0]['Book'];
        $mortgageData['FirstMortgagePage']['type'] = 'varchar';
        $mortgageData['FirstMortgagePage']['value'] = $mortgages[0]['Page'];
        $mortgageData['FirstMortgageAmount']['type'] = 'double';
        $mortgageData['FirstMortgageAmount']['value'] = $mortgages[0]['Amount'];
        $mortgageData['FirstMortgageDate']['type'] = 'date';
        if ($mortgages[0]['Date'] === '') {
            $mortgages[0]['Date'] = null;
        }
        if($mortgages[0]['Date'] !== null) {
            $mortgageData['FirstMortgageDate']['value'] = date_format(date_create($mortgages[0]['Date']),"Y-m-d H:i:s");
        }
        $mortgageData['FirstMortgageRelDate']['type'] = 'date';
        if ($mortgages[0]['RelDate'] === '') {
            $mortgages[0]['RelDate'] = null;
        }
        if($mortgages[0]['RelDate'] !== null) {
            $mortgageData['FirstMortgageRelDate']['value'] = date_format(date_create($mortgages[0]['RelDate']),"Y-m-d H:i:s");
        }
    } else {
        $mortgageData['FirstMortgageInfo']['type'] = 'varchar';
        $mortgageData['FirstMortgageInfo']['value'] = '';
        $mortgageData['FirstMortgageBook']['type'] = 'varchar';
        $mortgageData['FirstMortgageBook']['value'] = '';
        $mortgageData['FirstMortgagePage']['type'] = 'varchar';
        $mortgageData['FirstMortgagePage']['value'] = '';
        $mortgageData['FirstMortgageAmount']['type'] = 'double';
        $mortgageData['FirstMortgageAmount']['value'] = null;
        $mortgageData['FirstMortgageDate']['type'] = 'date';
        $mortgageData['FirstMortgageDate']['value'] = null;
        $mortgageData['FirstMortgageRelDate']['type'] = 'date';
        $mortgageData['FirstMortgageRelDate']['value'] = null;
    }
    if (isset($mortgages[1])) {
        $mortgageData['SecondMortgageInfo']['type'] = 'varchar';
        $mortgageData['SecondMortgageInfo']['value'] = $mortgages[1]['Info'];
        $mortgageData['SecondMortgageBook']['type'] = 'varchar';
        $mortgageData['SecondMortgageBook']['value'] = $mortgages[1]['Book'];
        $mortgageData['SecondMortgagePage']['type'] = 'varchar';
        $mortgageData['SecondMortgagePage']['value'] = $mortgages[1]['Page'];
        $mortgageData['SecondMortgageAmount']['type'] = 'double';
        $mortgageData['SecondMortgageAmount']['value'] = $mortgages[1]['Amount'];
        $mortgageData['SecondMortgageDate']['type'] = 'date';
        if ($mortgages[1]['Date'] === '') {
            $mortgages[1]['Date'] = null;
        }
        if($mortgages[1]['Date'] !== null) {
            $mortgageData['SecondMortgageDate']['value'] = date_format(date_create($mortgages[1]['Date']),"Y-m-d H:i:s");
        }
        $mortgageData['SecondMortgagRelDate']['type'] = 'date';
        if ($mortgages[1]['RelDate'] === '') {
            $mortgages[1]['RelDate'] = null;
        }
        if($mortgages[1]['RelDate'] !== null) {
            $mortgageData['SecondMortgagRelDate']['value'] = date_format(date_create($mortgages[1]['RelDate']),"Y-m-d H:i:s");
        }
    } else {
        $mortgageData['SecondMortgageInfo']['type'] = 'varchar';
        $mortgageData['SecondMortgageInfo']['value'] = '';
        $mortgageData['SecondMortgageBook']['type'] = 'varchar';
        $mortgageData['SecondMortgageBook']['value'] = '';
        $mortgageData['SecondMortgagePage']['type'] = 'varchar';
        $mortgageData['SecondMortgagePage']['value'] = '';
        $mortgageData['SecondMortgageAmount']['type'] = 'double';
        $mortgageData['SecondMortgageAmount']['value'] = null;
        $mortgageData['SecondMortgageDate']['type'] = 'date';
        $mortgageData['SecondMortgageDate']['value'] = null;
        $mortgageData['SecondMortgagRelDate']['type'] = 'date';
        $mortgageData['SecondMortgagRelDate']['value'] = null;
    }
    if (isset($mortgages[2])) {
        $mortgageData['ThirdMortgageInfo']['type'] = 'varchar';
        $mortgageData['ThirdMortgageInfo']['value'] = $mortgages[2]['Info'];
        $mortgageData['ThirdMortgageBook']['type'] = 'varchar';
        $mortgageData['ThirdMortgageBook']['value'] = $mortgages[2]['Book'];
        $mortgageData['ThirdMortgagePage']['type'] = 'varchar';
        $mortgageData['ThirdMortgagePage']['value'] = $mortgages[2]['Page'];
        $mortgageData['ThirdMortgageAmount']['type'] = 'double';
        $mortgageData['ThirdMortgageAmount']['value'] = $mortgages[2]['Amount'];
        $mortgageData['ThirdMortgageDate']['type'] = 'date';
        if ($mortgages[2]['Date'] === '') {
            $mortgages[2]['Date'] = null;
        }
        if($mortgages[2]['Date'] !== null) {
            $mortgageData['ThirdMortgageDate']['value'] = date_format(date_create($mortgages[2]['Date']),"Y-m-d H:i:s");
        }
        $mortgageData['ThirdMortgageRelDate']['type'] = 'date';
        if ($mortgages[2]['RelDate'] === '') {
            $mortgages[2]['RelDate'] = null;
        }
        if($mortgages[2]['RelDate'] !== null) {
            $mortgageData['ThirdMortgageRelDate']['value'] = date_format(date_create($mortgages[2]['RelDate']),"Y-m-d H:i:s");
        }
    } else {
        $mortgageData['ThirdMortgageInfo']['type'] = 'varchar';
        $mortgageData['ThirdMortgageInfo']['value'] = '';
        $mortgageData['ThirdMortgageBook']['type'] = 'varchar';
        $mortgageData['ThirdMortgageBook']['value'] = '';
        $mortgageData['ThirdMortgagePage']['type'] = 'varchar';
        $mortgageData['ThirdMortgagePage']['value'] = '';
        $mortgageData['ThirdMortgageAmount']['type'] = 'double';
        $mortgageData['ThirdMortgageAmount']['value'] = null;
        $mortgageData['ThirdMortgageDate']['type'] = 'date';
        $mortgageData['ThirdMortgageDate']['value'] = null;
        $mortgageData['ThirdMortgageRelDate']['type'] = 'date';
        $mortgageData['ThirdMortgageRelDate']['value'] = null;
    }
    if (isset($mortgages[3])) {
        $mortgageData['FourthMortgageInfo']['type'] = 'varchar';
        $mortgageData['FourthMortgageInfo']['value'] = $mortgages[3]['Info'];
        $mortgageData['FourthMortgageBook']['type'] = 'varchar';
        $mortgageData['FourthMortgageBook']['value'] = $mortgages[3]['Book'];
        $mortgageData['FourthMortgagePage']['type'] = 'varchar';
        $mortgageData['FourthMortgagePage']['value'] = $mortgages[3]['Page'];
        $mortgageData['FourthMortgageAmount']['type'] = 'double';
        $mortgageData['FourthMortgageAmount']['value'] = $mortgages[3]['Amount'];
        $mortgageData['FourthMortgageDate']['type'] = 'date';
        if ($mortgages[3]['Date'] === '') {
            $mortgages[3]['Date'] = null;
        }
        if($mortgages[3]['Date'] !== null) {
            $mortgageData['FourthMortgageDate']['value'] = date_format(date_create($mortgages[3]['Date']),"Y-m-d H:i:s");
        }
        $mortgageData['FourthMortgagRelDate']['type'] = 'date';
        if ($mortgages[3]['RelDate'] === '') {
            $mortgages[3]['RelDate'] = null;
        }
        if($mortgages[3]['RelDate'] !== null) {
            $mortgageData['FourthMortgagRelDate']['value'] = date_format(date_create($mortgages[3]['RelDate']),"Y-m-d H:i:s");
        }
    } else {
        $mortgageData['FourthMortgageInfo']['type'] = 'varchar';
        $mortgageData['FourthMortgageInfo']['value'] = '';
        $mortgageData['FourthMortgageBook']['type'] = 'varchar';
        $mortgageData['FourthMortgageBook']['value'] = '';
        $mortgageData['FourthMortgagePage']['type'] = 'varchar';
        $mortgageData['FourthMortgagePage']['value'] = '';
        $mortgageData['FourthMortgageAmount']['type'] = 'double';
        $mortgageData['FourthMortgageAmount']['value'] = null;
        $mortgageData['FourthMortgageDate']['type'] = 'date';
        $mortgageData['FourthMortgageDate']['value'] = null;
        $mortgageData['FourthMortgagRelDate']['type'] = 'date';
        $mortgageData['FourthMortgagRelDate']['value'] = null;
    }

    // create SystemSunc record
    $systemSync = new \SystemSync();
    $systemSync->setSystem(SYSTEM_NAME);
    $systemSync->setApplication('site-manager');
    $systemSync->setPervasiveDatabase('Fischer Management');
    $systemSync->setPervasiveTable('CommunitySectionLega');
    $systemSync->setAction('update');
    $systemSync->setKeyFields('ComLegalSecRecordId');
    $systemSync->setData(json_encode($mortgageData));
    $systemSync->setCreatedDate('now');
    $systemSync->save();

    return $response->withJson($result);
});

$app->post('/slimapi/v1/legalSections/{id}/mortgages', function (Request $request, Response $response, $args) {
    $result = [];
    $mortgageData = [];

    $legalSectionId = (int)$args['id'];
    // Validate input
    if(!($legalSectionId > 0)){
      //            $errors = $request->getAttribute('errors');
            $data = [];
            $data["status"] = "error";
            $data["message"] = 'Invalid Legal Section Id';
            return $response->withJson($data, 422);
    }
    $allPostPutVars = $request->getParsedBody();
    foreach ($allPostPutVars as $row) {
        $mortgage = new \Mortgage();
        $mortgageTable = \Map\MortgageTableMap::getTableMap();
        foreach ($row as $fieldName => $fieldValue) {
            $pascalCaseName = ltrim(strtolower(preg_replace('/[A-Z]([A-Z](?![a-z]))*/', '_$0', $fieldName)), '_');
            // POST creates primary key
            if ($fieldName !== 'MortgageId') {
                $mortgageColumn = $mortgageTable->getColumn($pascalCaseName);
                // Parse all empty strings as null if cell is integer
                if ($mortgageColumn->isNumeric() && $fieldValue == "") {
                    $fieldValue = null;
                }
                if ($mortgageColumn->getType() == "TIMESTAMP") {
                    if ($fieldValue !== '') {
                        $fieldValue = date_create($fieldValue);
                    } else {
                        $fieldValue = null;
                    }
                }
                $mortgage->setByName($fieldName, $fieldValue);
            }
        }

        $mortgage->setLegalsectionId($legalSectionId);

        $mortgage->setCreatedBy($request->getAttribute('userId'));
        $mortgage->setCreatedDate('now');
        $mortgage->setLastModifiedBy($user->getId());
        $mortgage->setLastModifiedDate('now');
        $mortgage->save();

        $result[] = $mortgage->toArray();
    }

    // get all mortgages for this legal_section
    $mortgages = \MortgageQuery::create()->filterByIsDeleted(0)->findByLegalsectionId($legalSectionId)->toArray();
    // create data json
    $mortgageData['ComLegalSecRecordId']['type'] = 'integer';
    $mortgageData['ComLegalSecRecordId']['value'] = $legalSectionId;
    if (isset($mortgages[0])) {
        $mortgageData['FirstMortgageInfo']['type'] = 'varchar';
        $mortgageData['FirstMortgageInfo']['value'] = $mortgages[0]['Info'];
        $mortgageData['FirstMortgageBook']['type'] = 'varchar';
        $mortgageData['FirstMortgageBook']['value'] = $mortgages[0]['Book'];
        $mortgageData['FirstMortgagePage']['type'] = 'varchar';
        $mortgageData['FirstMortgagePage']['value'] = $mortgages[0]['Page'];
        $mortgageData['FirstMortgageAmount']['type'] = 'double';
        $mortgageData['FirstMortgageAmount']['value'] = $mortgages[0]['Amount'];
        $mortgageData['FirstMortgageDate']['type'] = 'date';
        if ($mortgages[0]['Date'] === '') {
            $mortgages[0]['Date'] = null;
        }
        if($mortgages[0]['Date'] !== null) {
            $mortgageData['FirstMortgageDate']['value'] = date_format(date_create($mortgages[0]['Date']),"Y-m-d H:i:s");
        }
        $mortgageData['FirstMortgageRelDate']['type'] = 'date';
        if ($mortgages[0]['RelDate'] === '') {
            $mortgages[0]['RelDate'] = null;
        }
        if($mortgages[0]['RelDate'] !== null) {
            $mortgageData['FirstMortgageRelDate']['value'] = date_format(date_create($mortgages[0]['RelDate']),"Y-m-d H:i:s");
        }
    } else {
        $mortgageData['FirstMortgageInfo']['type'] = 'varchar';
        $mortgageData['FirstMortgageInfo']['value'] = '';
        $mortgageData['FirstMortgageBook']['type'] = 'varchar';
        $mortgageData['FirstMortgageBook']['value'] = '';
        $mortgageData['FirstMortgagePage']['type'] = 'varchar';
        $mortgageData['FirstMortgagePage']['value'] = '';
        $mortgageData['FirstMortgageAmount']['type'] = 'double';
        $mortgageData['FirstMortgageAmount']['value'] = null;
        $mortgageData['FirstMortgageDate']['type'] = 'date';
        $mortgageData['FirstMortgageDate']['value'] = null;
        $mortgageData['FirstMortgageRelDate']['type'] = 'date';
        $mortgageData['FirstMortgageRelDate']['value'] = null;
    }
    if (isset($mortgages[1])) {
        $mortgageData['SecondMortgageInfo']['type'] = 'varchar';
        $mortgageData['SecondMortgageInfo']['value'] = $mortgages[1]['Info'];
        $mortgageData['SecondMortgageBook']['type'] = 'varchar';
        $mortgageData['SecondMortgageBook']['value'] = $mortgages[1]['Book'];
        $mortgageData['SecondMortgagePage']['type'] = 'varchar';
        $mortgageData['SecondMortgagePage']['value'] = $mortgages[1]['Page'];
        $mortgageData['SecondMortgageAmount']['type'] = 'double';
        $mortgageData['SecondMortgageAmount']['value'] = $mortgages[1]['Amount'];
        $mortgageData['SecondMortgageDate']['type'] = 'date';
        if ($mortgages[1]['Date'] === '') {
            $mortgages[1]['Date'] = null;
        }
        if($mortgages[1]['Date'] !== null) {
            $mortgageData['SecondMortgageDate']['value'] = date_format(date_create($mortgages[1]['Date']),"Y-m-d H:i:s");
        }
        $mortgageData['SecondMortgagRelDate']['type'] = 'date';
        if ($mortgages[1]['RelDate'] === '') {
            $mortgages[1]['RelDate'] = null;
        }
        if($mortgages[1]['RelDate'] !== null) {
            $mortgageData['SecondMortgagRelDate']['value'] = date_format(date_create($mortgages[1]['RelDate']),"Y-m-d H:i:s");
        }
    } else {
        $mortgageData['SecondMortgageInfo']['type'] = 'varchar';
        $mortgageData['SecondMortgageInfo']['value'] = '';
        $mortgageData['SecondMortgageBook']['type'] = 'varchar';
        $mortgageData['SecondMortgageBook']['value'] = '';
        $mortgageData['SecondMortgagePage']['type'] = 'varchar';
        $mortgageData['SecondMortgagePage']['value'] = '';
        $mortgageData['SecondMortgageAmount']['type'] = 'double';
        $mortgageData['SecondMortgageAmount']['value'] = null;
        $mortgageData['SecondMortgageDate']['type'] = 'date';
        $mortgageData['SecondMortgageDate']['value'] = null;
        $mortgageData['SecondMortgagRelDate']['type'] = 'date';
        $mortgageData['SecondMortgagRelDate']['value'] = null;
    }
    if (isset($mortgages[2])) {
        $mortgageData['ThirdMortgageInfo']['type'] = 'varchar';
        $mortgageData['ThirdMortgageInfo']['value'] = $mortgages[2]['Info'];
        $mortgageData['ThirdMortgageBook']['type'] = 'varchar';
        $mortgageData['ThirdMortgageBook']['value'] = $mortgages[2]['Book'];
        $mortgageData['ThirdMortgagePage']['type'] = 'varchar';
        $mortgageData['ThirdMortgagePage']['value'] = $mortgages[2]['Page'];
        $mortgageData['ThirdMortgageAmount']['type'] = 'double';
        $mortgageData['ThirdMortgageAmount']['value'] = $mortgages[2]['Amount'];
        $mortgageData['ThirdMortgageDate']['type'] = 'date';
        if ($mortgages[2]['Date'] === '') {
            $mortgages[2]['Date'] = null;
        }
        if($mortgages[2]['Date'] !== null) {
            $mortgageData['ThirdMortgageDate']['value'] = date_format(date_create($mortgages[2]['Date']),"Y-m-d H:i:s");
        }
        $mortgageData['ThirdMortgageRelDate']['type'] = 'date';
        if ($mortgages[2]['RelDate'] === '') {
            $mortgages[2]['RelDate'] = null;
        }
        if($mortgages[2]['RelDate'] !== null) {
            $mortgageData['ThirdMortgageRelDate']['value'] = date_format(date_create($mortgages[2]['RelDate']),"Y-m-d H:i:s");
        }
    } else {
        $mortgageData['ThirdMortgageInfo']['type'] = 'varchar';
        $mortgageData['ThirdMortgageInfo']['value'] = '';
        $mortgageData['ThirdMortgageBook']['type'] = 'varchar';
        $mortgageData['ThirdMortgageBook']['value'] = '';
        $mortgageData['ThirdMortgagePage']['type'] = 'varchar';
        $mortgageData['ThirdMortgagePage']['value'] = '';
        $mortgageData['ThirdMortgageAmount']['type'] = 'double';
        $mortgageData['ThirdMortgageAmount']['value'] = null;
        $mortgageData['ThirdMortgageDate']['type'] = 'date';
        $mortgageData['ThirdMortgageDate']['value'] = null;
        $mortgageData['ThirdMortgageRelDate']['type'] = 'date';
        $mortgageData['ThirdMortgageRelDate']['value'] = null;
    }
    if (isset($mortgages[3])) {
        $mortgageData['FourthMortgageInfo']['type'] = 'varchar';
        $mortgageData['FourthMortgageInfo']['value'] = $mortgages[3]['Info'];
        $mortgageData['FourthMortgageBook']['type'] = 'varchar';
        $mortgageData['FourthMortgageBook']['value'] = $mortgages[3]['Book'];
        $mortgageData['FourthMortgagePage']['type'] = 'varchar';
        $mortgageData['FourthMortgagePage']['value'] = $mortgages[3]['Page'];
        $mortgageData['FourthMortgageAmount']['type'] = 'double';
        $mortgageData['FourthMortgageAmount']['value'] = $mortgages[3]['Amount'];
        $mortgageData['FourthMortgageDate']['type'] = 'date';
        if ($mortgages[3]['Date'] === '') {
            $mortgages[3]['Date'] = null;
        }
        if($mortgages[3]['Date'] !== null) {
            $mortgageData['FourthMortgageDate']['value'] = date_format(date_create($mortgages[3]['Date']),"Y-m-d H:i:s");
        }
        $mortgageData['FourthMortgagRelDate']['type'] = 'date';
        if ($mortgages[3]['RelDate'] === '') {
            $mortgages[3]['RelDate'] = null;
        }
        if($mortgages[3]['RelDate'] !== null) {
            $mortgageData['FourthMortgagRelDate']['value'] = date_format(date_create($mortgages[3]['RelDate']),"Y-m-d H:i:s");
        }
    } else {
        $mortgageData['FourthMortgageInfo']['type'] = 'varchar';
        $mortgageData['FourthMortgageInfo']['value'] = '';
        $mortgageData['FourthMortgageBook']['type'] = 'varchar';
        $mortgageData['FourthMortgageBook']['value'] = '';
        $mortgageData['FourthMortgagePage']['type'] = 'varchar';
        $mortgageData['FourthMortgagePage']['value'] = '';
        $mortgageData['FourthMortgageAmount']['type'] = 'double';
        $mortgageData['FourthMortgageAmount']['value'] = null;
        $mortgageData['FourthMortgageDate']['type'] = 'date';
        $mortgageData['FourthMortgageDate']['value'] = null;
        $mortgageData['FourthMortgagRelDate']['type'] = 'date';
        $mortgageData['FourthMortgagRelDate']['value'] = null;
    }

    // create SystemSunc record
    $systemSync = new \SystemSync();
    $systemSync->setSystem(SYSTEM_NAME);
    $systemSync->setApplication('site-manager');
    $systemSync->setPervasiveDatabase('Fischer Management');
    $systemSync->setPervasiveTable('CommunitySectionLega');
    $systemSync->setAction('update');
    $systemSync->setKeyFields('ComLegalSecRecordId');
    $systemSync->setData(json_encode($mortgageData));
    $systemSync->setCreatedDate('now');
    $systemSync->save();

    return $response->withJson($result);
})->add(new Validation($validateId));

$app->delete('/slimapi/v1/mortgages/{id}', function (Request $request, Response $response, $args) {
    $id = (int)$args['id'];

    $mortgage = \MortgageQuery::create()->findOneByMortgageId($id);

    if ($mortgage) {
        $mortgage->setIsDeleted(1);
        $mortgage->save();
    } else {
        return $response->withStatus(404);
    }

    $legalSectionId = $mortgage->getLegalSectionId();
    // get all mortgages for this legal_section
    $mortgages = \MortgageQuery::create()->filterByIsDeleted(0)->findByLegalsectionId($legalSectionId)->toArray();
    // create data json
    $mortgageData['ComLegalSecRecordId']['type'] = 'integer';
    $mortgageData['ComLegalSecRecordId']['value'] = $legalSectionId;
    if (isset($mortgages[0])) {
        $mortgageData['FirstMortgageInfo']['type'] = 'varchar';
        $mortgageData['FirstMortgageInfo']['value'] = $mortgages[0]['Info'];
        $mortgageData['FirstMortgageBook']['type'] = 'varchar';
        $mortgageData['FirstMortgageBook']['value'] = $mortgages[0]['Book'];
        $mortgageData['FirstMortgagePage']['type'] = 'varchar';
        $mortgageData['FirstMortgagePage']['value'] = $mortgages[0]['Page'];
        $mortgageData['FirstMortgageAmount']['type'] = 'double';
        $mortgageData['FirstMortgageAmount']['value'] = $mortgages[0]['Amount'];
        $mortgageData['FirstMortgageDate']['type'] = 'date';
        if ($mortgages[0]['Date'] === '') {
            $mortgages[0]['Date'] = null;
        }
        if($mortgages[0]['Date'] !== null) {
            $mortgageData['FirstMortgageDate']['value'] = date_format(date_create($mortgages[0]['Date']),"Y-m-d H:i:s");
        }
        $mortgageData['FirstMortgageRelDate']['type'] = 'date';
        if ($mortgages[0]['RelDate'] === '') {
            $mortgages[0]['RelDate'] = null;
        }
        if($mortgages[0]['RelDate'] !== null) {
            $mortgageData['FirstMortgageRelDate']['value'] = date_format(date_create($mortgages[0]['RelDate']),"Y-m-d H:i:s");
        }
    } else {
        $mortgageData['FirstMortgageInfo']['type'] = 'varchar';
        $mortgageData['FirstMortgageInfo']['value'] = '';
        $mortgageData['FirstMortgageBook']['type'] = 'varchar';
        $mortgageData['FirstMortgageBook']['value'] = '';
        $mortgageData['FirstMortgagePage']['type'] = 'varchar';
        $mortgageData['FirstMortgagePage']['value'] = '';
        $mortgageData['FirstMortgageAmount']['type'] = 'double';
        $mortgageData['FirstMortgageAmount']['value'] = '';
        $mortgageData['FirstMortgageDate']['type'] = 'date';
        $mortgageData['FirstMortgageDate']['value'] = null;
        $mortgageData['FirstMortgageRelDate']['type'] = 'date';
        $mortgageData['FirstMortgageRelDate']['value'] = null;
    }
    if (isset($mortgages[1])) {
        $mortgageData['SecondMortgageInfo']['type'] = 'varchar';
        $mortgageData['SecondMortgageInfo']['value'] = $mortgages[1]['Info'];
        $mortgageData['SecondMortgageBook']['type'] = 'varchar';
        $mortgageData['SecondMortgageBook']['value'] = $mortgages[1]['Book'];
        $mortgageData['SecondMortgagePage']['type'] = 'varchar';
        $mortgageData['SecondMortgagePage']['value'] = $mortgages[1]['Page'];
        $mortgageData['SecondMortgageAmount']['type'] = 'double';
        $mortgageData['SecondMortgageAmount']['value'] = $mortgages[1]['Amount'];
        $mortgageData['SecondMortgageDate']['type'] = 'date';
        if ($mortgages[1]['Date'] === '') {
            $mortgages[1]['Date'] = null;
        }
        if($mortgages[1]['Date'] !== null) {
            $mortgageData['SecondMortgageDate']['value'] = date_format(date_create($mortgages[1]['Date']),"Y-m-d H:i:s");
        }
        $mortgageData['SecondMortgagRelDate']['type'] = 'date';
        if ($mortgages[1]['RelDate'] === '') {
            $mortgages[1]['RelDate'] = null;
        }
        if($mortgages[1]['RelDate'] !== null) {
            $mortgageData['SecondMortgagRelDate']['value'] = date_format(date_create($mortgages[1]['RelDate']),"Y-m-d H:i:s");
        }
    } else {
        $mortgageData['SecondMortgageInfo']['type'] = 'varchar';
        $mortgageData['SecondMortgageInfo']['value'] = '';
        $mortgageData['SecondMortgageBook']['type'] = 'varchar';
        $mortgageData['SecondMortgageBook']['value'] = '';
        $mortgageData['SecondMortgagePage']['type'] = 'varchar';
        $mortgageData['SecondMortgagePage']['value'] = '';
        $mortgageData['SecondMortgageAmount']['type'] = 'double';
        $mortgageData['SecondMortgageAmount']['value'] = '';
        $mortgageData['SecondMortgageDate']['type'] = 'date';
        $mortgageData['SecondMortgageDate']['value'] = null;
        $mortgageData['SecondMortgagRelDate']['type'] = 'date';
        $mortgageData['SecondMortgagRelDate']['value'] = null;
    }
    if (isset($mortgages[2])) {
        $mortgageData['ThirdMortgageInfo']['type'] = 'varchar';
        $mortgageData['ThirdMortgageInfo']['value'] = $mortgages[2]['Info'];
        $mortgageData['ThirdMortgageBook']['type'] = 'varchar';
        $mortgageData['ThirdMortgageBook']['value'] = $mortgages[2]['Book'];
        $mortgageData['ThirdMortgagePage']['type'] = 'varchar';
        $mortgageData['ThirdMortgagePage']['value'] = $mortgages[2]['Page'];
        $mortgageData['ThirdMortgageAmount']['type'] = 'double';
        $mortgageData['ThirdMortgageAmount']['value'] = $mortgages[2]['Amount'];
        $mortgageData['ThirdMortgageDate']['type'] = 'date';
        if ($mortgages[2]['Date'] === '') {
            $mortgages[2]['Date'] = null;
        }
        if($mortgages[2]['Date'] !== null) {
            $mortgageData['ThirdMortgageDate']['value'] = date_format(date_create($mortgages[2]['Date']),"Y-m-d H:i:s");
        }
        $mortgageData['ThirdMortgageRelDate']['type'] = 'date';
        if ($mortgages[2]['RelDate'] === '') {
            $mortgages[2]['RelDate'] = null;
        }
        if($mortgages[2]['RelDate'] !== null) {
            $mortgageData['ThirdMortgageRelDate']['value'] = date_format(date_create($mortgages[2]['RelDate']),"Y-m-d H:i:s");
        }
    } else {
        $mortgageData['ThirdMortgageInfo']['type'] = 'varchar';
        $mortgageData['ThirdMortgageInfo']['value'] = '';
        $mortgageData['ThirdMortgageBook']['type'] = 'varchar';
        $mortgageData['ThirdMortgageBook']['value'] = '';
        $mortgageData['ThirdMortgagePage']['type'] = 'varchar';
        $mortgageData['ThirdMortgagePage']['value'] = '';
        $mortgageData['ThirdMortgageAmount']['type'] = 'double';
        $mortgageData['ThirdMortgageAmount']['value'] = '';
        $mortgageData['ThirdMortgageDate']['type'] = 'date';
        $mortgageData['ThirdMortgageDate']['value'] = null;
        $mortgageData['ThirdMortgageRelDate']['type'] = 'date';
        $mortgageData['ThirdMortgageRelDate']['value'] = null;
    }
    if (isset($mortgages[3])) {
        $mortgageData['FourthMortgageInfo']['type'] = 'varchar';
        $mortgageData['FourthMortgageInfo']['value'] = $mortgages[3]['Info'];
        $mortgageData['FourthMortgageBook']['type'] = 'varchar';
        $mortgageData['FourthMortgageBook']['value'] = $mortgages[3]['Book'];
        $mortgageData['FourthMortgagePage']['type'] = 'varchar';
        $mortgageData['FourthMortgagePage']['value'] = $mortgages[3]['Page'];
        $mortgageData['FourthMortgageAmount']['type'] = 'double';
        $mortgageData['FourthMortgageAmount']['value'] = $mortgages[3]['Amount'];
        $mortgageData['FourthMortgageDate']['type'] = 'date';
        if ($mortgages[3]['Date'] === '') {
            $mortgages[3]['Date'] = null;
        }
        if($mortgages[3]['Date'] !== null) {
            $mortgageData['FourthMortgageDate']['value'] = date_format(date_create($mortgages[3]['Date']),"Y-m-d H:i:s");
        }
        $mortgageData['FourthMortgagRelDate']['type'] = 'date';
        if ($mortgages[3]['RelDate'] === '') {
            $mortgages[3]['RelDate'] = null;
        }
        if($mortgages[3]['RelDate'] !== null) {
            $mortgageData['FourthMortgagRelDate']['value'] = date_format(date_create($mortgages[3]['RelDate']),"Y-m-d H:i:s");
        }
    } else {
        $mortgageData['FourthMortgageInfo']['type'] = 'varchar';
        $mortgageData['FourthMortgageInfo']['value'] = '';
        $mortgageData['FourthMortgageBook']['type'] = 'varchar';
        $mortgageData['FourthMortgageBook']['value'] = '';
        $mortgageData['FourthMortgagePage']['type'] = 'varchar';
        $mortgageData['FourthMortgagePage']['value'] = '';
        $mortgageData['FourthMortgageAmount']['type'] = 'double';
        $mortgageData['FourthMortgageAmount']['value'] = '';
        $mortgageData['FourthMortgageDate']['type'] = 'date';
        $mortgageData['FourthMortgageDate']['value'] = null;
        $mortgageData['FourthMortgagRelDate']['type'] = 'date';
        $mortgageData['FourthMortgagRelDate']['value'] = null;
    }

    // create SystemSunc record
    $systemSync = new \SystemSync();
    $systemSync->setSystem(SYSTEM_NAME);
    $systemSync->setApplication('site-manager');
    $systemSync->setPervasiveDatabase('Fischer Management');
    $systemSync->setPervasiveTable('CommunitySectionLega');
    $systemSync->setAction('update');
    $systemSync->setKeyFields('ComLegalSecRecordId');
    $systemSync->setData(json_encode($mortgageData));
    $systemSync->setCreatedDate('now');
    $systemSync->save();

    return $response->withStatus(204);
})->add(new Validation($validateId));

/*
 * Site Holds
 *
 */
// Good fields solution
// Maybe add foreach handlers (if fields not defined in the request)
$app->get('/slimapi/v1/siteHolds', function (Request $request, Response $response) use ($customConfiguration) {
    // Initialization
    $queryParams = $request->getQueryParams();
    $fields = [
        'SiteHold' => [],
        'SiteHoldReason' => []
        ];
    // Pagination
    if(isset($queryParams['page'])) {
        $customConfiguration['page'] = $queryParams['page'];
    }
    if(isset($queryParams['per_page'])) {
        $customConfiguration['perPage'] = $queryParams['per_page'];
    }
    // Order
    $orderBy = 'SiteNumber';
    if(isset($queryParams['orderBy'])) {
        $orderBy = $queryParams['orderBy'];
    }
    if(isset($queryParams['orderByAsc'])) {
        $customConfiguration['orderByAsc'] = $queryParams['orderByAsc'];
    }
    // Filters
    $filterSiteHold = [];
    if (isset($queryParams['filter']['SiteHold']) && is_array($queryParams['filter']['SiteHold'])) {
        $filterSiteHold = $queryParams['filter']['SiteHold'];
    }
    $filterSiteHoldReason = [];
    if (isset($queryParams['filter']['SiteHoldReason'])) {
        $filterSiteHoldReason = $queryParams['filter']['SiteHoldReason'];
    }
    $filterCommunity = [];
    if (isset($queryParams['filter']['Community'])) {
        $filterCommunity = $queryParams['filter']['Community'];
    }
    // Includes
    if(isset($queryParams['include'])) {
        $withArray = explode(',', $queryParams['include']);
        if(isset($withArray) && count($withArray) > 0) {
            foreach ($withArray as $with) {
                if(isset($queryParams['fields'][$with])) {
                    $fields[$with] = explode(',', $queryParams['fields'][$with]);
                }
                if ($with == 'SiteHoldReason') {
                    $withSiteHoldReason = true;
                } elseif ($with == 'Community') {
                    $withCommunity = true;
                }
            }
        }
    }
    $siteHolds = \CommunitySiteQuery::create()
            ->where('hold_reason_id is not null')
            ->addSiteHoldReason($withSiteHoldReason, $fields['SiteHoldReason'])
            ->addCommunity($withCommunity, $fields['Community'])
            ->addSiteHoldFilters($filterSiteHold)
            ->addCommunityFilters($filterCommunity)
            ->addSiteHoldReasonFilters($filterSiteHoldReason)
            ->orderBy($orderBy, $customConfiguration['orderByAsc'])
            ->paginate($customConfiguration['page'], $customConfiguration['perPage']);
    $fields['SiteHold'] = explode(',', $queryParams['fields']['SiteHold']);
    foreach ($siteHolds as $siteHold) {
        $tempSiteHolds = [];
        foreach ($fields as $entity => $entityFields) {
            foreach ($entityFields as $field) {
                if (array_key_exists($field, $siteHold->toArray())) {
                    $tempSiteHolds[$field] = $siteHold->toArray()[$field];
                } elseif ($siteHold->hasVirtualColumn($entity.$field)) {
                    $tempSiteHolds[$entity.$field] = $siteHold->getVirtualColumn($entity.$field);
                }
            }
        }
        $arrSiteHolds[] = $tempSiteHolds;
    }
    $data = [
        'page' => $siteHolds->getPage(),
        'rows' => $arrSiteHolds,
        'totalRecords' => $siteHolds->getNbResults(),
        'totalPages' => $siteHolds->getLastPage()
        ];

    return $response->withJson($data);
});

$app->get('/slimapi/v1/siteHolds/{id}', function (Request $request, Response $response, $args) {

    $queryParams = $request->getQueryParams();
    $id = $args['id'];

    if(isset($queryParams['include'])) {
        $withArray = explode(',', $queryParams['include']);
        if(isset($withArray) && count($withArray) > 0) {
            foreach ($withArray as $value) {
                if($value == 'SiteHoldReason') {
                    $withSiteHoldReason = 1;
                }
            }
        }
    }
    if(isset($queryParams['fields'])) {
        // FIXME add validation
        $fields = explode(',', $queryParams['fields']);
    } else {
        $siteHoldTable = \Map\CommunitySiteTableMap::getTableMap();
        foreach ($siteHoldTable->getColumns() as $column) {
            $fields[] = $column->getPhpName();
        }
    }
    $siteHold = \CommunitySiteQuery::create()
            ->select($fields)
            ->addSiteHoldReason($withSiteHoldReason)
            ->findPK($id);
    return $response->withJson($siteHold);
})->add(new Validation($validateId));

$app->post('/slimapi/v1/sites/{id}/siteHolds', function (Request $request, Response $response, $args) {
    $result = [];
    $siteData = [];
    $siteId = (int)$args['id'];
    $allPostPutVars = $request->getParsedBody();
    // Validate input
    if($request->getAttribute('has_errors')){
        $errors = $request->getAttribute('errors');
        $data = [];
        $data["status"] = "error";
        $data["message"] = $errors;
        return $response->withJson($data, 422);
    }
    foreach ($allPostPutVars as $row) {
        $site = \CommunitySiteQuery::create()->findPk($siteId);
        $siteTable = \Map\CommunitySiteTableMap::getTableMap();
        foreach ($row as $fieldName => $fieldValue) {
            if ($siteTable->hasColumnByPhpName($fieldName) ) {
                $siteColumn = $siteTable->getColumnByPhpName($fieldName);
                $site->setByName($fieldName, $fieldValue);
                // Pervasive sync
                $siteData[$siteColumn->getName()] = $fieldValue;
            }
        }

        $site->setHoldCreatedBy($request->getAttribute('userId'));
        $site->setHoldCreatedDate('now');
        $site->setHoldLastModifiedBy($user->getId());
        $site->setHoldLastModifiedDate('now');
        $site->save();

        // Must add SiteNumber to be able to update SiteHold
        $siteData['site_number'] = $site->getSiteNumber();
        $siteData['hold_created_by'] = $site->getHoldCreatedBy();
        $siteData['hold_created_date'] = $site->getHoldCreatedDate();
        $siteData['hold_last_modified_by'] = $site->getHoldLastModifiedBy();
        $siteData['hold_last_modified_date'] = $site->getHoldLastModifiedDate();

        $pervasiveMapSite = new SyncPervasive(SYSTEM_NAME, 'site-manager', 'Fischer Management', 'community_site', 'create');
        //        $pervasiveMapSite->run($siteData);

        $result[] = $site->toArray();
    }

    return $response->withJson($result);
})->add(new Validation($validateId));

$app->patch('/slimapi/v1/siteHolds', function (Request $request, Response $response) {
    $result = [];
    $allPostPutVars = $request->getParsedBody();

    foreach ($allPostPutVars as $row) {
        $site = \CommunitySiteQuery::create()->findPk($row['SiteId']);
        $siteTable = \Map\CommunitySiteTableMap::getTableMap();
        foreach ($row as $fieldName => $fieldValue) {
            if ($siteTable->hasColumnByPhpName($fieldName) ) {
                $siteColumn = $siteTable->getColumnByPhpName($fieldName);
                $site->setByName($fieldName, $fieldValue);
                // Pervasive sync - CommunitySiteNotes special parsing
                $siteData[$siteColumn->getName()] = $fieldValue;
            }
        }

        $site->setHoldLastModifiedBy($request->getAttribute('userId'));
        $site->setHoldLastModifiedDate('now');
        $site->save();

        // Must add SiteNumber to be able to update SiteHold
        $siteData['site_number'] = $site->getSiteNumber();
        $siteData['hold_last_modified_by'] = $site->getHoldLastModifiedBy();
        $siteData['hold_last_modified_date'] = $site->getHoldLastModifiedDate();

        $pervasiveMapSite = new SyncPervasive(SYSTEM_NAME, 'site-manager', 'Fischer Management', 'community_site', 'update');
        //  $pervasiveMapSite->run($siteData);

        $result[] = $site->toArray();
    }

    return $response->withJson($result);
});

$app->delete('/slimapi/v1/siteHolds/{id}', function (Request $request, Response $response, $args) {
    $id = (int)$args['id'];

    $siteHold = \CommunitySiteQuery::create()->findPk($id);
    if ($siteHold && $siteHold->getHoldReasonId()) {
        // Create Sitehold History
        $siteHoldHistory = new \SiteholdHistory();
        $siteHoldHistory->setSiteId($siteHold->getSiteId());
        $siteHoldHistory->setSiteholdReasonId($siteHold->getHoldReasonId());
        $siteHoldHistory->setVendorId($siteHold->getHoldVendorId());
        $siteHoldHistory->setHoldDate($siteHold->getHoldDate());
        $siteHoldHistory->setHoldControlCode($siteHold->getHoldControlCode());
        $siteHoldHistory->setHoldDeposit($siteHold->getHoldDeposit());
        $siteHoldHistory->setHoldNotes($siteHold->getHoldNotes());
        $siteHoldHistory->setCreatedBy($siteHold->getHoldCreatedBy());
        $siteHoldHistory->setCreatedDate($siteHold->getHoldCreatedDate());
        $siteHoldHistory->setLastModifiedBy($siteHold->getHoldLastModifiedBy());
        $siteHoldHistory->setLastModifiedDate($siteHold->getHoldLastModifiedDate());
        $siteHoldHistory->setArchivedDate($headers['HTTP_UID'][0]);

        if ($siteHoldHistory->save()) {
            // Clear out Sitehold columns
            $siteHold->setHoldReasonId(null);
            $siteHold->setHoldVendorId(null);
            $siteHold->setHoldDate(null);
            $siteHold->setHoldControlCode(null);
            $siteHold->setHoldDeposit(null);
            $siteHold->setHoldNotes(null);
            $siteHold->setHoldCreatedBy(null);
            $siteHold->setHoldCreatedDate(null);
            $siteHold->setHoldLastModifiedBy(null);
            $siteHold->setHoldLastModifiedDate(null);
            $siteHold->save();
        }
    } else {
        return $response->withStatus(404);
    }

    // FIXME Find current user_id
    $headers = $request->getHeaders();

    try {
        $siteHoldData['site_id'] = $id;
        $siteHoldData['site_number'] = $siteHold->getSiteNumber();
        $pervasiveMapSite = new SyncPervasive(SYSTEM_NAME, 'site-manager', 'Fischer Management', 'community_site', 'delete');
        // $pervasiveMapSite->run($siteHoldData);
    } catch (Exception $e) {
        // TBD log failure
        // echo $e->getTraceAsString();
    }

    return $response->withStatus(204);
})->add(new Validation($validateId));

// Site Hold Reasons
$app->get('/slimapi/v1/siteHoldReasons', function (Request $request, Response $response) use ($customConfiguration) {
    // Initialization
    $queryParams = $request->getQueryParams();
    $fields = [
        'SiteHoldReason' => []
        ];
    // Pagination
    if(isset($queryParams['page'])) {
        $customConfiguration['page'] = $queryParams['page'];
    }
    if(isset($queryParams['per_page'])) {
        $customConfiguration['perPage'] = $queryParams['per_page'];
    }
    // Order
    $orderBy = 'Description';
    if(isset($queryParams['orderBy'])) {
        $orderBy = $queryParams['orderBy'];
    }
    if(isset($queryParams['orderByAsc'])) {
        $customConfiguration['orderByAsc'] = $queryParams['orderByAsc'];
    }
    // Filters
    $filterSiteHoldReason = [];
    if (isset($queryParams['filter']['SiteHoldReason'])) {
        $filterSiteHoldReason = $queryParams['filter']['SiteHoldReason'];
    }
    $filterSiteHold = [];
    if (isset($queryParams['filter']['SiteHold'])) {
        $filterSiteHold = $queryParams['filter']['SiteHold'];
    }
    // Includes
    if(isset($queryParams['include'])) {
        $withArray = explode(',', $queryParams['include']);
        if(isset($withArray) && count($withArray) > 0) {
            foreach ($withArray as $with) {
                if(isset($queryParams['fields'][$with])) {
                    $fields[$with] = explode(',', $queryParams['fields'][$with]);
                }
                if ($with == 'SiteHold') {
                    $withSiteHold = true;
                }
            }
        }
    }
    $siteHoldReasons = \SiteholdReasonQuery::create()
    //      ->addSiteHold($withSiteHold, $fields['SiteHold'])
    //      ->addSiteHoldFilters($filterSiteHold)
            ->addSiteHoldReasonFilters($filterSiteHoldReason)
            ->orderBy($orderBy, $customConfiguration['orderByAsc'])
            ->paginate($customConfiguration['page'], $customConfiguration['perPage']);
    // FIXME fields for primary entity
    // $fields['Site'] = explode(',', $queryParams['fields']['Site']);
    foreach ($siteHoldReasons as $siteHoldReason) {
        $tempSiteHoldReason = $siteHoldReason->toArray();
        $arrSiteHoldReasons[] = $tempSiteHoldReason;
    }
    $data = [
        'page' => $siteHoldReasons->getPage(),
        'rows' => $arrSiteHoldReasons,
        'totalRecords' => $siteHoldReasons->getNbResults(),
        'totalPages' => $siteHoldReasons->getLastPage()
        ];

    return $response->withJson($data);
});

// Vendors
$app->get('/slimapi/v1/vendors', function (Request $request, Response $response) use ($customConfiguration) {
    // Initialization
    $queryParams = $request->getQueryParams();
    $fields = [
        'Vendor' => []
        ];
    // Pagination
    if(isset($queryParams['page'])) {
        $customConfiguration['page'] = $queryParams['page'];
    }
    if(isset($queryParams['per_page'])) {
        $customConfiguration['perPage'] = $queryParams['per_page'];
    }
    // Order
    $orderBy = 'VendorId';
    if(isset($queryParams['orderBy'])) {
        $orderBy = $queryParams['orderBy'];
    }
    if(isset($queryParams['orderByAsc'])) {
        $customConfiguration['orderByAsc'] = $queryParams['orderByAsc'];
    }
    // Filters
    $filterVendor = [];
    if (isset($queryParams['filter']['Vendor']) && is_array($queryParams['filter']['Vendor'])) {
        $filterVendor = $queryParams['filter']['Vendor'];
    }

    $vendors = \VendorQuery::create()
            ->addVendorFilters($filterVendor)
            ->orderBy($orderBy, $customConfiguration['orderByAsc'])
            ->paginate($customConfiguration['page'], $customConfiguration['perPage']);

    // Fields
    // TODO Needs the same for related entities
    if (isset($queryParams['fields']['Vendor'])) {
        $fields['Vendor'] = explode(',', $queryParams['fields']['Vendor']);
    } else {
        $vendorTable = \Map\VendorTableMap::getTableMap();
        foreach ($vendorTable->getColumns() as $key => $column) {
            $fields['Vendor'][] = $column->getPhpName();
        }
    }
    foreach ($vendors as $vendor) {
        $tempVendors = [];
        foreach ($fields as $entity => $entityFields) {
            foreach ($entityFields as $field) {
                if (array_key_exists($field, $vendor->toArray())) {
                    $tempVendors[$field] = $vendor->toArray()[$field];
                } elseif ($vendor->hasVirtualColumn($entity.$field)) {
                    $tempVendors[$entity.$field] = $vendor->getVirtualColumn($entity.$field);
                }
            }
        }
        $arrVendors[] = $tempVendors;
    }
    $data = [
        'page' => $vendors->getPage(),
        'rows' => $arrVendors,
        'totalRecords' => $vendors->getNbResults(),
        'totalPages' => $vendors->getLastPage()
        ];

    return $response->withJson($data);
});

// System Sync
$app->get('/slimapi/v1/systemSyncs', function (Request $request, Response $response) use ($customConfiguration) {

    $arrSystemSyncs = [];
    $queryParams = $request->getQueryParams();
    $fields = [
        'SystemSync' => [],
        ];

    if(isset($queryParams['page'])) {
        $customConfiguration['page'] = $queryParams['page'];
    }
    if(isset($queryParams['per_page'])) {
        $customConfiguration['perPage'] = $queryParams['per_page'];
    }
    $orderBy = 'Id';
    if(isset($queryParams['orderBy'])) {
        $orderBy = $queryParams['orderBy'];
    }
    if(isset($queryParams['orderByAsc'])) {
        $customConfiguration['orderByAsc'] = $queryParams['orderByAsc'];
    }

    // Filters
    $filterSystemSync = [];
    if (isset($queryParams['filter']['SystemSync'])) {
        $filterSystemSync = $queryParams['filter']['SystemSync'];
    }
    // Fields
    if(isset($queryParams['fields']['SystemSync'])) {
        $fields['SystemSync'] = explode(',', $queryParams['fields']['SystemSync']);
    } else {
        $fields['SystemSync'] = ['Id', 'System', 'Application', 'PervaisveDatabase', 'PervasiveTable', 'Action', 'KeyFields', 'Data', 'CreatedDate', 'IsProcessed', 'IsErrored'];
    }

    $systemSyncs = \SystemSyncQuery::create()
            ->addSystemSyncFilters($filterSystemSync)
            ->orderBy($orderBy, $customConfiguration['orderByAsc'])
            ->paginate($customConfiguration['page'], $customConfiguration['perPage']);
    foreach ($systemSyncs as $systemSync) {
        $tempSystemSyncs = [];
        foreach ($fields as $entity => $entityFields) {
            foreach ($entityFields as $field) {
                if (array_key_exists($field, $systemSync->toArray())) {
                    $tempSystemSyncs[$field] = $systemSync->toArray()[$field];
                } elseif ($systemSync->hasVirtualColumn($entity.$field)) {
                    $tempSystemSyncs[$entity.$field] = $systemSync->getVirtualColumn($entity.$field);
                }
            }
        }
        $arrSystemSyncs[] = $tempSystemSyncs;
    }

    $data = [
        'page' => $systemSyncs->getPage(),
        'rows' => $arrSystemSyncs,
        'totalRecords' => $systemSyncs->getNbResults(),
        'totalPages' => $systemSyncs->getLastPage()
        ];

    return $response->withJson($data);

});

/*
 * Pricing Groups
 *
 */
$app->get('/slimapi/v1/pricingGroups', function (Request $request, Response $response) use ($customConfiguration) {
    // Initialization
    $withArray = [];
    $queryParams = $request->getQueryParams();

    $fields = [
        'PricingGroup' => [],
        'Site' => []
    ];
    // Pagination
    if(isset($queryParams['page'])) {
        $customConfiguration['page'] = $queryParams['page'];
    }
    if(isset($queryParams['per_page'])) {
        $customConfiguration['perPage'] = $queryParams['per_page'];
    }
    // Order
    $orderBy = 'PricingGroupId';
    if(isset($queryParams['orderBy'])) {
        $orderBy = $queryParams['orderBy'];
    }
    if(isset($queryParams['orderByAsc'])) {
        $customConfiguration['orderByAsc'] = $queryParams['orderByAsc'];
    }
    // Filters
    $filterPricingGroup = [];
    if (isset($queryParams['filter']['PricingGroup']) && is_array($queryParams['filter']['PricingGroup'])) {
        $filterPricingGroup = $queryParams['filter']['PricingGroup'];
    }
    // Includes
    if(isset($queryParams['include'])) {
        $withArray = explode(',', $queryParams['include']);
        if(isset($withArray) && count($withArray) > 0) {
            foreach ($withArray as $with) {
                if(isset($queryParams['fields'][$with])) {
                    $fields[$with] = explode(',', $queryParams['fields'][$with]);
                }
            }
        }
    }
    $pricingGroups = \PricingGroupQuery::create()
            ->addPricingGroupFilters($filterPricingGroup)
            ->filterByIsDeleted($customConfiguration['isDeletedFilter'])
            ->orderBy($orderBy, $customConfiguration['orderByAsc'])
            ->paginate($customConfiguration['page'], $customConfiguration['perPage']);
    // Fields
    if(isset($queryParams['fields']['PricingGroup'])) {
        $fields['PricingGroup'] = explode(',', $queryParams['fields']);
    } else {
        $fields['PricingGroup'] = ['PricingGroupId', 'Name', 'Description'];
    }
    foreach ($pricingGroups as $pricingGroup) {
        $tempPricingGroup = [];
        foreach ($fields as $entity => $entityFields) {
            foreach ($entityFields as $field) {
                if (array_key_exists($field, $pricingGroup->toArray())) {
                    $tempPricingGroup[$field] = $pricingGroup->toArray()[$field];
                } elseif ($pricingGroup->hasVirtualColumn($entity.$field)) {
                    $tempPricingGroup[$entity.$field] = $pricingGroup->getVirtualColumn($entity.$field);
                }
            }
        }
        if (in_array('Site', $withArray)) {
            $tempPricingGroup['Site'] = $pricingGroup->loadCommunitySites();
        }
        $arrPricingGroups[] = $tempPricingGroup;
    }
    $data = [
        'page' => $pricingGroups->getPage(),
        'rows' => $arrPricingGroups,
        'totalRecords' => $pricingGroups->getNbResults(),
        'totalPages' => $pricingGroups->getLastPage()
        ];

    return $response->withJson($data);
});

$app->get('/slimapi/v1/pricingGroups/{id}', function (Request $request, Response $response, $args) {

    $queryParams = $request->getQueryParams();
    $id = $args['id'];

    $pricingGroup = \PricingGroupQuery::create()->findPk($id);
    $arrPricingGroup = $pricingGroup->toArray();
    if(isset($queryParams['include'])) {
        $withArray = explode(',', $queryParams['include']);
        if(isset($withArray) && count($withArray) > 0) {
            foreach ($withArray as $value) {
                if($value == 'CommunitySite') {
                    $arrPricingGroup['CommunitySites'] = $pricingGroup->loadSites();
                }
            }
        }
    }

    return $response->withJson($arrPricingGroup);
})->add(new Validation($validateId));

$app->post('/slimapi/v1/pricingGroups', function (Request $request, Response $response, $args) {
    $result = [];
    $allPostPutVars = $request->getParsedBody();

    foreach ($allPostPutVars as $row) {
        $pricingGroup = new \PricingGroup();
        $pricingGroupTable = \Map\PricingGroupTableMap::getTableMap();
        foreach ($row as $fieldName => $fieldValue) {
            if ($pricingGroupTable->hasColumnByPhpName($fieldName) ) {
                $pricingGroup->setByName($fieldName, $fieldValue);
            }
        }

        $pricingGroup->setCreatedBy($request->getAttribute('userId'));
        $pricingGroup->setCreatedDate('now');
        $pricingGroup->setLastModifiedBy($user->getId());
        $pricingGroup->setLastModifiedDate('now');
        $pricingGroup->save();

        // Pervasive sync here.
        $pricingGroupData['pricing_group_id'] = $pricingGroup->getPricingGroupId();
        $pricingGroupData['pricing_group_name'] = $pricingGroup->getName();
        $pricingGroupData['pricing_group_description'] = $pricingGroup->getDescription();
        $pricingGroupData['pricing_group_created_date'] = $pricingGroup->getCreatedDate();
        $pricingGroupData['pricing_group_last_modified_date'] = $pricingGroup->getLastModifiedDate();

        $pervasiveMapSite = new SyncPervasive(SYSTEM_NAME, 'site-manager', 'Fischer Management', 'pricing_group', 'create');
        $pervasiveMapSite->run($pricingGroupData);

        $result[] = $pricingGroup->toArray();
    }

    return $response->withJson($result);
});

$app->patch('/slimapi/v1/pricingGroups', function (Request $request, Response $response) {
    $result = [];
    $allPostPutVars = $request->getParsedBody();

    foreach ($allPostPutVars as $row) {
        $pricingGroup = \PricingGroupQuery::create()->findPk($row['PricingGroupId']);
        $pricingGroupTable = \Map\PricingGroupTableMap::getTableMap();
        foreach ($row as $fieldName => $fieldValue) {
            if ($pricingGroupTable->hasColumnByPhpName($fieldName) ) {
                $pricingGroup->setByName($fieldName, $fieldValue);
            }
        }

        $pricingGroup->setLastModifiedBy($request->getAttribute('userId'));
        $pricingGroup->setLastModifiedDate('now');
        $pricingGroup->save();

        // Pervasive sync here.
        $pricingGroupData['pricing_group_id'] = $pricingGroup->getPricingGroupId();
        $pricingGroupData['pricing_group_name'] = $pricingGroup->getName();
        $pricingGroupData['pricing_group_description'] = $pricingGroup->getDescription();
        $pricingGroupData['pricing_group_last_modified_date'] = $pricingGroup->getLastModifiedDate();

        $pervasiveMapSite = new SyncPervasive(SYSTEM_NAME, 'site-manager', 'Fischer Management', 'pricing_group', 'update');
        $pervasiveMapSite->run($pricingGroupData);

        $result[] = $pricingGroup->toArray();
    }

    return $response->withJson($result);
});

$app->delete('/slimapi/v1/pricingGroups/{id}', function (Request $request, Response $response, $args) {
    $id = (int)$args['id'];

    $pricingGroup = \PricingGroupQuery::create()->findOneByPricingGroupId($id);

    if ($pricingGroup) {

        $pricingGroup->setLastModifiedBy($request->getAttribute('userId'));
        $pricingGroup->setLastModifiedDate('now');
        $pricingGroup->setIsDeleted(1);
        $pricingGroup->save();

        // Pervasive sync here.
        $pricingGroupData['pricing_group_id'] = $pricingGroup->getPricingGroupId();
        $pricingGroupData['pricing_group_is_deleted'] = 1;

        $pervasiveMapSite = new SyncPervasive(SYSTEM_NAME, 'site-manager', 'Fischer Management', 'pricing_group', 'update');
        $pervasiveMapSite->run($pricingGroupData);
    } else {
        return $response->withStatus(404);
    }

    return $response->withStatus(204);
})->add(new Validation($validateId));

/*
 * Contracts
 *
 */

$app->get('/slimapi/v1/contracts', function (Request $request, Response $response) use ($customConfiguration) {
    // Initialization
    $queryParams = $request->getQueryParams();
    $fields = [
        'Escalator' => [],
        'Site' => []
        ];
    // Pagination
    if(isset($queryParams['page'])) {
        $customConfiguration['page'] = $queryParams['page'];
    }
    if(isset($queryParams['per_page'])) {
        $customConfiguration['perPage'] = $queryParams['per_page'];
    }
    // Order
    $orderBy = 'ContractId';
    if(isset($queryParams['orderBy'])) {
        $orderBy = $queryParams['orderBy'];
    }
    if(isset($queryParams['orderByAsc'])) {
        $customConfiguration['orderByAsc'] = $queryParams['orderByAsc'];
    }
    // Filter by primary entity
    $filterContract = [];
    if (isset($queryParams['filter']['Contract']) && is_array($queryParams['filter']['Contract'])) {
        $filterContract = $queryParams['filter']['Contract'];
    }
    // Include - Related entities and fields
    if(isset($queryParams['include'])) {
        $withArray = explode(',', $queryParams['include']);
        if(isset($withArray) && count($withArray) > 0) {
            foreach ($withArray as $with) {
                if(isset($queryParams['fields'][$with])) {
                    $fields[$with] = explode(',', $queryParams['fields'][$with]);
                } elseif($with == 'Site' ) {
                    $siteTable = \Map\CommunitySiteTableMap::getTableMap();
                    foreach ($siteTable->getColumns() as $column) {
                        $fields['Site'][] = $column->getPhpName();
                    }
                } elseif($with == 'Escalator' ) {
                    $escalatorTable = \Map\EscalatorTableMap::getTableMap();
                    foreach ($escalatorTable->getColumns() as $column) {
                        $fields['Escalator'][] = $column->getPhpName();
                    }
                }
            }
        }
    }
    // Primary entity fields
    if(isset($queryParams['fields']['Contract'])) {
        $fields['Contract'] = explode(',', $queryParams['fields']['Contract']);
    } else {
        $contractTable = \Map\ContractTableMap::getTableMap();
        foreach ($contractTable->getColumns() as $column) {
            $fields['Contract'][] = $column->getPhpName();
        }
    }
    // Get primary entity
    $contracts = \ContractQuery::create()
            ->addContractFilters($filterContract)
            ->filterByIsDeleted($customConfiguration['isDeletedFilter'])
            ->orderBy($orderBy, $customConfiguration['orderByAsc'])
            ->paginate($customConfiguration['page'], $customConfiguration['perPage']);

    // Prepare response record set
    foreach ($contracts as $contract) {
        $tempContract = [];
        foreach ($fields as $entity => $entityFields) {
            foreach ($entityFields as $field) {
                if (array_key_exists($field, $contract->toArray())) {
                    $tempContract[$field] = $contract->toArray()[$field];
                } elseif ($contract->hasVirtualColumn($entity.$field)) {
                    $tempContract[$entity.$field] = $contract->getVirtualColumn($entity.$field);
                }
            }
        }
        if (in_array('Site', $withArray)) {
            $tempContract['Site'] = $contract->loadSites($fields['Site']);
        }
        if (in_array('Escalator', $withArray)) {
            $tempContract['Escalator'] = $contract->loadEscalators($fields['Escalator']);
        }
        $arrContracts[] = $tempContract;
    }
    $data = [
        'page' => $contracts->getPage(),
        'rows' => $arrContracts,
        'totalRecords' => $contracts->getNbResults(),
        'totalPages' => $contracts->getLastPage()
        ];

    return $response->withJson($data);
});

$app->get('/slimapi/v1/contracts/{id}', function (Request $request, Response $response, $args) {
    // Initialization
    $queryParams = $request->getQueryParams();
    $id = $args['id'];
    $fields = [
        'Contract' => [],
        'Escalator' => [],
        'Site' => []
        ];
    // Include - Related entities and fields
    if(isset($queryParams['include'])) {
        $withArray = explode(',', $queryParams['include']);
        if(isset($withArray) && count($withArray) > 0) {
            foreach ($withArray as $with) {
                if(isset($queryParams['fields'][$with])) {
                    $fields[$with] = explode(',', $queryParams['fields'][$with]);
                } elseif($with == 'Site' ) {
                    $siteTable = \Map\CommunitySiteTableMap::getTableMap();
                    foreach ($siteTable->getColumns() as $column) {
                        $fields['Site'][] = $column->getPhpName();
                    }
                } elseif($with == 'Escalator' ) {
                    $escalatorTable = \Map\EscalatorTableMap::getTableMap();
                    foreach ($escalatorTable->getColumns() as $column) {
                        $fields['Escalator'][] = $column->getPhpName();
                    }
                }
            }
        }
    }
    // Primary entity fields
    if(isset($queryParams['fields']['Contract'])) {
        $fields['Contract'] = explode(',', $queryParams['fields']['Contract']);
    } else {
        $contractTable = \Map\ContractTableMap::getTableMap();
        foreach ($contractTable->getColumns() as $column) {
            $fields['Contract'][] = $column->getPhpName();
        }
    }
    // Get primary entity
    $arrContract = \ContractQuery::create()
            ->select($fields['Contract'])
            ->findPk($id);

    // Load related data (1-N)
    if(isset($queryParams['include'])) {
        $withArray = explode(',', $queryParams['include']);
        if(isset($withArray) && count($withArray) > 0) {
            $contract = \ContractQuery::create()->findPk($id);
            foreach ($withArray as $value) {
                if($value == 'Site') {
                    $arrContract['Site'] = $contract->loadSites($fields['Site']);
                } elseif($value == 'Escalator') {
                    $arrContract['Escalator'] = $contract->loadEscalators($fields['Escalator']);
                }
            }
        }
    }

    return $response->withJson($arrContract);
})->add(new Validation($validateId));

$app->post('/slimapi/v1/contracts', function (Request $request, Response $response, $args) {
    $result = [];
    $allPostPutVars = $request->getParsedBody();

    foreach ($allPostPutVars as $row) {
        $contract = new \Contract();
        $contractTable = \Map\ContractTableMap::getTableMap();
        foreach ($row as $fieldName => $fieldValue) {
            if ($contractTable->hasColumnByPhpName($fieldName) ) {
                $contract->setByName($fieldName, $fieldValue);
            }
        }

        $contract->setCreatedBy($request->getAttribute('userId'));
        $contract->setCreatedDate('now');
        $contract->setLastModifiedBy($user->getId());
        $contract->setLastModifiedDate('now');
        $contract->save();

        $result[] = $contract->toArray();
    }

    return $response->withJson($result);
});

$app->patch('/slimapi/v1/contracts', function (Request $request, Response $response) {
    $result = [];
    $allPostPutVars = $request->getParsedBody();

    foreach ($allPostPutVars as $row) {
        $contract = \ContractQuery::create()->findPk($row['ContractId']);
        $contractTable = \Map\ContractTableMap::getTableMap();
        foreach ($row as $fieldName => $fieldValue) {
            if ($contractTable->hasColumnByPhpName($fieldName) ) {
                $contract->setByName($fieldName, $fieldValue);
            }
        }

        $contract->setLastModifiedBy($request->getAttribute('userId'));
        $contract->setLastModifiedDate('now');
        $contract->save();

        $result[] = $contract->toArray();
    }

    return $response->withJson($result);
});

$app->delete('/slimapi/v1/contracts/{id}', function (Request $request, Response $response, $args) {
    $id = (int)$args['id'];

    $contract = \ContractQuery::create()->findOneByContractId($id);

    if ($contract) {

        $contract->setLastModifiedBy($request->getAttribute('userId'));
        $contract->setLastModifiedDate('now');
        $contract->setIsDeleted(1);
        $contract->save();
    } else {
        return $response->withStatus(404);
    }

    return $response->withStatus(204);
})->add(new Validation($validateId));

/*
 * Escalators
 *
 */
$app->get('/slimapi/v1/escalators', function (Request $request, Response $response) use ($customConfiguration) {
    // Initialization
    $queryParams = $request->getQueryParams();
    $fields = [
        'Contract' => [],
        'Escalator' => [],
        'EscalatorCondition' => []
        ];
    // Pagination
    if(isset($queryParams['page'])) {
        $customConfiguration['page'] = $queryParams['page'];
    }
    if(isset($queryParams['per_page'])) {
        $customConfiguration['perPage'] = $queryParams['per_page'];
    }
    // Order
    $orderBy = 'EscalatorId';
    if(isset($queryParams['orderBy'])) {
        $orderBy = $queryParams['orderBy'];
    }
    if(isset($queryParams['orderByAsc'])) {
        $customConfiguration['orderByAsc'] = $queryParams['orderByAsc'];
    }
    // Filter by primary entity
    $filterEscalator = [];
    if (isset($queryParams['filter']['Escalator']) && is_array($queryParams['filter']['Escalator'])) {
        $filterEscalator = $queryParams['filter']['Escalator'];
    }
    $filterContract = [];
    if (isset($queryParams['filter']['Contract'])) {
        $filterContract = $queryParams['filter']['Contract'];
    }
    // Include - Related entities and fields
    if(isset($queryParams['include'])) {
        $withArray = explode(',', $queryParams['include']);
        if(isset($withArray) && count($withArray) > 0) {
            foreach ($withArray as $with) {
                if(isset($queryParams['fields'][$with])) {
                    $fields[$with] = explode(',', $queryParams['fields'][$with]);
                } elseif($with == 'Contract' ) {
                    $contractTable = \Map\ContractTableMap::getTableMap();
                    foreach ($contractTable->getColumns() as $column) {
                        $fields['Contract'][] = $column->getPhpName();
                    }
                } elseif($with == 'EscalatorCondition' ) {
                    $escalatorConditionTable = \Map\EscalatorConditionTableMap::getTableMap();
                    foreach ($escalatorConditionTable->getColumns() as $column) {
                        $fields['EscalatorCondition'][] = $column->getPhpName();
                    }
                }

                if($with == 'Contract') {
                    $withContract = 1;
                }
            }
        }
    }
    // Primary entity fields
    if(isset($queryParams['fields']['Escalator'])) {
        $fields['Escalator'] = explode(',', $queryParams['fields']['Escalator']);
    } else {
        $escalatorTable = \Map\EscalatorTableMap::getTableMap();
        foreach ($escalatorTable->getColumns() as $column) {
            $fields['Escalator'][] = $column->getPhpName();
        }
    }
    // Get primary entity
    $escalators = \EscalatorQuery::create()
            ->addEscalatorFilters($filterEscalator)
            ->addContract($withContract, $fields['Contract'])
            ->addContractFilters($filterContract)
            ->filterByIsDeleted($customConfiguration['isDeletedFilter'])
            ->orderBy($orderBy, $customConfiguration['orderByAsc'])
            ->paginate($customConfiguration['page'], $customConfiguration['perPage']);
    // Prepare response record set
    // FIXME Add fields support
    foreach ($escalators as $escalator) {
        //$tempEscalator = [];
        //foreach ($fields as $entity => $entityFields) {
        //    foreach ($entityFields as $field) {
        //        if (array_key_exists($field, $escalator->toArray())) {
        //            $tempEscalator[$field] = $escalator->toArray()[$field];
        //        } elseif ($escalator->hasVirtualColumn($entity.$field)) {
        //            $tempEscalator[$entity.$field] = $escalator->getVirtualColumn($entity.$field);
        //        }
        //    }
        //}
        $tempEscalator = $escalator->toArray();
        if (in_array('EscalatorCondition', $withArray)) {
            $tempEscalator['EscalatorCondition'] = $escalator->loadEscalatorConditions($fields['EscalatorCondition']);
        }
        $arrEscalators[] = $tempEscalator;
    }
    $data = [
        'page' => $escalators->getPage(),
        'rows' => $arrEscalators,
        'totalRecords' => $escalators->getNbResults(),
        'totalPages' => $escalators->getLastPage()
        ];

    return $response->withJson($data);
});

$app->get('/slimapi/v1/escalators/{id}', function (Request $request, Response $response, $args) {
    // Initialization
    $queryParams = $request->getQueryParams();
    $id = $args['id'];
    $fields = [
        'Contract' => [],
        'Escalator' => [],
        'EscalatorCondition' => []
        ];
    // Include - Related entities and fields
    if(isset($queryParams['include'])) {
        $withArray = explode(',', $queryParams['include']);
        if(isset($withArray) && count($withArray) > 0) {
            foreach ($withArray as $with) {
                if(isset($queryParams['fields'][$with])) {
                    $fields[$with] = explode(',', $queryParams['fields'][$with]);
                } elseif($with == 'Contract' ) {
                    $contractTable = \Map\ContractTableMap::getTableMap();
                    foreach ($contractTable->getColumns() as $column) {
                        $fields['Contract'][] = $column->getPhpName();
                    }
                } elseif($with == 'EscalatorCondition' ) {
                    $escalatorConditionTable = \Map\EscalatorConditionTableMap::getTableMap();
                    foreach ($escalatorConditionTable->getColumns() as $column) {
                        $fields['EscalatorCondition'][] = $column->getPhpName();
                    }
                }

                if($with == 'Contract') {
                    $withContract = 1;
                }
            }
        }
    }
    // Primary entity fields
    if(isset($queryParams['fields']['Escalator'])) {
        $fields['Escalator'] = explode(',', $queryParams['fields']['Escalator']);
    } else {
        $escalatorTable = \Map\EscalatorTableMap::getTableMap();
        foreach ($escalatorTable->getColumns() as $column) {
            $fields['Escalator'][] = $column->getPhpName();
        }
    }
    $arrEscalator = \EscalatorQuery::create()
            ->select($fields['Escalator'])
            ->addContract($withContract, $fields['Contract'])
            ->findPk($id);

    // Load related data (1-N)
    if(isset($queryParams['include'])) {
        $withArray = explode(',', $queryParams['include']);
        if(isset($withArray) && count($withArray) > 0) {
            $escalator = \EscalatorQuery::create()->findPk($id);
            foreach ($withArray as $value) {
                if($value == 'EscalatorCondition') {
                    $arrEscalator['EscalatorCondition'] = $escalator->loadEscalatorConditions($fields['EscalatorCondition']);
                }
            }
        }
    }

    return $response->withJson($arrEscalator);
})->add(new Validation($validateId));

$app->post('/slimapi/v1/escalators', function (Request $request, Response $response, $args) {
    $result = [];
    $allPostPutVars = $request->getParsedBody();

    foreach ($allPostPutVars as $row) {
        $escalator = new \Escalator();
        $escalatorTable = \Map\EscalatorTableMap::getTableMap();
        foreach ($row as $fieldName => $fieldValue) {
            if ($escalatorTable->hasColumnByPhpName($fieldName) ) {
                $escalator->setByName($fieldName, $fieldValue);
            }
        }

        $escalator->setCreatedBy($request->getAttribute('userId'));
        $escalator->setCreatedDate('now');
        $escalator->setLastModifiedBy($user->getId());
        $escalator->setLastModifiedDate('now');
        $escalator->save();

        $result[] = $escalator->toArray();
    }

    return $response->withJson($result);
});

$app->patch('/slimapi/v1/escalators', function (Request $request, Response $response) {
    $result = [];
    $allPostPutVars = $request->getParsedBody();

    foreach ($allPostPutVars as $row) {
        $escalator = \EscalatorQuery::create()->findPk($row['EscalatorId']);
        $escalatorTable = \Map\EscalatorTableMap::getTableMap();
        foreach ($row as $fieldName => $fieldValue) {
            if ($escalatorTable->hasColumnByPhpName($fieldName) ) {
                $escalator->setByName($fieldName, $fieldValue);
            }
        }

        $escalator->setLastModifiedBy($request->getAttribute('userId'));
        $escalator->setLastModifiedDate('now');
        $escalator->save();

        $result[] = $escalator->toArray();
    }

    return $response->withJson($result);
});

$app->delete('/slimapi/v1/escalators/{id}', function (Request $request, Response $response, $args) {
    $id = (int)$args['id'];

    $escalator = \EscalatorQuery::create()->findOneByEscalatorId($id);

    if ($escalator) {

        $escalator->setLastModifiedBy($request->getAttribute('userId'));
        $escalator->setLastModifiedDate('now');
        $escalator->setIsDeleted(1);
        $escalator->save();
    } else {
        return $response->withStatus(404);
    }

    return $response->withStatus(204);
})->add(new Validation($validateId));

/*
 * Escalator Conditions
 *
 */

$app->get('/slimapi/v1/escalatorConditions', function (Request $request, Response $response) use ($customConfiguration) {
    // Initialization
    $queryParams = $request->getQueryParams();
    $fields = [
        'EscalatorCondition' => [],
        'Escalator' => []
        ];
    // Pagination
    if(isset($queryParams['page'])) {
        $customConfiguration['page'] = $queryParams['page'];
    }
    if(isset($queryParams['per_page'])) {
        $customConfiguration['perPage'] = $queryParams['per_page'];
    }
    // Order
    $orderBy = 'EscalatorConditionId';
    if(isset($queryParams['orderBy'])) {
        $orderBy = $queryParams['orderBy'];
    }
    if(isset($queryParams['orderByAsc'])) {
        $customConfiguration['orderByAsc'] = $queryParams['orderByAsc'];
    }
    // Filters
    $filterEscalatorCondition = [];
    if (isset($queryParams['filter']['EscalatorCondition']) && is_array($queryParams['filter']['EscalatorCondition'])) {
        $filterEscalatorCondition = $queryParams['filter']['EscalatorCondition'];
    }
    $filterEscalator = [];
    if (isset($queryParams['filter']['Escalator']) && is_array($queryParams['filter']['Escalator'])) {
        $filterEscalator = $queryParams['filter']['Escalator'];
    }
    // Includes
    $withEscalator = false;
    if(isset($queryParams['include'])) {
        $withArray = explode(',', $queryParams['include']);
        if(isset($withArray) && count($withArray) > 0) {
            foreach ($withArray as $with) {
                if(isset($queryParams['fields'][$with])) {
                    $fields[$with] = explode(',', $queryParams['fields'][$with]);
                } elseif($with == 'Escalator' ) {
                    $escalatorTable = \Map\EscalatorTableMap::getTableMap();
                    foreach ($escalatorTable->getColumns() as $column) {
                        $fields['Escalator'][] = $column->getPhpName();
                    }
                }
                if ($with == 'Escalator') {
                    $withEscalator = true;
                }
            }
        }
    }
    // Primary entity fields
    if(isset($queryParams['fields']['EscalatorCondition'])) {
        $fields['EscalatorCondition'] = explode(',', $queryParams['fields']['EscalatorCondition']);
    } else {
        $escalatorConditionTable = \Map\EscalatorConditionTableMap::getTableMap();
        foreach ($escalatorConditionTable->getColumns() as $column) {
            $fields['EscalatorCondition'][] = $column->getPhpName();
        }
    }
    $escalatorConditions = \EscalatorConditionQuery::create()
            ->addEscalator($withEscalator, $fields['Escalator'])
            ->addEscalatorConditionFilters($filterEscalatorCondition)
            ->addEscalatorFilters($filterEscalator)
            ->orderBy($orderBy, $customConfiguration['orderByAsc'])
            ->paginate($customConfiguration['page'], $customConfiguration['perPage']);
    foreach ($escalatorConditions as $escalatorCondition) {
        $tempEscalatorConditions = [];
        foreach ($fields as $entity => $entityFields) {
            foreach ($entityFields as $field) {
                if (array_key_exists($field, $escalatorCondition->toArray())) {
                    $tempEscalatorConditions[$field] = $escalatorCondition->toArray()[$field];
                } elseif ($escalatorCondition->hasVirtualColumn($entity.$field)) {
                    $tempEscalatorConditions[$entity.$field] = $escalatorCondition->getVirtualColumn($entity.$field);
                }
            }
        }
        // Hack to allow for MySQL JSON field type
        $tempEscalatorConditions['TargetDate'] = json_decode($tempEscalatorConditions['TargetDate']);
        $tempEscalatorConditions['TargetLots'] = json_decode($tempEscalatorConditions['TargetLots']);
        $arrEscalatorConditions[] = $tempEscalatorConditions;
    }
    $data = [
        'page' => $escalatorConditions->getPage(),
        'rows' => $arrEscalatorConditions,
        'totalRecords' => $escalatorConditions->getNbResults(),
        'totalPages' => $escalatorConditions->getLastPage()
        ];

    return $response->withJson($data);
});

$app->get('/slimapi/v1/escalatorConditions/{id}', function (Request $request, Response $response, $args) {
    // Initialization
    $queryParams = $request->getQueryParams();
    $id = $args['id'];
    $fields = [
        'Escalator' => [],
        'EscalatorCondition' => []
        ];
    // Includes
    $withEscalator = false;
    if(isset($queryParams['include'])) {
        $withArray = explode(',', $queryParams['include']);
        if(isset($withArray) && count($withArray) > 0) {
            foreach ($withArray as $with) {
                if(isset($queryParams['fields'][$with])) {
                    $fields[$with] = explode(',', $queryParams['fields'][$with]);
                } elseif($with == 'Escalator' ) {
                    $escalatorTable = \Map\EscalatorTableMap::getTableMap();
                    foreach ($escalatorTable->getColumns() as $column) {
                        $fields['Escalator'][] = $column->getPhpName();
                    }
                }
                if ($with == 'Escalator') {
                    $withEscalator = true;
                }
            }
        }
    }
    // Primary entity fields
    if(isset($queryParams['fields']['EscalatorCondition'])) {
        $fields['EscalatorCondition'] = explode(',', $queryParams['fields']['EscalatorCondition']);
    } else {
        $escalatorConditionTable = \Map\EscalatorConditionTableMap::getTableMap();
        foreach ($escalatorConditionTable->getColumns() as $column) {
            $fields['EscalatorCondition'][] = $column->getPhpName();
        }
    }
    // Get primary entity
    $escalatorCondition = \EscalatorConditionQuery::create()
            ->select($fields['EscalatorCondition'])
            ->addEscalator($withEscalator, $fields['Escalator'])
            ->findPK($id);

    // Hack to allow for MySQL JSON field type
    $escalatorCondition['TargetDate'] = json_decode($escalatorCondition['TargetDate']);
    $escalatorCondition['TargetLots'] = json_decode($escalatorCondition['TargetLots']);

    return $response->withJson($escalatorCondition);
})->add(new Validation($validateId));

$app->post('/slimapi/v1/escalator/{id}/escalatorConditions', function (Request $request, Response $response, $args) {
    $result = [];
    $allPostPutVars = $request->getParsedBody();

    $escalatorId = (int)$args['id'];
    $escalator = \EscalatorQuery::create()->findPK($escalatorId);
    if ($escalatorId < 1 || !$escalator) {
        return $response->withJson(['message' => 'Invalid Escalator'], 422);
    }

    foreach ($allPostPutVars as $row) {
        $escalatorCondition = new \EscalatorCondition();
        $escalatorConditionTable = \Map\EscalatorConditionTableMap::getTableMap();
        foreach ($row as $fieldName => $fieldValue) {
            if ($escalatorConditionTable->hasColumnByPhpName($fieldName) ) {
                if (is_array($fieldValue)) {
                    $fieldValue = json_encode($fieldValue);
                }
                $escalatorCondition->setByName($fieldName, $fieldValue);
            }
        }

        $escalatorCondition->setEscalatorId($escalatorId);

        $escalatorCondition->setCreatedBy($request->getAttribute('userId'));
        $escalatorCondition->setCreatedDate('now');
        $escalatorCondition->setLastModifiedBy($user->getId());
        $escalatorCondition->setLastModifiedDate('now');
        $escalatorCondition->save();

        // Hack to allow for MySQL JSON field type
        $temp = $escalatorCondition->toArray();
        $temp['TargetDate'] = json_decode($temp['TargetDate']);
        $temp['TargetLots'] = json_decode($temp['TargetLots']);
        $result[] = $temp;
    }

    return $response->withJson($result);
})->add(new Validation($validateId));

$app->patch('/slimapi/v1/escalatorConditions', function (Request $request, Response $response) {
    $result = [];
    $allPostPutVars = $request->getParsedBody();

    foreach ($allPostPutVars as $row) {
        $escalatorCondition = \EscalatorConditionQuery::create()->findPk($row['EscalatorConditionId']);
        $escalatorConditionTable = \Map\EscalatorConditionTableMap::getTableMap();
        foreach ($row as $fieldName => $fieldValue) {
            if ($escalatorConditionTable->hasColumnByPhpName($fieldName) ) {
                if (is_array($fieldValue)) {
                    $fieldValue = json_encode($fieldValue);
                }
                $escalatorCondition->setByName($fieldName, $fieldValue);
            }
        }

        $escalatorCondition->setLastModifiedBy($request->getAttribute('userId'));
        $escalatorCondition->setLastModifiedDate('now');
        $escalatorCondition->save();

        // Hack to allow for MySQL JSON field type
        $temp = $escalatorCondition->toArray();
        $temp['TargetDate'] = json_decode($temp['TargetDate']);
        $temp['TargetLots'] = json_decode($temp['TargetLots']);
        $result[] = $temp;
    }

    return $response->withJson($result);
});

$app->delete('/slimapi/v1/escalatorConditions/{id}', function (Request $request, Response $response, $args) {
    $id = (int)$args['id'];

    $escalatorCondition = \EscalatorConditionQuery::create()->findOneByEscalatorConditionId($id);

    if ($escalatorCondition) {

        $escalatorCondition->setLastModifiedBy($request->getAttribute('userId'));
        $escalatorCondition->setLastModifiedDate('now');
        $escalatorCondition->setIsDeleted(1);
        $escalatorCondition->save();
    } else {
        return $response->withStatus(404);
    }

    return $response->withStatus(204);
})->add(new Validation($validateId));

/*
    Fetches limitations
    Table: fischer_api.limitation
    Description: Returns all limitations.
*/
$app->get('/slimapi/v1/limitations', function(Request $request, Response $response, $args)  use ($customConfiguration) {
    $queryParams = $request->getQueryParams();
    $arrLimitations = [];
    $orderBy = 'Code';
    $showAll = false;

    // Pagination
    if(isset($queryParams['page'])) {
        $customConfiguration['page'] = $queryParams['page'];
    }
    if(isset($queryParams['per_page'])) {
        if($queryParams['per_page'] == -1) {
            $showAll = true;
        } else {
            $customConfiguration['perPage'] = $queryParams['per_page'];
        }
    }

    // Order
    if(isset($queryParams['orderBy'])) {
        $orderBy = $queryParams['orderBy'];
    }
    if(isset($queryParams['orderByAsc'])) {
        $customConfiguration['orderByAsc'] = $queryParams['orderByAsc'];
    }

    $limitations = \LimitationQuery::create()
                    ->orderBy($orderBy, $customConfiguration['orderByAsc'])
                    ->paginate($customConfiguration['page'], $customConfiguration['perPage']);


    if($showAll) {
        $customConfiguration['perPage'] = $limitations->getNbResults(); 

        $limitations = \LimitationQuery::create()
                        ->orderBy($orderBy, $customConfiguration['orderByAsc'])
                        ->paginate($customConfiguration['page'], $customConfiguration['perPage']);
    }

    // Loop through limitations set pull what columns are wanted.
    /*foreach($limitations as $limitation) {
        $arrLimitations[] = $limitation->toArray();
    }*/

    $arrLimitations = $limitations->toArray();

    $data = [
        'page' => $limitations->getPage(),
        'rows' => $arrLimitations,
        'totalRecords' => $limitations->getNbResults(),
        'totalPages' => $limitations->getLastPage()
    ];

    return $response->withJson($data);
})->add(new Validation($validateId));

/*
    Creates limitations
    Table: fischer_api.limitation
    Description: Returns all limitations.
*/
$app->post('/slimapi/v1/sites/{id}/limitations', function(Request $request, Response $response, $args)  use ($customConfiguration) {
    $siteId = (int)$args['id'];

    if(!isset($siteId) || $siteId < 1) {
        // Invalid site id
        return $response->withStatus(404);
    }

    $data = [];
    $queryParams = $request->getParsedBody();
    $communityId =null;
    $fischerSection = null;
    $legalSection = null;
    $limitationType = 1; // site level
    
    if(!isset($queryParams['limitations']) || count($queryParams['limitations']) < 1) {
        // Invalid limitations
        return $response->withStatus(404);
    }

    if(isset($queryParams['fischerSection']) && $queryParams['fischerSection'] > 0) {
        $fischerSection = intval($queryParams['fischerSection']);
    }

    if(isset($queryParams['legalSection']) && $queryParams['legalSection'] > 0) {
        $legalSection = intval($queryParams['legalSection']);
    }

    foreach($queryParams['limitations'] as $limitation) {
        $duplicate = false;

        try {
            // Find one or create method.
            $newLimitation = \RefLimitationQuery::create()
                                ->filterByLimitationId($limitation)
                                ->filterBySiteId(intval($siteId))
                                ->findOneOrCreate();

            if($newLimitation) {
                if($newLimitation->getRefLimitationsId() !== null) {
                    $duplicate = true;
                    $data['rows']['duplicates'][] = $newLimitation->toArray();
                }

                // Set values
                $newLimitation->setLimitationId($limitation);
                $newLimitation->setLimitationTypeId(intval($limitationType));
                $newLimitation->setCommunityId(intval($queryParams['community']));
                $newLimitation->setFischerSectionId($fischerSection);
                $newLimitation->setLegalSectionId($legalSection);
                $newLimitation->setSiteId(intval($siteId));
                $newLimitation->setIsDeleted(0);
                $newLimitation->setCreatedBy(intval($request->getAttribute('userId')));
                $newLimitation->setLastModifiedBy(intval($request->getAttribute('userId')));

                // Save the limitation.
                $newLimitation->save();

                // Sync to pervasive
                $pervasiveData = [];

                // Select information to send to sync.
                $syncData = \RefLimitationQuery::create()
                            ->useCommunitySiteQuery()
                                ->filterBySiteId($newLimitation->getSiteId())
                            ->endUse()
                            ->useLimitationQuery()
                                ->filterByLimitationId($newLimitation->getLimitationId())
                            ->endUse()
                            ->with('CommunitySite')
                            ->with('Limitation')
                            ->findPk($newLimitation->getRefLimitationsId());

                $pervasiveData['site_number'] = $syncData->getCommunitySite()->getSiteNumber();
                $pervasiveData['code'] = $syncData->getLimitation()->getCode();
                $pervasiveData['date_created'] = $newLimitation->getCreatedDate();
                $pervasiveData['last_modified_date'] = $newLimitation->getCreatedDate();
                $pervasiveData['modified_by'] = $newLimitation->getLastModifiedBy();


                // Sync Pervasive Here.
                $pervasiveMapSite = new SyncPervasive(SYSTEM_NAME, 'site-manager', 'Fischer Management', 'ref_limitation', 'create');

                // Run the system sync?
                $pervasiveMapSite->run($pervasiveData);

                if(!$duplicate) {
                    $data['rows']['created'][] = $newLimitation->toArray(); 
                }
            }
        }
        catch(Exception $e) { 
            die($e);
            $data['rows']['failed'][] = $newLimitation->toArray();
        }
    }

    return $response->withJson($data, 200);
})->add(new Validation($validateId));

/*
    Change this to /slimapi/v1/{entity_type}/{entity_id}/limitation/{id}

    This way you pass in the limitation type the entity id and the limitation id and delete
    by that.

    Need to up the database to reflect these changes.
*/
$app->delete('/slimapi/v1/site/limitations/{id}', function(Request $request, Response $response, $args)  use ($customConfiguration) {
    $refLimitationId = $args['id'];

    if($refLimitationId < 1) {
        return $reponse->withStatus(404);
    }

    $refLimitation = \RefLimitationQuery::create()->findPk($refLimitationId);

    if($refLimitation) {

        $refLimitation->setIsDeleted(1);
        $refLimitation->setLastModifiedBy($request->getAttribute('userId'));
        $refLimitation->save();

        $pervasiveData = [];

        $syncData = \RefLimitationQuery::create()
                    ->useCommunitySiteQuery()
                        ->filterBySiteId($refLimitation->getSiteId())
                    ->endUse()
                    ->useLimitationQuery()
                        ->filterByLimitationId($refLimitation->getLimitationId())
                    ->endUse()
                    ->with('CommunitySite')
                    ->with('Limitation')
                    ->findPk($refLimitation->getRefLimitationsId());

        $pervasiveData['site_number'] = $syncData->getCommunitySite()->getSiteNumber();
        $pervasiveData['code'] = $syncData->getLimitation()->getCode();

        // Sync Pervasive Here.
        $pervasiveMapSite = new SyncPervasive(SYSTEM_NAME, 'site-manager', 'Fischer Management', 'CommSiteLimitation', 'delete');

        // Run the system sync?
        $pervasiveMapSite->run($pervasiveData);


        return $response->withStatus(204);
    }

    return $response->withJson($result, 404);

})->add(new Validation($validateId));

/*
    Fetches limitations attached to sites.
    Table: fischer_api.ref_limitations
    Description: Returns all limitations currently
    on the specified site.
*/
$app->get('/slimapi/v1/site/{id}/limitations', function(Request $request, Response $response, $args)  use ($customConfiguration) {
    // variables
    $id = $args['id'];
    $arrSiteLimitations = [];
    $orderBy = "LimitationId";
    $queryParams = $request->getQueryParams();

    if(isset($queryParams['page'])) {
        $customConfiguration['page'] = $queryParams['page'];
    }

    if(isset($queryParams['per_page'])) {
        if($queryParams['per_page'] == -1) {
            $showAll = true;
        } else {
            $customConfiguration['perPage'] = $queryParams['per_page'];
        }
    }

    if(isset($queryParams['orderBy'])) {
        $orderBy = $queryParams['orderBy'];
    }

    if(isset($queryParams['orderByAsc'])) {
        $customConfiguration['orderByAsc'] = $queryParams['orderByAsc'];
    }

    // Select all limitations associated with site.
    $siteLimitations = \RefLimitationQuery::create()
                        ->filterBySiteId($id)
                        ->orderBy($orderBy, $customConfiguration['orderByAsc'])
                        ->paginate($customConfiguration['page'], $customConfiguration['perPage']);

    // If show all grab all limitations
    if($showAll) {
        $customConfiguration['perPage'] = $siteLimitations->getNbResults();

        $siteLimitations = \RefLimitationQuery::create()
                            ->filterBySiteId($id)
                            ->filterByIsDeleted($customConfiguration['isDeletedFilter'])
                            ->orderBy($orderBy, $customConfiguration['orderByAsc'])
                            ->paginate($customConfiguration['page'], $customConfiguration['perPage']);
    }

    foreach($siteLimitations as $sl) {
        $arrSiteLimitations[] = $sl->toArray();
    }

    $data = [
        'page' => $siteLimitations->getPage(),
        'rows' => $arrSiteLimitations,
        'totalRecords' => $siteLimitations->getNbResults(),
        'totalPages' => $siteLimitations->getLastPage()
    ];

    return $response->withJson($data);
})->add(new Validation($validateId));

/*
    Fetches environment config.
    Table: fischer_api.configuration
    Description: Returns all rows of the configuration table
*/
$app->get('/slimapi/v1/configurations', function(Request $request, Response $response) {
    $configArray = [];

    // Select all rows from the configuration table.
    $configs = \ConfigurationQuery::create()->find();

    foreach($configs as $config) {
        $configArray[$config->getKey()] = $config->getValue();
    }   

    return $response->withJson($configArray);
})->add(new Validation($validateId));

// Communities fischer section.
$app->get('/slimapi/v1/communities/{id}/fischerSections', function(Request $request, Response $response, $args)  use ($customConfiguration) {
    $id = $args['id'];
    $arrFischerSections = [];

    if($id > 0) {
        $sections = \CommunitySectionQuery::create()
                    ->filterByCommunityId($id)
                    ->filterByIsDeleted($customConfiguration['isDeletedFilter']);

        foreach($sections as $sec) {
            $tmp = $sec->toArray();
            $tmp['Count'] = $sec->countCommunitySites();
            $arrFischerSections['CommunitySections'][] = $tmp;
        }
    }

    return $response->withJson($arrFischerSections, 200);
});

// Community Legal Sections
$app->get('/slimapi/v1/communities/{id}/legalSections', function(Request $request, Response $response, $args)  use ($customConfiguration) {
    $id = $args['id'];
    $arrLegalSections = [];
    $data = [];
    $showAll = false;
    $queryParams = $request->getQueryParams();

    if(isset($queryParams['page'])) {
        $customConfiguration['page'] = $queryParams['page'];
    }
    if(isset($queryParams['per_page'])) {
        if($queryParams['per_page'] == -1) {
            $showAll = true;
        } else {
            $customConfiguration['perPage'] = $queryParams['per_page'];
        }
    }
    if(isset($queryParams['is_deleted'])) {
        $customConfiguration['isDeletedFilter'] = $queryParams['is_deleted'];
    }

    if($id > 0) {
        $legalSections = \CommunitySectionLegalQuery::create()
                         ->filterByCommunityId($id)
                         ->filterByIsDeleted($customConfiguration['isDeletedFilter'])
                         ->paginate($customConfiguration['page'], $customConfiguration['perPage']);

        if($showAll) {
            $customConfiguration['perPage'] = $legalSections->getNbResults();

            $legalSections = \CommunitySectionLegalQuery::create()
                             ->filterByCommunityId($id)
                             ->filterByIsDeleted($customConfiguration['isDeletedFilter'])
                             ->paginate($customConfiguration['page'], $customConfiguration['perPage']);
        }

        foreach($legalSections as $leg) {
            $tmp = $leg->toArray();
            $tmp['Count'] = $leg->countCommunitySites();

            $arrLegalSections[] = $tmp;
        }

        $data = [
            'page' => $legalSections->getPage(),
            'rows' => $arrLegalSections,
            'totalRecords' => $legalSections->getNbResults(),
            'totalPages' => $legalSections->getLastPage()
        ];
    }

    return $response->withJson($data, 200);
})->add(new Validation($validateId));

// CORS
$app->add(function ($req, $res, $next) {
    $response = $next($req, $res);
    return $response
            ->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization, nolog, Application, uid')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, PATCH, DELETE, OPTIONS');
});

$app->run();
