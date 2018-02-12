<?php

use Base\MortgageQuery as BaseMortgageQuery;

/**
 * Skeleton subclass for performing query and update operations on the 'mortgage' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class MortgageQuery extends BaseMortgageQuery
{
    // Adds related data
    public function addLegalSection($withLegalSection = false, $columns = [])
    {
        if ($withLegalSection) {
            $this->join('Mortgage.CommunitySectionLegal');
            if(count($columns) > 0) {
                foreach ($columns as $column) {
                    $this->withColumn('CommunitySectionLegal.' . $column, 'CommunitySectionLegal' . $column);
                }
            } else {
                $communitySectionLegalTable = \Map\CommunitySectionLegalTableMap::getTableMap();
                $communitySectionLegalColumns = $communitySectionLegalTable->getColumns();
                foreach ($communitySectionLegalColumns as $column) {
                    $this->withColumn('CommunitySectionLegal.' . $column->getName(), 'CommunitySectionLegal' . $column->getPhpName());
                }
            }
        }

        return $this;
    }

    // Parses all Mortgage filters
    public function addMortgageFilters($filter = [])
    {
        foreach ($filter as $field => $value) {
            $this->where('Mortgage.' . $field . ' ' . $value['operator'] . ' ?', $value['value']);
        }

        return $this;
    }

    // Parses all Legal Section filters
    public function addLegalSectionFilters($filter = [])
    {
        foreach ($filter as $field => $value) {
            $this->where('CommunitySectionLegal.' . $field . ' ' . $value['operator'] . ' ?', $value['value']);
        }

        return $this;
    }
}
