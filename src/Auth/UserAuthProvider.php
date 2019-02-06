<?php

namespace Logistio\Symmetry\Auth;

use Illuminate\Auth\Authenticatable;

class UserAuthProvider
{

    private static $overrideUser;

    /**
     * Controls whether this UserAuthProvider will be reset after
     * the 'getAuthUser' function is called.
     * This allows one to set an $overrideUser that will only
     * be retieved once, so that the UserAuthProvider can be
     * guaranteed to be in its "normal" state after the user is retrieved.
     *
     * @var boolean
     */
    private static $isResetAfterGetUser = false;

    /**
     * Can be used to inject the Authorized user into tests.
     *
     * The next time that [getAuthUser()] is called the provided [$user]
     * will be returned and this [UserAuthProvider] will then be reset.
     *
     * @param $user
     * @param bool $isResetAfterNextRetrieval
     */
    public static function setNextUser($user, $isResetAfterNextRetrieval = false)
    {
        // TODO: Use the Passport "actingAs" function instead of overrideUser.
        // See: https://laravel.com/docs/5.7/passport#testing

        self::$overrideUser = $user;
        self::$isResetAfterGetUser = $isResetAfterNextRetrieval;
    }

    /**
     * Get the currently logged in User.
     *
     * @return null|User|Authenticatable
     */
    public static function getAuthUser()
    {
        if (self::$overrideUser) {
            $user = self::$overrideUser;
            if (self::$isResetAfterGetUser) {
                self::reset();
            }
            return $user;

        } else {
            return \Auth::user();
        }
    }

    /**
     * Resets the state of [UserAuthProvider] to a "fresh" default state.
     *
     * This should only be used for reseting the app's state between
     * unit tests. This should not be used in production, as the user
     * should never be "un-authenticated" within a request context.
     *
     */
    public static function reset()
    {
        self::$isResetAfterGetUser = false;
        self::$overrideUser = null;
        try {
            \DB::transaction(function() {
                \Auth::logout();
            });
        } catch (\Exception $e) {
            // This process fails because our User table doesn't have 'remember_token' in it.
            // The user will still be removed from Auth::user, which is what we want.
            // This is a very dirty solution, but the reset method should only be used
            // for resetting the app state between unit tests.
        }
    }

    /**
     * Converts a password to the hash format used by the database.
     *
     * @param $rawPassword
     * @return string
     */
    public static function hashPassword($rawPassword)
    {
        return \Hash::make($rawPassword);
    }

}