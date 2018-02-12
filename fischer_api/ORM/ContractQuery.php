<?php

use Base\ContractQuery as BaseContractQuery;

/**
 * Skeleton subclass for performing query and update operations on the 'contract' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class ContractQuery extends BaseContractQuery
{
    // Parses all Contract filters
    public function addContractFilters($filter = [])
    {
        foreach ($filter as $field => $value) {
            $this->where('Contract.' . $field . ' ' . $value['operator'] . ' ?', $value['value']);
        }

        return $this;
    }
}
