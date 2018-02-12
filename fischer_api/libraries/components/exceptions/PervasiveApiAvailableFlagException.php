<?php

namespace FischerHomes\Components\Exceptions;

class PervasiveApiAvailableFlagException extends PervasiveApiException
{
    protected $siteNumber;

    protected $availableFlag;

    public function __construct($siteNumber, $currentValue, $proposedValue, $url, $statusCode, $responseBody, $innerException = null)
    {
        $this->siteNumber = $siteNumber;

        $this->currentValue = $currentValue;

        $this->proposedValue = $proposedValue;

        parent::__construct($url, $statusCode, $responseBody, $innerException);
    }

    public function getExtraData()
    {
        return [
            'Site Number' => $this->siteNumber,
            'Current value' => $this->currentValue,
            'Proposed value' => $this->proposedValue,
            'Request URL' => $this->url,
            'Status code' => $this->statusCode,
            'Response body' => $this->responseBody
        ];
    }

    public function getDefaultMessage()
    {
        return 'System cannot update available flag - data issue.';
    }

    public function getExceptionCode()
    {
        return 1402;
    }
}
