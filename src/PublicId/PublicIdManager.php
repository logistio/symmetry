<?php
/* Copyright (C) 2017, Logistio Limited. All Rights Reserved. */

namespace Logistio\Symmetry\PublicId;


use Hashids\Hashids;


/**
 * PublicIdManager
 * ----
 * Provides static access to the globally-available PublicIdConverter.
 *
 * @package App\Services\PublicId
 */
class PublicIdManager
{

    /**
     * @var PublicIdConverter
     */
    private static $globalPubIdConverter;

    /**
     * @return PublicIdConverter
     */
    public static function getGlobalConverter(): PublicIdConverter
    {
        return self::getConverter();
    }

    /**
     * @return PublicIdConverter
     */
    private static function getConverter(): PublicIdConverter
    {
        if (!self::$globalPubIdConverter) {
            self::$globalPubIdConverter = app()->make('PublicId');
        }
        return self::$globalPubIdConverter;
    }

    /**
     * @return Hashids
     */
    public static function createHashIds(): Hashids
    {
        return new Hashids(env('PUB_ID_SALT'));
    }

    /**
     * @param $int - The integer to be hashed
     * @return mixed
     */
    public static function encode($int)
    {
        return self::getConverter()->encode($int);
    }

    /**
     * Decode a public Id.
     *
     * This method is strict, and it will thrown an error if the given
     * value is not a Public Id.
     *
     * @param $idValue
     * @return mixed
     */
    public static function decode($idValue)
    {
        return self::getConverter()->decode($idValue);
    }

    /**
     * Decodes a Public Id non-strictly.
     *
     * If a database ID is given instead of a public ID, then this
     * method will detect that and return the given idValue instead
     * of trying to decode it.
     *
     * Use of the strict [decode] method is prefered, and should be
     * used wherever the given [$idValue] is directly provided by the user.
     *
     *
     * @param $idValue
     * @return mixed
     */
    public static function decodeSoft($idValue)
    {
        return self::getConverter()->decodeSoft($idValue);
    }

    /**
     * @param $id
     * @return bool
     */
    public static function isEncoded($id)
    {
        return self::getConverter()->isEncoded($id);
    }
}