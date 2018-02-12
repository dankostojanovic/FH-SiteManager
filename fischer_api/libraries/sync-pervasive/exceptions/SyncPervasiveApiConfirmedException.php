<?php

namespace FischerHomes\SyncPervasive\Exceptions;

class SyncPervasiveConfirmedException extends SyncPervasiveSaveException
{
    protected $insertedId;

    public function __construct($insertedId, $innerException = null)
    {
        $this->insertedId = $insertedId;

        parent::__construct($innerException);
    }

    public function getExtraData()
    {
        return [
            'Inserted Id: ' => $this->insertedId
        ];
    }

    public function getDefaultMessage()
    {
        return 'Update Pervasive failed, system_sync record ' . $this->insertedId;
    }

    public function getExceptionCode()
    {
        return 1502;
    }
}
