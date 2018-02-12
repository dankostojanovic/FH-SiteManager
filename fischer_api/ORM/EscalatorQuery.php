<?php

use Base\EscalatorQuery as BaseEscalatorQuery;

/**
 * Skeleton subclass for performing query and update operations on the 'escalator' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class EscalatorQuery extends BaseEscalatorQuery
{
    // Adds related data
    public function addContract($withContract = false, $columns = [])
    {
        if ($withContract) {
            $this->join('Escalator.Contract');
            if(count($columns) > 0) {
                foreach ($columns as $column) {
                    $this->withColumn('Contract.' . $column, 'Contract' . $column);
                }
            } else {
                $contractTable = \Map\ContractTableMap::getTableMap();
                $contractColumns = $contractTable->getColumns();
                foreach ($contractColumns as $column) {
                    $this->withColumn('Contract.' . $column->getName(), 'Contract' . $column->getPhpName());
                }
            }
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

    // Parses all Contract filters
    public function addContractFilters($filter = [])
    {
        foreach ($filter as $field => $value) {
            $this->where('Contract.' . $field . ' ' . $value['operator'] . ' ?', $value['value']);
        }

        return $this;
    }
}
