<?php

namespace FischerHomes\Components;

/**
 * Query Sapphire Db Component
 *
 * @package app\components
 */
class QuerySapphireDbComponent
{
    /**
     * Get user details from the database
     *
     * @return array An array of Sapphire user details
     */
    // TODO
    public function getUserDetails($id)
    {
        $user = ['id' => $id, 'username' => 'dstojanovic'];
        return $user;
    }
}
