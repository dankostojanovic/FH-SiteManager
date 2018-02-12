<?php

use Base\EscalatorConditionQuery as BaseEscalatorConditionQuery;

/**
 * Skeleton subclass for performing query and update operations on the 'escalator_condition' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class EscalatorConditionQuery extends BaseEscalatorConditionQuery
{
    // Adds related data
    public function addEscalator($withCommunity = false, $columns = [])
    {
        if ($withCommunity) {
            $this->join('EscalatorCondition.Escalator');
            if(count($columns) > 0) {
                foreach ($columns as $column) {
                    $this->withColumn('Escalator.' . $column, 'Escalator' . $column);
                }
            } else {
                $escalatorTable = \Map\EscalatorTableMap::getTableMap();
                $escalatorColumns = $escalatorTable->getColumns();
                foreach ($escalatorColumns as $column) {
                    $this->withColumn('Escalator.' . $column->getName(), 'Escalator' . $column->getPhpName());
                }
            }
        }

        return $this;
    }

    // Parses all Escalator Condition filters
    public function addEscalatorConditionFilters($filter = [])
    {
        foreach ($filter as $field => $value) {
            $this->where('EscalatorCondition.' . $field . ' ' . $value['operator'] . ' ?', $value['value']);
        }

        return $this;
    }

    // Parses all Escalator filters
    public function addEscalatorFilters($filter = [])
    {
        foreach ($filter as $field => $value) {
            $this->where('Escalator.' . $field . ' ' . $value['operator'] . ' ?', $value['value']);
        }

        return $this;
    }
}
