<?php

use Base\Escalator as BaseEscalator;

/**
 * Skeleton subclass for representing a row from the 'escalator' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class Escalator extends BaseEscalator
{
	public function __construct($id = null){
        parent::__construct();
		if(!empty ($id) && is_numeric($id)){
			$this->loadByPK($id);
		}
	}

	public function loadByPK($id = null){
        $c = \EscalatorQuery::create()->findPK($id);
		if(!empty($c)){
			$this->fromArray($c->toArray());
			$this->setNew(false);
		}
	}

    public function loadEscalatorConditions($fields = [])
    {
        $arrData = [];
        $escalatorConditions = $this->getEscalatorConditions();
        foreach ($escalatorConditions->toArray() as $key => $value) {
            if ($value['IsDeleted'] === false) {
                if (count($fields) > 0) {
                    $arrTemp = [];
                    foreach ($fields as $field) {
                        // Hack to allow for MySQL JSON field type
                        if ($field == 'TargetDate' || $field == 'TargetLots') {
                            $arrTemp[$field] = json_decode($value[$field]);
                        } else {
                            $arrTemp[$field] = $value[$field];
                        }
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
