<?php

namespace FischerHomes\Components\Exceptions;

abstract class EventEmitterException extends \Exception
{
    protected $url;
    protected $statusCode;
    protected $response_body;

    public function __construct($url, $statusCode, $response_body, $innerException = null)
    {
        $this->url = $url;
        $this->statusCode = $statusCode;
        $this->response_body = $response_body;

        parent::__construct($innerException);
    }

    public function getExtraData()
    {
        return [
            'Request URL: ' => $this->url,
            'Status code: ' => $this->statusCode,
            'Response body: ' => $this->response_body
        ];
    }

    public function getDefaultMessage()
    {
        return 'Unable to contact Events API. Please try again later or contact our technical support.';
    }

    public function setMessage($message = ''){
        $this->message = $message;
    }

    public function getExceptionCode()
    {
        return 1400;
    }
}
