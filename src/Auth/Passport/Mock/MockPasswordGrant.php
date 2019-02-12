<?php
/* Copyright (C) 2018, Logistio Limited. All Rights Reserved. */


/**
 * @noinspection PhpDocSignatureInspection
 */

namespace Logistio\Symmetry\Auth\Passport\Mock;


use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Log;
use Laravel\Passport\Bridge\AccessTokenRepository;
use Laravel\Passport\Client;
use Laravel\Passport\Passport;
use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\UserEntityInterface;
use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\Grant\PasswordGrant;
use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;
use League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface;
use League\OAuth2\Server\Repositories\UserRepositoryInterface;
use League\OAuth2\Server\RequestEvent;
use Psr\Http\Message\ServerRequestInterface;


/**
 * MockPasswordGrant
 * ----
 *
 *
 * @package Logistio\Symmetry\Auth\Passport\Mock
 */
class MockPasswordGrant extends PasswordGrant
{

    private $username = null;

    private $password = null;

    /**
     * @param Application $app
     */
    public function setupDefaultBindings($app)
    {
        $this->setRefreshTokenTTL(Passport::refreshTokensExpireIn());
        $this->setAccessTokenRepository($app->make(AccessTokenRepositoryInterface::class));
    }

    public function setUserCredentials($username, $password)
    {
        $this->username = $username;
        $this->password = $password;
    }

    // ------------------------------------------------------------------------------

    /** {@inheritdoc} */
    public function issueAccessToken(
        \DateInterval $accessTokenTTL,
        ClientEntityInterface $client,
        $userIdentifier,
        array $scopes = []
    )
    {
        // Override to make public.
        return parent::issueAccessToken($accessTokenTTL, $client, $userIdentifier, $scopes);
    }


    /** {@inheritdoc} */
    public function issueRefreshToken(AccessTokenEntityInterface $accessToken)
    {
        // Override to make public.
        return parent::issueRefreshToken($accessToken);
    }

    /**
     * Validates the user in the same way as the real PasswordGrant does, but without
     * the need to create a [ServerRequestInterface] for the $request argument.
     *
     * @param string $username
     * @param string $password
     * @param Client client
     *
     * @return UserEntityInterface
     */
    public function directValidateUser($username, $password, $client)
    {
        $user = $this->userRepository->getUserEntityByUserCredentials(
            $username,
            $password,
            $this->getIdentifier(),
            $client
        );
        if ($user instanceof UserEntityInterface === false) {
            Log::debug("Emit RequestEvent: RequestEvent::USER_AUTHENTICATION_FAILED");

            // $this->getEmitter()->emit(new RequestEvent(RequestEvent::USER_AUTHENTICATION_FAILED, $request));

            throw OAuthServerException::invalidCredentials();
        }

        return $user;
    }

    protected function getRequestParameter($parameter, ServerRequestInterface $request, $default = null)
    {
        if ($this->username != null && $parameter == 'username') {
            return $this->username;
        }

        if ($this->password != null && $parameter == 'password') {
            return $this->password;
        }

        return parent::getRequestParameter($parameter, $request, $default);
    }


}