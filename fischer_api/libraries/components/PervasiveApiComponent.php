<?php

namespace FischerHomes\Components;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\RequestException;

use \FischerHomes\Components\Exceptions\PervasiveApiAvailableFlagException;
use \FischerHomes\Components\Exceptions\PervasiveApiRequestException;

/**
 * Pervasive API
 *
 * @package app\components
 */
class PervasiveApiComponent
{
    private $token;
    private $baseUri;
    private $url;

    public function __construct()
    {
        $this->setBaseUri(getenv('PERVASIVE_API_URL'));
        $this->setToken(getenv('PERVASIVE_API_TOKEN'));
    }

    public function setBaseUri($uri)
    {
        $this->baseUri = $uri;
    }

    public function getBaseUri()
    {
        return $this->baseUri;
    }

    public function setToken($token)
    {
        $this->token = $token;
    }

    public function getToken()
    {
        return $this->token;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function setUrl($url = '')
    {
        $this->url = $url;
    }

    public function updateSiteAvailableFlag($siteNumber, $currentValue, $proposedValue, $status)
    {
        $this->setUrl('/API/SiteAvailability/');
        try {
            /*
             * Response can be
             * 200 - all OK
             * 401 - authentication failed
             * 404 - API not accessible
             * 422 - validation failed
             * 503 - propagation failed
             */
            $response = $this->client()->post(
                    $this->getUrl(),
                    [
                        'json' => [
                            'siteNumber' => $siteNumber,
                            'currentValue' => $currentValue,
                            'proposedValue' => $proposedValue,
                            'appID' => getenv('APP_ID'),
                            'status' => $status
                        ],
                    ]
            );

            $this->parseResponse($siteNumber, $currentValue, $proposedValue, $response);
        } catch (RequestException $ex) {
            $statusCode = $ex->getResponse() ? $ex->getResponse()->getStatusCode() : null;
            $responseBody = $ex->getResponse() ? $ex->getResponse()->getBody()->getContents() : null;

            throw new PervasiveApiRequestException($this->getBaseUri() . $this->getUrl(), $statusCode, $responseBody, $ex);
        }
    }

    private function parseResponse($siteNumber, $currentValue, $proposedValue, $response)
    {
        if ($response->getStatusCode() === 503) {
// FIXME defend against invalid json!!!
            $responseBody = json_decode($response->getBody());
            $statusCode = json_decode($response->getStatusCode());
            foreach ($responseBody->messages as $message) {
                if (in_array($message->messageCode, [301, 302, 401, 402, 501, 502])) {
                    throw new PervasiveApiAvailableFlagException($siteNumber, $currentValue, $proposedValue, $this->getBaseUri() . $this->getUrl(), $statusCode, $responseBody);
                }
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
            'base_uri' => $this->getBaseUri(),
            'headers' => [
                'Authorization' => $this->getToken()
            ]
        ]);
    }
}
