<?php

use Base\Configuration as BaseConfiguration;

/**
 * Skeleton subclass for representing a row from the 'configuration' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class Configuration extends BaseConfiguration
{
	public function __construct($id = null){
        parent::__construct();
		if(!empty ($id) && is_numeric($id)){
			$this->loadByPK($id);
		}
	}

	public function loadByPK($id = null){
		$cs = \ConfigurationQuery::create()->findPK($id);
		if(!empty($cs)){
			$this->fromArray($cs->toArray());
			$this->setNew(false);
		}
	}
}