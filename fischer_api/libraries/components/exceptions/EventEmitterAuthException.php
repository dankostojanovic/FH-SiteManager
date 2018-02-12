<?php

namespace FischerHomes\Components\Exceptions;

class EventEmitterAuthException extends EventEmitterException
{
    public function __construct($url, $statusCode, $response_body, $innerException = null)
    {
        parent::__construct($url, $statusCode, $response_body, $innerException);
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
        return 'Unable to emit event ' . $this->event_id;
    }

    public function getExceptionCode()
    {
        return 1405;
    }
}
