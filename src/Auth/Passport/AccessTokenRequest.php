<?php


namespace Logistio\Symmetry\Auth\Passport;


class AccessTokenRequest
{
    const GRANT_TYPE_CLIENT_CREDENTIALS = 'client_credentials';
    const GRANT_TYPE_PASSWORD = 'password';

    /**
     * @var string
     */
    protected $clientId;

    /**
     * @var string
     */
    protected $clientSecret;

    /**
     * @var string
     */
    protected $grantType;

    public function __construct()
    {
    }

    /**
     * @return string
     */
    public function getClientId()
    {
        return $this->clientId;
    }

    /**
     * @param string $clientId
     */
    public function setClientId($clientId)
    {
        $this->clientId = $clientId;
    }

    /**
     * @return string
     */
    public function getClientSecret()
    {
        return $this->clientSecret;
    }

    /**
     * @param string $clientSecret
     */
    public function setClientSecret($clientSecret)
    {
        $this->clientSecret = $clientSecret;
    }

    /**
     * @return string
     */
    public function getGrantType()
    {
        return $this->grantType;
    }

    /**
     * @param string $grantType
     */
    public function setGrantType($grantType)
    {
        $this->grantType = $grantType;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'grant_type' => $this->getGrantType(),
            'client_id' => $this->getClientId(),
            'client_secret' => $this->getClientSecret(),
            'scope' => null,
        ];
    }
}