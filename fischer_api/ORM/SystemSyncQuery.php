<?php

use Base\SystemSyncQuery as BaseSystemSyncQuery;

/**
 * Skeleton subclass for performing query and update operations on the 'system_sync' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class SystemSyncQuery extends BaseSystemSyncQuery
{
    // Parses all System Sync filters
    public function addSystemSyncFilters($filter = [])
    {
        foreach ($filter as $field => $value) {
            $this->where('SystemSync.' . $field . ' ' . $value['operator'] . ' ?', $value['value']);
        }

        return $this;
    }

}
