<?php

use Base\Contract as BaseContract;

/**
 * Skeleton subclass for representing a row from the 'contract' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class Contract extends BaseContract
{
	public function __construct($id = null){
        parent::__construct();
		if(!empty ($id) && is_numeric($id)){
			$this->loadByPK($id);
		}
	}

	public function loadByPK($id = null){
        $c = \ContractQuery::create()->findPK($id);
		if(!empty($c)){
			$this->fromArray($c->toArray());
			$this->setNew(false);
		}
	}

    public function loadSites($fields = [])
    {
        $arrData = [];
        $communitySites = $this->getCommunitySites();
        foreach ($communitySites->toArray() as $key => $value) {
            if ($value['IsDeleted'] === false) {
                if (count($fields) > 0) {
                    $arrTemp = [];
                    foreach ($fields as $field) {
                        $arrTemp[$field] = $value[$field];
                    }
                    $arrData[] = $arrTemp;
                } else {
                    $arrData[] = $value;
                }
            }
        }
        return $arrData;
    }

    public function loadEscalators($fields = [])
    {
        $arrData = [];
        $escalators = $this->getEscalators();
        foreach ($escalators->toArray() as $value) {
            if ($value['IsDeleted'] === false) {
                if (count($fields) > 0) {
                    $arrTemp = [];
                    foreach ($fields as $field) {
                        $arrTemp[$field] = $value[$field];
                    }
                    $arrData[] = $arrTemp;
                } else {
                    $arrData[] = $value;
                }
            }
        }
        return $arrData;
    }
}
