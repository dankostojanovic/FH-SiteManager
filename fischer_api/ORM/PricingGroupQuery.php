<?php

use Base\PricingGroupQuery as BasePricingGroupQuery;

/**
 * Skeleton subclass for performing query and update operations on the 'pricing_group' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class PricingGroupQuery extends BasePricingGroupQuery
{
    // Parses all Pricing Group filters
    public function addPricingGroupFilters($filter = [])
    {
        foreach ($filter as $field => $value) {
            $this->where('PricingGroup.' . $field . ' ' . $value['operator'] . ' ?', $value['value']);
        }

        return $this;
    }
}
