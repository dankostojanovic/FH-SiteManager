<?php

namespace FischerHomes\Components;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\RequestException;

//use Propel\Runtime\ActiveQuery\PropelQuery;

use \FischerHomes\Components\Exceptions\EventEmitterEmitException;
use \FischerHomes\Components\Exceptions\EventEmitterAuthException;


/**
 * Event Emitter Component
 *
 * @package app\components
 */
class EventEmitterComponent
{
    /**
     * Attribute to determine which event is to be emitted
     * @var string
     */
    public $eventId;

    /**
     * Attribute to hold oAuth token to use with emit request
     * @var string
     */
    private $authToken;

    /**
     * Ids of entities to be included in the event body
     * @var array
     */
    public $ids = [];

    /**
     * attribute to hold Event body string
     * @var string
     */
    protected $body = '';

    /**
     * attribute to determine if new auth token has been requested.
     * If true we should not request another token
     * as most likely we are in infinite loop between auth and events systems
     * @var boolean
     */
    private $breakOnFail = false;

    /**
     * Get auth token from the database
     *
     * @return string A string to be used in header of events requests
     */
    protected function getAuthTokenDB()
    {
        $configuration = \ConfigurationQuery::create()->select('Value')->findOneByKey('sitemanager-authtoken');

        return $configuration;
    }

    /**
     * Sets auth token to be used by events requests
     * Also saves current token into the database
     *
     * @param  string  A string to be set as token and saved intot the database
     * @return boolean True if token saved to the database
     */
    protected function setAuthToken($authToken)
    {
        $configuration = \ConfigurationQuery::create()->filterByKey('sitemanager-authtoken')->findOne();
        $configuration->setValue($authToken);
        if ($authToken === $configuration->getValue() || $configuration->save()) {
            $this->authToken = $authToken;

            return true;
        }

        return false;
    }

    /**
     * Creates an auth token by requesting it from auth service
     *
     * @return mixed
     *
     * @throws Exception
     */
    // TODO
    protected function createAuthToken()
    {
        $url = '/oauth/token';
        try {
            $response = $this->client(getenv('AUTH_API_URL'))->post(
                    $url,
                    [
                        'json' => [
                            'client_id' => getenv('AUTH_CLIENT_ID'),
                            'client_secret' => getenv('AUTH_CLIENT_SECRET'),
                            'grant_type' => 'client_credentials'
                        ]
//                        'debug' => true
                    ]
            );
            if ($response->getStatusCode() !== 200) {
                $responseBody = json_decode($response->getBody());
//                throw new AuthException();
            }
        } catch (RequestException $ex) {
//            $statusCode = null;
//            $content = null;
//            if ($ex->getResponse()) {
//                $statusCode = $ex->getResponse()->getStatusCode();
//                $content = $ex->getResponse()->getBody()->getContents();
//            }
//            return $content;
//        } catch (AuthException $ex) {
//            $statusCode = null;
//            $content = null;
//            if ($ex->getMessage()) {
//                $content = $ex->getMessage();
//            }
//            return $content;
        }
        $responseBody = json_decode($response->getBody());
        return "Bearer " . $responseBody->access_token;
    }

    /**
     * Get breakonFail flag value.
     *
     * @return boolean
     */
    protected function getBreakOnFail()
    {
        return $this->breakOnFail;
    }

    /**
     * Set the value of breakOnFail flag.
     *
     * @param boolean $breakOnFail new value
     * @return void
     */
    protected function setBreakOnFail($breakOnFail = false)
    {
        $this->breakOnFail = $breakOnFail;
    }

    protected function getEventId()
    {
        return $this->eventId;
    }

    /**
     * Set the value of eventId string.
     *
     * @param string $eventId new value
     * @return void
     */
    protected function setEventId($eventId = '')
    {
        $this->eventId = $eventId;
    }

    protected function getIds()
    {
        return $this->ids;
    }

    protected function setIds($ids = [])
    {
        $this->ids = $ids;
    }

    protected function getBody()
    {
        return $this->body;
    }

    protected function setBody($body = '')
    {
        $this->body = $body;
    }

    /**
     * Calls appropriate create body function
     * Can be used to define accptable events
     *
     * @return void
     */
    protected function createBody ()
    {
        switch ($this->getEventId()) {
            case 'fischer-section-created':
                $this->createBodyFischerSectionCreated();
                break;

            case 'legal-section-created':
                $this->createBodyLegalSectionCreated();
                break;

            case 'site-created':
                $this->createBodySiteCreated();
                break;

            case 'site-creation-failed':
                $this->createBodySiteCreateFailed();
                break;

            default:
                break;
        }
    }

    /**
     * Set the value of body attribute based on Fischer Section Id.
     *
     * @return boolean True if body set
     */
    protected function createBodyFischerSectionCreated()
    {
        $body = '';
        $createdDate = '';
        // Find Fischer Section
        $fischerSectionId = $this->ids[0];
        $fischerSection = \CommunitySectionQuery::create()->findPK($fischerSectionId);
        if ($fischerSection->getCreatedDate()) {
            $createdDate = $fischerSection->getCreatedDate()->format('m/d/Y');
        }
        $user = \UsersQuery::create()->select(['FischerUsername', 'FirstName', 'LastName'])->findPK($fischerSection->getCreatedBy());
        $body = 'Community: ' . $fischerSection->getCommunity()->getCode() . ' - ' . $fischerSection->getCommunity()->getName();
        $body .= '<br>Section: ' . $fischerSection->getSectionName();
        $body .= '<br>Specification Level: ' . $fischerSection->getSpecLevelId();
        $body .= '<br><br>Created By: ' . $user['FischerUsername'];
        $body .= '<br>Created On: ' . $createdDate;

        $this->setBody($body);
    }

    /**
     * Set the value of body attribute based on Legal Section Id.
     *
     * @return boolean True if body set
     */
    protected function createBodyLegalSectionCreated()
    {
        $body = '';
        $createdDate = '';
        // Find Legal Section
        $legalSectionId = $this->ids[0];
        $legalSection = \CommunitySectionLegalQuery::create()->findPK($legalSectionId);
        if ($legalSection->getCreatedDate()) {
            $createdDate = $legalSection->getCreatedDate()->format('m/d/Y');
        }

        $user = \UsersQuery::create()->select(['FischerUsername', 'FirstName', 'LastName'])->findPK($legalSection->getCreatedBy());
        $body = 'Community: ' . $legalSection->getCommunity()->getCode() . ' - ' . $legalSection->getCommunity()->getName();
        $body .= '<br>Legal Section: ' . $legalSection->getLegalSectionName();
        $body .= '<br>Phase Name: ' . $legalSection->getSectionPhaseName();
        $body .= '<br><br>Created By: ' . $user['FirstName'] . ' ' . $user['LastName'];
        $body .= '<br>Created On: ' . $legalSection->getCreatedDate()->format('m/d/Y');

        $this->setBody($body);
    }

    /**
     * Set the value of body attribute based on Site Ids.
     *
     * @return boolean True if body set
     */
    protected function createBodySiteCreated()
    {
        $sites = [];

        foreach ($this->ids as $siteId) {
            $site = \CommunitySiteQuery::create()
                    ->addCommunity(true, ['Code', 'Name'])
                    ->addFischerSection(true, ['SectionName'])
                    ->addLegalSection(true, ['LegalSectionName'])
                    ->select(['SiteNumber', 'SiteId', 'CreatedBy', 'CreatedDate'])
                    ->findPK($siteId);
            $sites[] = substr($site['SiteNumber'], 5, 3);
        }
        $user = \UsersQuery::create()->select(['FischerUsername', 'FirstName', 'LastName'])->findPK($site['CreatedBy']);
        $date = strtotime($site['CreatedDate']);
        $body = 'Community: ' . $site['CommunityCode'] . ' - ' . $site['CommunityName'];
        $body .= '<br>Fischer Section: ' . $site['CommunitySectionSectionName'];
        $body .= '<br>Legal Section: ' . $site['CommunitySectionLegalLegalSectionName'];
        $body .= '<br><br>Created By: ' . $user['FirstName'] . ' ' . $user['LastName'];
        $body .= '<br>Created On: ' . date("m/d/Y", $date);
        $body .= '<br>Sites: ' . min($sites) . " - " . max($sites);

        $this->setBody($body);
    }

    protected function createBodySiteCreateFailed()
    {
        $body = 'Error creating sites:';
        foreach ($this->ids as $siteId) {
            $site = \CommunitySiteQuery::create()
                    ->select(['SiteId', 'SiteNumber', 'CreatedBy', 'CreatedDate'])
                    ->findPK($siteId);

            $user = \UsersQuery::create()->select(['FischerUsername', 'FirstName', 'LastName'])->findPK($site['CreatedBy']);

            $body .= '<br>' . $site->getSiteNumber();
            $body .= '<br>User: ' . $user['FischerUsername'] . ' ' . $user['FirstName'] . ' ' . $user['LastName'];
            $body .= '<br>Date: ' . date("m/d/Y", strtotime($site['CreatedDate'])) . '<br><br>';
        }

        $this->setBody($body);
    }

    public function emit($eventId, $ids = [])
    {
        $this->setEventId($eventId);
        $this->setIds($ids);
        $this->createBody();
        $this->setAuthToken($this->getAuthTokenDB());
        if (!$this->authToken) {
            $this->setAuthToken($this->createAuthToken());
            $this->setBreakOnFail(TRUE);
        }
        try {
            $this->dispatch();
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
            return false;
        }

        return true;
    }

    private function dispatch()
    {
        try {
            $this->sendDispatch();
        } catch (EventEmitterAuthException $exc) {
            if ($this->breakOnFail) {
                throw $exc;
            }

            $this->setAuthToken($this->createAuthToken());
            $this->setBreakOnFail(TRUE);
            $this->dispatch();
        } catch (Exception $exc) {
            throw $exc;
        }
    }

    /*
     * Dispatches an event
     *
     * @param string $body
     *
     */
    public function sendDispatch()
    {
        $url = '/api/v5/dispatch';
        try {
            // FIXME I don't know if this is the right way to set base URL
            $response = $this->client(getenv('EVENTS_API_URL'))->post(
                    $url,
                    [
                        'json' => [
                            'event_id' => $this->getEventId(),
                            'body' => $this->getBody()
                        ],
                        'headers' => [
                            'Authorization' => $this->authToken,
                            'Content-Type' => 'application/json'
                        ]
                    ]
            );
            if ($response->getStatusCode() !== 202) {
                $response_body = json_decode($response->getBody());
                throw new EventEmitterEmitException($this->event_id, $this->getBody(), $url, $response->getStatusCode(), $response_body);
            }
        } catch (RequestException $ex) {
            $statusCode = null;
            $content = null;
            if ($ex->getResponse()) {
                $statusCode = $ex->getResponse()->getStatusCode();
                $content = $ex->getResponse()->getBody()->getContents();
            }
            if($statusCode == 403) {
                $error = new EventEmitterAuthException($url, $statusCode, $content);
                $error->setMessage('Event authentication failed');
                throw $error;
            }
            return $content;
        }
        return true;
    }

    /**
     * Instantiates a new HTTP client
     *
     * @param string $url new value for base_uri
     * @return  ClientInterface
     */
    private function client($url)
    {
        return new Client([
            'base_uri' => $url
        ]);
    }
}
