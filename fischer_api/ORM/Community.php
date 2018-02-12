<?php

use Base\Community as BaseCommunity;

/**
 * Skeleton subclass for representing a row from the 'community' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class Community extends BaseCommunity
{
	public function __construct($id = null){
        parent::__construct();
		if(!empty ($id) && is_numeric($id)){
			$this->loadByPK($id);
		}
	}

	public function loadByPK($id = null){
        $c = \CommunityQuery::create()->findPK($id);
		if(!empty($c)){
			$this->fromArray($c->toArray());
			$this->setNew(false);
		}
	}

    public function loadCommunitySites()
    {
        $arrData = [];
        $communitySites = $this->getCommunitySites();
        foreach ($communitySites->toArray() as $value) {
            if ($value['IsDeleted'] === false) {
                $arrData[] = $value;
            }
        }
        return $arrData;
    }

    public function loadCommunitySections()
    {
        $arrData = [];
        $communitySections = $this->getCommunitySections();
        foreach ($communitySections->toArray() as $value) {
            if ($value['IsDeleted'] === false) {
                $arrData[] = $value;
            }
        }
        return $arrData;
    }

    public function loadCommunitySectionLegals()
    {
        $arrData = [];
        $communitySectionLegals = $this->getCommunitySectionLegals();
        foreach ($communitySectionLegals->toArray() as $value) {
            if ($value['IsDeleted'] === false) {
                $arrData[] = $value;
            }
        }
        return $arrData;
    }

}
