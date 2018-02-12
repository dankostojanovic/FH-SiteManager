<?php

use Base\VendorQuery as BaseVendorQuery;

/**
 * Skeleton subclass for performing query and update operations on the 'vendor' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class VendorQuery extends BaseVendorQuery
{
    // Parses all Site filters
    public function addVendorFilters($filter = [])
    {
        foreach ($filter as $field => $value) {
            $this->where('Vendor.' . $field . ' ' . $value['operator'] . ' ?', $value['value']);
        }

        return $this;
    }
}
