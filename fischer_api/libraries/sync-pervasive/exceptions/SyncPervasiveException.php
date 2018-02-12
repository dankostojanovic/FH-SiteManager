<?php

namespace FischerHomes\SyncPervasive\Exceptions;

abstract class SyncPervasiveException extends \Exception
{
    public function __construct($innerException = null)
    {
        parent::__construct($innerException);
    }

    public function getDefaultMessage()
    {
        return 'Unable to complete Pervasive Sync. Please try again later or contact our technical support.';
    }

    public function getExceptionCode()
    {
        return 1500;
    }
}
