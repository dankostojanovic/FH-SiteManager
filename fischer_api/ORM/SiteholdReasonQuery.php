<?php

use Base\SiteholdReasonQuery as BaseSiteholdReasonQuery;

/**
 * Skeleton subclass for performing query and update operations on the 'sitehold_reason' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class SiteholdReasonQuery extends BaseSiteholdReasonQuery
{
	public function __construct($id = null){
        parent::__construct();
		if(!empty ($id) && is_numeric($id)){
			$this->loadByPK($id);
		}
	}
	public function loadByPK($id = null){
        $shr = \SiteholdReasonQuery::create()->findPK($id);
		if(!empty($shr)){
			$this->fromArray($shr->toArray());
			$this->setNew(false);
		}
	}

    // FIXME
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

    // Parses all Sitehold Reason filters
    public function addSiteHoldReasonFilters($filter = [])
    {
        foreach ($filter as $field => $value) {
            $this->where('SiteholdReason.' . $field . ' ' . $value['operator'] . ' ?', $value['value']);
        }

        return $this;
    }

}
