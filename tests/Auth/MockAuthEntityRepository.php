<?php


namespace Logistio\Symmetry\Test\Auth;


use Logistio\Symmetry\Auth\AuthEntityRepository;

/**
 * MockAuthEntityRepository
 * ----
 * Mock implementation of AuthEntityRepository
 *
 * @package Logistio\Symmetry\Test\Auth
 */
class MockAuthEntityRepository implements AuthEntityRepository
{

    /**
     * OauthClients, indexed by their ids.
     *
     * @var array
     */
    private $oauthClientById = [];

    /**
     * Users, indexed by their email address.
     *
     * @var array
     */
    private $userByEmail = [];

    // ------------------------------------------------------------------------------

    public function addOauthClient($oauthClient)
    {
        $this->oauthClientById[$oauthClient->id] = $oauthClient;
    }

    public function addUser($user, $emailField = null)
    {
        if (is_null($emailField)) {
            $emailField = 'email';
        }

        $this->userByEmail[$user->$emailField] = $user;
    }

    // ----------------------------------------------------

    public function findOauthClientOrFail($oauthClientId)
    {
        // TODO: Implement findOauthClientOrFail() method.
    }

    public function findUserByEmail($emailAddress)
    {
        // TODO: Implement findUserByEmail() method.
    }


}