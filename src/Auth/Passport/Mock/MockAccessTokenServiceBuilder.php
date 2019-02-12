<?php

namespace Logistio\Symmetry\Auth\Passport\Mock;


use Laravel\Passport\Bridge\AccessTokenRepository;
use Laravel\Passport\Bridge\RefreshTokenRepository;
use Laravel\Passport\Bridge\UserRepository;
use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;
use League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface;
use League\OAuth2\Server\Repositories\UserRepositoryInterface;
use Logistio\Symmetry\Auth\AuthEntityRepository;
use Logistio\Symmetry\Auth\Passport\AccessTokenServiceInterface;


/**
 * MockAccessTokenServiceBuilder
 * ----
 * Builds the MockAccessTokenService.
 *
 */
class MockAccessTokenServiceBuilder
{

    /**
     * @var \Illuminate\Foundation\Application
     */
    private $app;

    /**
     *
     * @param \Illuminate\Foundation\Application $app
     */
    public function __construct($app)
    {
        $this->app = $app;
    }

    // ----------------------------------------------------


    /**
     * @param AuthEntityRepository $authEntityRepo
     *
     * @return MockAccessTokenService
     */
    public function createMockAccessTokenService(AuthEntityRepository $authEntityRepo)
    {
        $accessTokenService = new MockAccessTokenService($authEntityRepo);

        $this->app->singleton(AccessTokenServiceInterface::class, function ($app) use ($accessTokenService) {
            return $accessTokenService;
        });

        $this->bindIfNotRegistered(UserRepositoryInterface::class, UserRepository::class);
        $this->bindIfNotRegistered(RefreshTokenRepositoryInterface::class, RefreshTokenRepository::class);
        $this->bindIfNotRegistered(AccessTokenRepositoryInterface::class, AccessTokenRepository::class);

        return $accessTokenService;
    }

    private function bindIfNotRegistered($abstract, $concrete)
    {
        if (!in_array($abstract, $this->app->getBindings())) {
            $this->app->bind($abstract, $concrete);
        }
    }

}