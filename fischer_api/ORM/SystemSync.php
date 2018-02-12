<?php

use Base\SystemSync as BaseSystemSync;

/**
 * Skeleton subclass for representing a row from the 'system_sync' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class SystemSync extends BaseSystemSync
{
	public function __construct($id = null){
        parent::__construct();
		if(!empty ($id) && is_numeric($id)){
			$this->loadByPK($id);
		}
	}
	public function loadByPK($id = null){
        $ss = \SystemSyncQuery::create()->findPK($id);
		if(!empty($ss)){
			$this->fromArray($ss->toArray());
			$this->setNew(false);
		}
	}
}
