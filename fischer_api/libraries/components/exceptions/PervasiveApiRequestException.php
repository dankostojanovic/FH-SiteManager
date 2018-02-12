<?php

namespace FischerHomes\Components\Exceptions;

class PervasiveApiRequestException extends PervasiveApiException
{
    public function __construct($url, $statusCode, $responseBody, $innerException = null)
    {
        parent::__construct($url, $statusCode, $responseBody, $innerException);
    }

    public function getDefaultMessage()
    {
        return 'System cannot update available flag - request issue. URL: ' . $this->url . " | Status Code: " . $this->statusCode . " | Response Body: " . $this->responseBody;
    }

    public function getExceptionCode()
    {
        return 1401;
    }
}
