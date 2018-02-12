<?php

namespace FischerHomes\Components\Exceptions;

abstract class PervasiveApiException extends \Exception
{
    protected $url;
    protected $statusCode;
    protected $response;

    abstract protected function getDefaultMessage();
    abstract protected function getExceptionCode();

    public function __construct($url, $statusCode, $responseBody, $innerException = null)
    {
        $this->url = $url;
        $this->statusCode = $statusCode;
        $this->responseBody = $responseBody;

        parent::__construct($innerException);
    }

    public function getExtraData()
    {
        return [
            'Request URL' => $this->url,
            'Status code' => $this->statusCode,
            'Response body' => $this->responseBody
        ];
    }
}
