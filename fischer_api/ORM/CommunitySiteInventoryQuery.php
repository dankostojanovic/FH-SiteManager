<?php

use Base\CommunitySiteInventoryQuery as BaseCommunitySiteInventoryQuery;

/**
 * Skeleton subclass for performing query and update operations on the 'community_site_inventory' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class CommunitySiteInventoryQuery extends BaseCommunitySiteInventoryQuery
{
    public function addPurchaseDateFilter($filter = [])
    {
        if (isset($filter['operator']) && $filter['operator'] == ' ISNULL ') {
            return $this
                    ->where('purchase_date is null');
        } elseif(count($filter) > 0) {
            return $this
                    ->where('purchase_date' . $filter['operator'] . ' ' . $filter['value']);
        } else {
            return $this;
        }
    }
}
