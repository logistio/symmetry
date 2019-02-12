<?php


namespace Logistio\Symmetry\Auth\Passport;


use Laravel\Passport\Client as PassportClient;
use Laravel\Passport\Bridge\Client as ClientEntity;
use League\OAuth2\Server\Entities\ClientEntityInterface;


/**
 * OauthClient
 * ----
 * Adds functions to the Eloquent model of the "oauth_clients" table,
 * which is used by Passport.
 *
 * ----
 * @property int $id
 * @property int|null $user_id
 * @property string $name
 * @property string $secret
 * @property string $redirect
 * @property bool $personal_access_client
 * @property bool $password_client
 * @property bool $revoked
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * ----
 */
trait PassportOauthClient
{

    /**
     * Converts to the native Passport Client object.
     *
     * @return PassportClient
     */
    public function toPassportClient()
    {
        return PassportClient::findOrFail($this->id);
    }

    /**
     * @return ClientEntityInterface
     */
    public function toClientEntity()
    {
        return new ClientEntity($this->id, $this->name, $this->redirect);
    }


}