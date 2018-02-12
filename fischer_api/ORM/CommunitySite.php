<?php

use Base\CommunitySite as BaseCommunitySite;

/**
 * Skeleton subclass for representing a row from the 'community_site' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class CommunitySite extends BaseCommunitySite
{
	public function __construct($id = null){
        parent::__construct();
		if(!empty ($id) && is_numeric($id)){
			$this->loadByPK($id);
		}
	}

	public function loadByPK($id = null){
		$cs = \CommunitySiteQuery::create()->findPK($id);
		if(!empty($cs)){
			$this->fromArray($cs->toArray());
			$this->setNew(false);
		}
	}

    public function loadSiteMortgageAmmendments()
    {
        return $this->getSiteMortgageAmmendments()->toArray();
    }
}
