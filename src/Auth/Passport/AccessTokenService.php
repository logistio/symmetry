<?php


namespace Logistio\Symmetry\Auth\Passport;


use Logistio\Symmetry\Exception\Auth\InvalidCredentialsException;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\ClientException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * Class AccessTokenService
 * @package Fastway\Platform\Auth\Passport
 */
class AccessTokenService implements AccessTokenServiceInterface
{
    private static $URI = '/oauth/token';

    /**
     * @return string
     */
    private function getUrl()
    {
        return env('APP_URL') . static::$URI;
    }

    /**
     * @param AccessTokenRequest $accessTokenRequest
     * @return AccessToken
     * @throws InvalidCredentialsException
     */
    public function getToken(AccessTokenRequest $accessTokenRequest)
    {
        $httpClient = $this->buildHttpClient();

        $url = $this->getUrl();

        try {
            $response = $httpClient->post($url, [
                'form_params' => $accessTokenRequest->toArray(),
            ]);
        } catch (ClientException $e) {

            if ($e->getCode() == 401) {
                throw new InvalidCredentialsException();
            }

            $body = json_decode((string)$e->getResponse()->getBody(), true);

            $message = array_get($body, 'message');

            $error = array_get($body, 'error');

            throw new BadRequestHttpException($message . " " . $error);
        }

        $responseDecoded = json_decode((string)$response->getBody(), true);

        return $this->guzzleResponseToAccessToken($responseDecoded);
    }

    /**
     * @return HttpClient
     */
    protected function buildHttpClient()
    {
        return new HttpClient([
            'verify' => false,
        ]);
    }

    /**
     * @param array $response
     * @return AccessToken
     */
    private function guzzleResponseToAccessToken(array $response)
    {
        $accessToken = new AccessToken();

        $accessToken->setTokenType($response['token_type']);
        $accessToken->setExpiresIn($response['expires_in']);
        $accessToken->setAccessToken($response['access_token']);

        $refreshToken = array_get($response, 'refresh_token');

        if (!is_null($refreshToken)) {
            $accessToken->setRefreshToken($refreshToken);
        }

        return $accessToken;
    }
}