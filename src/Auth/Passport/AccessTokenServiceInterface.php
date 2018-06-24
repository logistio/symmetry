<?php
/* Copyright (C) 2018, Logistio Limited. All Rights Reserved. */

namespace Logistio\Symmetry\Auth\Passport;


use Logistio\Symmetry\Exception\Auth\InvalidCredentialsException;

interface AccessTokenServiceInterface
{

    /**
     * @param AccessTokenRequest $accessTokenRequest
     * @return AccessToken
     * @throws InvalidCredentialsException
     */
    public function getToken(AccessTokenRequest $accessTokenRequest);

}