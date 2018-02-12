<?php

use Base\UsersQuery as BaseUsersQuery;

/**
 * Skeleton subclass for performing query and update operations on the 'users' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class UsersQuery extends BaseUsersQuery
{
    // Adds related data
    public function addRole($withRole = false, $columns = [])
    {
        if ($withRole) {
            $this->join('Users.Role');
            if(count($columns) > 0) {
                foreach ($columns as $column) {
                    $this->withColumn('Role.' . $column, 'Role' . $column);
                }
            } else {
                $roleTable = \Map\RoleTableMap::getTableMap();
                $roleColumns = $roleTable->getColumns();
                foreach ($roleColumns as $column) {
                    $this->withColumn('Role.' . $column->getName(), 'Role.' . $column->getPhpName());
                }
            }
        }

        return $this;
    }

    // Parses all Role filters
    public function addRoleFilters($filter = [])
    {
        foreach ($filter as $field => $value) {
            $this->where('Role.' . $field . ' ' . $value['operator'] . ' ?', $value['value']);
        }

        return $this;
    }
}
