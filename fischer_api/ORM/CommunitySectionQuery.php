<?php

use Base\CommunitySectionQuery as BaseCommunitySectionQuery;

/**
 * Skeleton subclass for performing query and update operations on the 'community_section' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class CommunitySectionQuery extends BaseCommunitySectionQuery
{
    // Adds related data
    public function addCommunity($withCommunity = false, $columns = [])
    {
        if ($withCommunity) {
            $this->join('CommunitySection.Community');
            if(count($columns) > 0) {
                foreach ($columns as $column) {
                    $this->withColumn('Community.' . $column, 'Community' . $column);
                }
            } else {
                $communityTable = \Map\CommunityTableMap::getTableMap();
                $communityColumns = $communityTable->getColumns();
                foreach ($communityColumns as $column) {
                    $this->withColumn('Community.' . $column->getName(), 'Community' . $column->getPhpName());
                }
            }
        }

        return $this;
    }

    // Adds related data
    public function addSpecLevel($withSpecLevel = false, $columns = [])
    {
        if ($withSpecLevel) {
            $this->leftJoin('CommunitySection.SpecLevels');
            if(count($columns) > 0) {
                foreach ($columns as $column) {
                    $this->withColumn('SpecLevels.' . $column, 'SpecLevels' . $column);
                }
            } else {
                $specLevelTable = \Map\SpecLevelsTableMap::getTableMap();
                $specLevelColumns = $specLevelTable->getColumns();
                foreach ($specLevelColumns as $column) {
                    $this->withColumn('SpecLevels.' . $column->getName(), 'SpecLevels' . $column->getPhpName());
                }
            }
        }

        return $this;
    }

    // Parses all Fischer Section filters
    public function addFischerSectionFilters($filter = [])
    {
        foreach ($filter as $field => $value) {
            $this->where('CommunitySection.' . $field . ' ' . $value['operator'] . ' ?', $value['value']);
        }

        return $this;
    }

    // Parses all Community filters
    public function addCommunityFilters($filter = [])
    {
        foreach ($filter as $field => $value) {
            $this->where('Community.' . $field . ' ' . $value['operator'] . ' ?', $value['value']);
        }

        return $this;
    }

    // Parses all Spec Level filters
    public function addSpecLevelFilters($filter = [])
    {
        foreach ($filter as $field => $value) {
            $this->where('SpecLevels.' . $field . ' ' . $value['operator'] . ' ?', $value['value']);
        }

        return $this;
    }

}
