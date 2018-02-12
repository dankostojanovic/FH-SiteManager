<?php

use Base\CommunityQuery as BaseCommunityQuery;

/**
 * Skeleton subclass for performing query and update operations on the 'community' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class CommunityQuery extends BaseCommunityQuery
{
    public function addActiveFilter($active = 0)
    {
        if ($active > 0)
        {
            return $this->filterByActive($active);
        } else {
            return $this;
        }
    }

    public function addDivision($withDivision = false) {
        if ($withDivision > 0)
        {
            return $this->join('Community.Division')
            ->withColumn('Division.DivisionName', 'DivisionName')
            ->withColumn('Division.Division', 'Division');
        } else {
            return $this;
        }
    }

    public function addBudgetNeighborhood($withBudgetNeighborhood = false) {
        if ($withBudgetNeighborhood > 0)
        {
            return $this->leftJoin('Community.BdgtNeighborhood')
            ->withColumn('BdgtNeighborhood.BdgtNeighRecordId', 'BudgetNeighborhoodId')
            ->withColumn('BdgtNeighborhood.BdgtNeighName', 'BudgetNeighborhoodName')
            ->withColumn('BdgtNeighborhood.InternalDeveloper', 'InternalDeveloper');
        } else {
            return $this;
        }
    }
}
