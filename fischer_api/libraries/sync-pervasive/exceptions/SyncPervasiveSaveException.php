<?php

namespace FischerHomes\SyncPervasive\Exceptions;

class SyncPervasiveSaveException extends SyncPervasiveException
{
    public function __construct($innerException = null)
    {
        parent::__construct($innerException);
    }

    public function getDefaultMessage()
    {
        return 'Save to SystemSync failed.';
    }

    public function getExceptionCode()
    {
        return 1501;
    }
}
