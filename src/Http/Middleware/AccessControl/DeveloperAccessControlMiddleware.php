<?php

namespace Logistio\Symmetry\Http\Middleware\AccessControl;

use Illuminate\Http\Request;
use Logistio\Symmetry\Auth\UserAuthProvider;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * Class DeveloperAccessControlMiddleware
 * @package Logistio\Symmetry\Http\Middleware\AccessControl
 */
class DeveloperAccessControlMiddleware
{
    const MIDDLEWARE_KEY = 'user.developer';

    public function handle(Request $request, \Closure $next)
    {
        $user = UserAuthProvider::getAuthUser();

        if (!$user) {
            throw new \Exception("The user is not available from the auth provider service.");
        }

        if (!$user->isDeveloper()) {
            throw new AccessDeniedHttpException("Only users with the `DEVELOPER` privilege may access this resource.");
        }

        return $next($request);
    }
}