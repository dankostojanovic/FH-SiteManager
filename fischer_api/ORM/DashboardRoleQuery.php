<?php

use Base\DashboardRoleQuery as BaseDashboardRoleQuery;

/**
 * Skeleton subclass for performing query and update operations on the 'dashboard_role' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class DashboardRoleQuery extends BaseDashboardRoleQuery
{
    // Adds related data
    public function addDashboard($withDashboard = false, $columns = [])
    {
        if ($withDashboard) {
            $this->join('DashboardRole.Dashboard');
            if(count($columns) > 0) {
                foreach ($columns as $column) {
                    $this->withColumn('Dashboard.' . $column, 'Dashboard' . $column);
                }
            } else {
                $dashboardTable = \Map\DashboardTableMap::getTableMap();
                $dashboardColumns = $dashboardTable->getColumns();
                foreach ($dashboardColumns as $column) {
                    $this->withColumn('Dashboard.' . $column->getName(), 'Dashboard' . $column->getPhpName());
                }
            }
        }

        return $this;
    }
}
