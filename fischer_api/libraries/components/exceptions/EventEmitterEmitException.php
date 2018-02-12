<?php

namespace FischerHomes\Components\Exceptions;

class EventEmitterEmitException extends EventEmitterException
{
    protected $event_id;

    protected $event_body;

    public function __construct($event_id, $event_body, $url, $statusCode, $response_body, $innerException = null)
    {
        $this->event_id = $event_id;

        $this->event_body = $event_body;

        parent::__construct($url, $statusCode, $response_body, $innerException);
    }

    public function getExtraData()
    {
        return [
            'Event ID: ' => $this->event_id,
            'Event body: ' => $this->event_body,
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
        return 1404;
    }
}
