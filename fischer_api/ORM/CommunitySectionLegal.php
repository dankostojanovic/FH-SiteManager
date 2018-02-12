<?php

use Base\CommunitySectionLegal as BaseCommunitySectionLegal;

/**
 * Skeleton subclass for representing a row from the 'community_section_legal' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class CommunitySectionLegal extends BaseCommunitySectionLegal
{

	public function __construct($id = null){
        parent::__construct();
		if(!empty ($id) && is_numeric($id)){
			$this->loadByPK($id);
		}
	}

	public function loadByPK($id = null){
        $csl = \CommunitySectionLegalQuery::create()->findPK($id);
		if(!empty($csl)){
			$this->fromArray($csl->toArray());
			$this->setNew(false);
		}
	}

    public function loadMortgages()
    {
        $arrData = [];
        $mortgages = $this->getMortgages();
        foreach ($mortgages->toArray() as $value) {
            if ($value['IsDeleted'] === false) {
                $arrData[] = $value;
            }
        }
        return $arrData;
    }
}
