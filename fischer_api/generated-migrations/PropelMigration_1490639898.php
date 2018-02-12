<?php

use Propel\Generator\Manager\MigrationManager;

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1490639898.
 * Generated on 2017-03-27 18:38:18 by ubuntu
 */
class PropelMigration_1490639898
{
    public $comment = '';

    public function preUp(MigrationManager $manager)
    {
        // add the pre-migration code here
    }

    public function postUp(MigrationManager $manager)
    {
        // add the post-migration code here
    }

    public function preDown(MigrationManager $manager)
    {
        // add the pre-migration code here
    }

    public function postDown(MigrationManager $manager)
    {
        // add the post-migration code here
    }

    /**
     * Get the SQL statements for the Up migration
     *
     * @return array list of the SQL strings to execute for the Up migration
     *               the keys being the datasources
     */
    public function getUpSQL()
    {
        return array (
  'default' => '
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

ALTER TABLE `community_section`

  DROP `f_min_lot_size_front_entr`,

  DROP `f_min_lot_size_side_entry`,

  DROP `f_min_lot_size_rear_entry`,

  DROP `f_comments`,

  DROP `f_tfg_zoning_juris_dicti`,

  DROP `f_tfg_zoning_classifica`,

  DROP `f_tfg_front_yard_min`,

  DROP `f_tfg_side_yard_minimum`,

  DROP `f_tfg_combined_side_mini`,

  DROP `f_tfg_rear_yard_minimum`,

  DROP `f_tfg_sideon_corner_mini`,

  DROP `f_width_at_setback1`,

  DROP `f_width_at_setback2`,

  DROP `f_tfg_width_at_setback`,

  DROP `f_side_next_to_street1`,

  DROP `f_side_next_to_street2`,

  DROP `f_tfg_side_next_to_street`,

  DROP `f_min_sqft_per_lot`,

  DROP `f_min_sq_ft_ranch`,

  DROP `f_min_sq_ft2_story`,

  DROP `f_min_sq_ft2_story1_maste`;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
    }

    /**
     * Get the SQL statements for the Down migration
     *
     * @return array list of the SQL strings to execute for the Down migration
     *               the keys being the datasources
     */
    public function getDownSQL()
    {
        return array (
  'default' => '
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

ALTER TABLE `community_section`

  ADD `f_min_lot_size_front_entr` INTEGER AFTER `section_name`,

  ADD `f_min_lot_size_side_entry` INTEGER AFTER `f_min_lot_size_front_entr`,

  ADD `f_min_lot_size_rear_entry` INTEGER AFTER `f_min_lot_size_side_entry`,

  ADD `f_comments` TEXT AFTER `f_min_lot_size_rear_entry`,

  ADD `f_tfg_zoning_juris_dicti` VARCHAR(51) AFTER `f_comments`,

  ADD `f_tfg_zoning_classifica` VARCHAR(21) AFTER `f_tfg_zoning_juris_dicti`,

  ADD `f_tfg_front_yard_min` VARCHAR(21) AFTER `f_tfg_zoning_classifica`,

  ADD `f_tfg_side_yard_minimum` VARCHAR(21) AFTER `f_tfg_front_yard_min`,

  ADD `f_tfg_combined_side_mini` VARCHAR(21) AFTER `f_tfg_side_yard_minimum`,

  ADD `f_tfg_rear_yard_minimum` VARCHAR(21) AFTER `f_tfg_combined_side_mini`,

  ADD `f_tfg_sideon_corner_mini` VARCHAR(21) AFTER `f_tfg_rear_yard_minimum`,

  ADD `f_width_at_setback1` VARCHAR(21) AFTER `f_tfg_sideon_corner_mini`,

  ADD `f_width_at_setback2` VARCHAR(21) AFTER `f_width_at_setback1`,

  ADD `f_tfg_width_at_setback` VARCHAR(21) AFTER `f_width_at_setback2`,

  ADD `f_side_next_to_street1` VARCHAR(21) AFTER `f_tfg_width_at_setback`,

  ADD `f_side_next_to_street2` VARCHAR(21) AFTER `f_side_next_to_street1`,

  ADD `f_tfg_side_next_to_street` VARCHAR(21) AFTER `f_side_next_to_street2`,

  ADD `f_min_sqft_per_lot` INTEGER AFTER `f_tfg_side_next_to_street`,

  ADD `f_min_sq_ft_ranch` INTEGER AFTER `f_min_sqft_per_lot`,

  ADD `f_min_sq_ft2_story` INTEGER AFTER `f_min_sq_ft_ranch`,

  ADD `f_min_sq_ft2_story1_maste` INTEGER AFTER `f_min_sq_ft2_story`;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
    }

}