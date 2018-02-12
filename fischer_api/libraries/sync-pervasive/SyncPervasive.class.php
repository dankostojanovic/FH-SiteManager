<?php

use Propel\Runtime\ActiveQuery\PropelQuery;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\RequestException;

use \FischerHomes\SyncPervasive\Exceptions\SyncPervasiveSaveException;
use \FischerHomes\SyncPervasive\Exceptions\SyncPervasiveConfirmedException;
use \FischerHomes\SyncPervasive\Exceptions\SyncPervasiveApiException;

class SyncPervasive {

    // System requesting data transfer
    private $system;

    // Application requesting data transfer
    private $application;

    // Pervasive database to write to
    private $pervasiveDatabase;

    // Fischer API table
    private $table;

    // Action performed
    private $action;

    // Pervasive table name
    private $pervasiveTables = [];

    // Fischer data to be transfered
    // Format ['field' => value]
    private $data = [];

    private $translatedData = [];

    private $keyFields = [];

    private $insertedIds = [];

    function __construct($system, $application, $pervasiveDatabase, $table, $action, $data = [])
    {
        $this->setSystem($system);
        $this->setApplication($application);
        $this->setPervasiveDatabase($pervasiveDatabase);
        $this->setTable($table);
        $this->setAction($action);
        $this->setData($data);
    }

    public function getSystem()
    {
        return $this->system;
    }

    public function setSystem($system)
    {
        $this->system = $system;
    }

    public function getApplication()
    {
        return $this->application;
    }

    public function setApplication($application)
    {
        $this->application = $application;
    }

    public function getPervasiveDatabase()
    {
        return $this->pervasiveDatabase;
    }

    public function setPervasiveDatabase($pervasiveDatabase)
    {
        $this->pervasiveDatabase = $pervasiveDatabase;
    }

    public function getTable()
    {
        return $this->table;
    }

    public function setTable($table)
    {
        $this->table = $table;
    }

    public function getAction()
    {
        return $this->action;
    }

    public function setAction($action)
    {
        $this->action = $action;
    }

    public function getPervasiveTables()
    {
        return $this->pervasiveTables;
    }

    public function setPervasiveTable($pervasiveTable)
    {
        if (!in_array($pervasiveTable, $this->pervasiveTables)) {
            $this->pervasiveTables[] = $pervasiveTable;
        }
    }

    public function getData()
    {
        return $this->data;
    }

    public function setData($data)
    {
        $this->data = $data;
    }

    public function getTranslatedData()
    {
        return $this->translatedData;
    }

    public function setTranslatedData($pervasiveTable, $translatedData)
    {
        $this->translatedData[$pervasiveTable] = $translatedData;
    }

    public function getKeyFields()
    {
        return $this->keyFields;
    }

    public function setKeyFields($table, $keyFields)
    {
        $this->keyFields[$table] = $keyFields;
    }

    public function translate()
    {
        $data = [];
        $table = \PervasivemapTableQuery::create()->findOneByName($this->table);
        $fields = $table->getPervasivemapFields();
        // TODO can be something like if $key in_array(fields) instead of two foreach loops
        foreach ($fields as $field) {
            foreach ($this->data as $key => $value) {
                if (trim($key) === trim($field->getName())) {
                    if ($field->getPervasiveValue()) {
                        $related = json_decode($field->getPervasiveValue());
                        $value = PropelQuery::from($related->table)->select($related->field)->findPk($value);
                    }
                    $pervasiveTable = \PervasivemapPervasivetableQuery::create()->findOneByName($field->getPervasiveTable());
                    $this->setKeyFields($field->getPervasiveTable(), $pervasiveTable->getKeyFields());
                    $this->setPervasiveTable($field->getPervasiveTable());
                    if ($field->getPervasiveType() == 'date') {
                        if ($value === '') {
                            $value = null;
                        }
                        if($value !== null && $value != '') {
                            $value = $value->format('Y-m-d H:i:s');
                        }
                    }
                    $data[$field->getPervasiveTable()][$field->getPervasiveField()] = [
                        'type' => $field->getPervasiveType(),
                        'value' => $value
                        ];
                }
            }
        }
        
        foreach ($data as $pervasiveTableName => $pervasiveTableData) {
            $this->setTranslatedData($pervasiveTableName, json_encode($pervasiveTableData));
        }
    }

    public function save()
    {
        foreach ($this->pervasiveTables as $pervasiveTable) {
            $systemSync = new \SystemSync();
            $systemSync->setSystem($this->system);
            $systemSync->setApplication($this->application);
            $systemSync->setPervasiveDatabase($this->pervasiveDatabase);
            $systemSync->setPervasiveTable($pervasiveTable);
            $systemSync->setAction($this->action);
            $systemSync->setKeyFields($this->keyFields[$pervasiveTable]);
            $systemSync->setData($this->translatedData[$pervasiveTable]);
            $systemSync->setCreatedDate('now');
            if ($systemSync->save()) {
                $this->insertedIds[] = $systemSync->getId();
                continue;
            } else {
                return false;
            }
        }
        return true;
    }

    public function run($data, $syncronous = false)
    {
        $this->setData($data);
        $this->translate();
        if(!$this->save()) {
            throw new SyncPervasiveSaveException();
        }

        if ($syncronous) {
            // Call sync procedure (guzzle "David's API")
            $this->syncSystems();
        }
    }

    // Triggers API which processes SystemSync table and updates Pervasive database
    protected function syncSystems()
    {
        $url = '/API/MySqlData/';
        try {
            $client = $this->client();
            $client->get($url);
            $this->testSyncSuccess();
        } catch (RequestException $ex) {
            $statusCode = $ex->getResponse() ? $ex->getResponse()->getStatusCode() : null;
            $content = $ex->getResponse() ? $ex->getResponse()->getBody()->getContents() : null;
            throw new SyncPervasiveApiException($url, $statusCode, $content, $ex);
        }
    }

    // Query database to check if records have been successfully processed
    protected function testSyncSuccess()
    {
        foreach ($this->insertedIds as $insertedId) {
            $systemSync = \SystemSyncQuery::create()->findPk($insertedId);
            $systemSync->reload();
            if (!$systemSync->getIsProcessed() || $systemSync->getIsErrored()) {
                throw new SyncPervasiveConfirmedException($systemSync->getId());
            }
        }
    }

    /**
     * Instantiates a new HTTP client
     *
     * @return  ClientInterface
     */
    private function client()
    {
        return new Client([
            'base_uri' => getenv('PERVASIVE_API_URL'),
            'headers' => [
                'Authorization' => getenv('PERVASIVE_API_TOKEN'),
                'Content-Type' => 'application/json'
            ]
        ]);
    }
}
