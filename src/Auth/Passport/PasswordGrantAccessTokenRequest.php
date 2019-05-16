<?php


namespace Logistio\Symmetry\Auth\Passport;

use Logistio\Symmetry\Util\String\SecureString;

/**
 * Class PasswordGrantAccessTokenRequest
 * @package Fastway\Platform\Auth\Passport
 */
class PasswordGrantAccessTokenRequest extends AccessTokenRequest
{
    /**
     * @var string
     */
    protected $username;

    /**
     * @var string|SecureString
     */
    protected $password;

    /**
     * PasswordGrantAccessTokenRequest constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->setGrantType(static::GRANT_TYPE_PASSWORD);
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername(string $username)
    {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        if ($this->password instanceof SecureString) {
            return $this->password->getString();
        }

        return $this->password;
    }

    /**
     * @param string|SecureString $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $parent  = parent::toArray();

        return array_merge($parent, [
            'username'  => $this->getUsername(),
            'password'  => $this->getPassword()
        ]);
    }
}