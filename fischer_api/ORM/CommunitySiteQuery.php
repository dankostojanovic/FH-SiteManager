<?php

use Base\CommunitySiteQuery as BaseCommunitySiteQuery;

/**
 * Skeleton subclass for performing query and update operations on the 'community_site' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class CommunitySiteQuery extends BaseCommunitySiteQuery
{

    // FIXME
    public function addSiteInventory($withSiteInventory = false)
    {
        if ($withSiteInventory > 0) {
            // FIXME Make this fetch all or selected fields
            return $this->join('CommunitySite.CommunitySiteInventory')
                ->withColumn('CommunitySiteInventory.PurchaseDate', 'PurchaseDate')
                ->withColumn('CommunitySiteInventory.ReleasedForSale', 'ReleasedForSale')
                ->withColumn('CommunitySiteInventory.SiteCost', 'SiteCost')
                ->withColumn('CommunitySiteInventory.SiteRating', 'SiteRating')
                ->withColumn('CommunitySiteInventory.vendor_id', 'VendorId')
                ->withColumn('CommunitySiteInventory.CheckNumber', 'CheckNumber')
                ->withColumn('CommunitySiteInventory.LoptRecordId', 'LoptRecordId')
                ->withColumn('CommunitySiteInventory.Credit', 'Credit')
                ->withColumn('CommunitySiteInventory.ViewPremium', 'ViewPremium')
                ->withColumn('CommunitySiteInventory.GaragePremium', 'GaragePremium')
                ->withColumn('CommunitySiteInventory.WalkOutPremium', 'WalkOutPremium')
                ->withColumn('CommunitySiteInventory.Fee1', 'Fee1')
                ->withColumn('CommunitySiteInventory.Fee1AppSca', 'Fee1AppSca')
                ->withColumn('CommunitySiteInventory.Fee2', 'Fee2')
                ->withColumn('CommunitySiteInventory.Fee2AppSca', 'Fee2AppSca')
                ->withColumn('CommunitySiteInventory.Fee3', 'Fee3')
                ->withColumn('CommunitySiteInventory.Fee3AppSca', 'Fee3AppSca')
                ->withColumn('CommunitySiteInventory.Fee4', 'Fee4')
                ->withColumn('CommunitySiteInventory.Fee4AppSca', 'Fee4AppSca');
        } else {
            return $this;
        }
    }

    // Adds related data
    public function addCommunity($withCommunity = false, $columns = [])
    {
        if ($withCommunity) {
            $this->join('CommunitySite.Community');
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
    public function addFischerSection($withFischerSection = false, $columns = [])
    {
        if ($withFischerSection) {
            $this->leftJoin('CommunitySite.CommunitySection');
            if(count($columns) > 0) {
                foreach ($columns as $column) {
                    $this->withColumn('CommunitySection.' . $column, 'CommunitySection' . $column);
                }
            } else {
                $fischerSectionTable = \Map\CommunitySectionTableMap::getTableMap();
                $fischerSectionColumns = $fischerSectionTable->getColumns();
                foreach ($fischerSectionColumns as $column) {
                    $this->withColumn('CommunitySection.' . $column->getName(), 'CommunitySection' . $column->getPhpName());
                }
            }
        }

        return $this;
    }

    // Adds related data
    public function addLegalSection($withLegalSection = false, $columns = [])
    {
        if ($withLegalSection) {
            $this->leftJoin('CommunitySite.CommunitySectionLegal');
            if(count($columns) > 0) {
                foreach ($columns as $column) {
                    $this->withColumn('CommunitySectionLegal.' . $column, 'CommunitySectionLegal' . $column);
                }
            } else {
                $legalSectionTable = \Map\CommunitySectionLegalTableMap::getTableMap();
                $legalSectionColumns = $legalSectionTable->getColumns();
                foreach ($legalSectionColumns as $column) {
                    $this->withColumn('CommunitySectionLegal.' . $column->getName(), 'CommunitySectionLegal' . $column->getPhpName());
                }
            }
        }

        return $this;
    }

    // Adds related data
    public function addSpecLevel($withSpecLevel = false, $columns = [])
    {
        if ($withSpecLevel) {
            $this->leftJoin('CommunitySite.SpecLevels');
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

    // Adds related data
    public function addPricingGroup($withPricingGroup = false, $columns = [])
    {
        if ($withPricingGroup) {
            $this->leftJoin('CommunitySite.PricingGroup');
            if(count($columns) > 0) {
                foreach ($columns as $column) {
                    $this->withColumn('PricingGroup.' . $column, 'PricingGroup' . $column);
                }
            } else {
                $pricingGroupTable = \Map\PricingGroupTableMap::getTableMap();
                $pricingGroupColumns = $pricingGroupTable->getColumns();
                foreach ($pricingGroupColumns as $column) {
                    $this->withColumn('PricingGroup.' . $column->getName(), 'PricingGroup' . $column->getPhpName());
                }
            }
        }

        return $this;
    }

    // Adds related data
    public function addContract($withContract = false, $columns = [])
    {
        if ($withContract) {
            $this->leftJoin('CommunitySite.Contract');
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

    // Adds related data
    public function addSiteHoldReason($withSiteHoldReason = false, $columns = [])
    {
        if ($withSiteHoldReason) {
            $this->leftJoin('CommunitySite.SiteholdReason');
            if(count($columns) > 0) {
                foreach ($columns as $column) {
                    $this->withColumn('SiteholdReason.' . $column, 'SiteHoldReason' . $column);
                }
            } else {
                $siteholdReasonTable = \Map\SiteholdReasonTableMap::getTableMap();
                $siteholdReasonColumns = $siteholdReasonTable->getColumns();
                foreach ($siteholdReasonColumns as $column) {
                    $this->withColumn('SiteholdReason.' . $column->getName(), 'SiteHoldReason' . $column->getPhpName());
                }
            }
        }

        return $this;
    }

    // Parses all Site filters
    public function addSiteFilters($filter = [])
    {
        foreach ($filter as $field => $value) {
            $this->where('CommunitySite.' . $field . ' ' . $value['operator'] . ' ?', $value['value']);
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

    // Parses all Spec Level filters
    public function addSpecLevelFilters($filter = [])
    {
        foreach ($filter as $field => $value) {
            $this->where('SpecLevels.' . $field . ' ' . $value['operator'] . ' ?', $value['value']);
        }

        return $this;
    }

    // Parses all Pricing Group filters
    public function addPricingGroupFilters($filter = [])
    {
        foreach ($filter as $field => $value) {
            $this->where('PricingGroup.' . $field . ' ' . $value['operator'] . ' ?', $value['value']);
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

    // Parses all Site Hold filters
    public function addSiteHoldFilters($filter = [])
    {
        foreach ($filter as $field => $value) {
            $this->where('CommunitySite.' . $field . ' ' . $value['operator'] . ' ?', $value['value']);
        }

        return $this;
    }

    // Parses all Sitehold Reason filters
    public function addSiteHoldReasonFilters($filter = [])
    {
        foreach ($filter as $field => $value) {
            $this->where('SiteholdReason.' . $field . ' ' . $value['operator'] . ' ?', $value['value']);
        }

        return $this;
    }

}
