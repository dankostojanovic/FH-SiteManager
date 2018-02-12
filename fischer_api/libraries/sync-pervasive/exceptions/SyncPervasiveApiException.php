<?php

namespace FischerHomes\SyncPervasive\Exceptions;

class SyncPervasiveApiException extends SyncPervasiveException
{
    protected $url;
    protected $statusCode;
    protected $response;

    public function __construct($url, $statusCode, $response, $innerException = null)
    {
        $this->url = $url;
        $this->statusCode = $statusCode;
        $this->response = $response;

        parent::__construct($innerException);
    }

    public function getExtraData()
    {
        return [
            'Url: ' => $this->url,
            'Status code: ' => $this->statusCode,
            'Response: ' => $this->response
        ];
    }

    public function getDefaultMessage()
    {
        return 'Update Pervasive API failed, url: ' . $this->url . ' Status code: ' . $this->statusCode . ' Response: ' . $this->response;
    }

    public function getExceptionCode()
    {
        return 1503;
    }
}
