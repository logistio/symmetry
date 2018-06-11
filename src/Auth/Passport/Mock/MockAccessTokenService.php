<?php
/* Copyright (C) 2018, Logistio Limited. All Rights Reserved. */


namespace Logistio\Symmetry\Auth\Passport\Mock;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Logistio\Symmetry\Auth\Passport\AccessToken;
use Logistio\Symmetry\Auth\Passport\AccessTokenRequest;
use Logistio\Symmetry\Auth\Passport\AccessTokenServiceInterface;
use Illuminate\Foundation\Testing\Concerns\MakesHttpRequests;
use Illuminate\Support\Facades\DB;
use Laravel\Passport\Bridge\ClientRepository;
use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\Grant\PasswordGrant;
use League\OAuth2\Server\Repositories\ClientRepositoryInterface;
use Psr\Http\Message\RequestInterface;

/**
 * MockAccessTokenService
 * ----
 * A Mock [AccessTokenServiceInterface] that simulates
 * making an API request using [MakesHttpRequests], which is the
 * same way that the Laravel TestCases simulat API requests.
 *
 * This still makes a request to Passport, so you can be sure that using
 * this behaves in essentially the same way as the standard [AccessTokenService]
 * implementation would, but this does not actually have to make a HTTP
 * request; instead it uses Laravel's HTTP mocking tools to simulate a call
 * to the 'oauth/token' route, removing the overhead of
 * calling a real network request.
 *
 * When testing, this has an advantage over the standard [AccessTokenService]
 * by keeping everything within the same PHP process as the unit test that
 * is being run. This means that the auth is performed within the same transaction
 * as the unit test, so if one creates a new User within the transaction
 * it will be visible to Passport.
 *
 */
class MockAccessTokenService implements AccessTokenServiceInterface
{
    use MakesHttpRequests;

    const URI = '/oauth/token';

    /**
     * @var AccessTokenRequest|null
     */
    public $lastAccessTokenRequest;

    /**
     * @var MockHandler $mockHandler
     */
    private $mockHandler;

    /**
     * AccessTokenMockService constructor.
     */
    public function __construct()
    {
        // $app is required by MakesHttpRequests.
        $this->app = app();
    }


    public function getToken(AccessTokenRequest $accessTokenRequest)
    {
        $this->lastAccessTokenRequest = $accessTokenRequest;

        $data = $accessTokenRequest->toArray();

        $result = $this->postJson(self::URI, $data);

        $resultContent = $result->getContent();
        $resultJson = json_decode($resultContent);

        $accessToken = new AccessToken();
        $accessToken->setAccessToken($resultJson->access_token);
        $accessToken->setExpiresIn($resultJson->expires_in);
        $accessToken->setRefreshToken($resultJson->refresh_token);
        $accessToken->setTokenType($resultJson->token_type);

        return $accessToken;
    }

    /**
     * @return Client
     */
    private function buildAuthApiMocker()
    {
        $this->mockHandler = new MockHandler([
            function ($request, $handlerData) {

                $accessTokenRequest = $this->parseAccessTokenRequest($request);

                $this->lastAccessTokenRequest = $accessTokenRequest;

                $clientId = $this->lastAccessTokenRequest->getClientId();
                $clientSecret = $this->lastAccessTokenRequest->getClientSecret();

                /**
                 * @var ClientRepositoryInterface
                 */
                $clientRepo = app()->make(ClientRepository::class);
                $client = $clientRepo->getClientEntity(
                    $clientId,
                    'password',
                    $clientSecret,
                    true
                );

                $userId = DB::select('user_id from oauth_clients WHERE id=?;', [$clientId]);

                /**
                 * @var MockPasswordGrant $passwordGrant
                 */
                $passwordGrant = app()->make(MockPasswordGrant::class);

                $accessTokenTTL = \DateInterval::createFromDateString('1 day');

                $finalizedScopes = [];

                // Issue and persist new tokens
                $accessToken = $passwordGrant->issueAccessToken($accessTokenTTL, $client, $userId, $finalizedScopes);
                $refreshToken = $passwordGrant->issueRefreshToken($accessToken);


                dd([self::class, $accessToken, $refreshToken]);

                /*
                $headers = [
                    'Content-Type' => 'application/json'
                ];
                return new Response(
                    $statusCode,
                    $headers,
                    json_encode($starfishResponse)
                );
                */
            }
        ]);

        $handler = HandlerStack::create($this->mockHandler);

        return new Client(['handler' => $handler]);

    }


    /**
     * @param RequestInterface $guzzleRequest
     * @return AccessTokenRequest
     */
    private function parseAccessTokenRequest($guzzleRequest)
    {
        $guzzleRequest->getBody()->rewind();
        $lastRequestData = $guzzleRequest->getBody()->getContents();

        return json_decode($lastRequestData);
    }

}