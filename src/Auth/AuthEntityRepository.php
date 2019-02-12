<?php


namespace Logistio\Symmetry\Auth;


/**
 * AuthEntityRepository
 * ----
 * Provides entities required for performing auth checks.
 *
 * Entities:
 *  - User
 *  - OauthClient
 *
 *
 * @package Logistio\Symmetry\Auth
 */
interface AuthEntityRepository
{

    /**
     * Find an entry from the "oauth_client" table by its "id" value.
     *
     * @param $oauthClientId
     * @return mixed
     */
    public function findOauthClientOrFail($oauthClientId);

    /**
     * Find a User by email address.
     * The User must have an "id" property.
     *
     * @param $emailAddress
     * @return mixed
     */
    public function findUserByEmail($emailAddress);


}