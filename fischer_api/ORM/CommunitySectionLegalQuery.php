<?php

use Base\CommunitySectionLegalQuery as BaseCommunitySectionLegalQuery;

/**
 * Skeleton subclass for performing query and update operations on the 'community_section_legal' table.
 *
 *
 *
 * Add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class CommunitySectionLegalQuery extends BaseCommunitySectionLegalQuery
{
    // Adds related data
    public function addCommunity($withCommunity = false, $columns = [])
    {
        if ($withCommunity) {
            $this->join('CommunitySectionLegal.Community');
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

    // Parses all Legal Section filters
    public function addLegalSectionFilters($filter = [])
    {
        foreach ($filter as $field => $value) {
            $this->where('CommunitySectionLegal.' . $field . ' ' . $value['operator'] . ' ?', $value['value']);
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
}
