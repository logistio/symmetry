<?php
/* Copyright (C) 2018, Logistio Limited. All Rights Reserved. */


namespace Logistio\Symmetry\PublicId;


use Hashids\Hashids;
use Logistio\Symmetry\Exception\BaseException;
use Logistio\Symmetry\Exception\FlaggedExceptionFactory;

class PublicIdConverter
{
    /**
     * The name of the DB column
     * if the table supports public IDs.
     *
     */
    const DATABASE_COLUMN = 'pubid';

    /**
     * @var Hashids $hashIds
     */
    private $hashIds;

    public function __construct(Hashids $hashids)
    {
        $this->hashIds = $hashids;
    }

    /**
     * @return string
     */
    public function getDatabaseColumn()
    {
        return static::DATABASE_COLUMN;
    }

    /**
     * @param $int - The integer to be hashed
     * @return mixed
     */
    public function encode($int)
    {
        return $this->hashIds->encode($int);
    }

    /**
     * Decode a public Id.
     *
     * This method is strict, and it will thrown an error if the given
     * value is not a Public Id.
     *
     * @param $idValue
     *
     * @return int
     *
     * @throws BaseException if the given $idValue has the wrong format, and cannot be decoded.
     */
    public function decode($idValue)
    {
        $hashIdValues = $this->hashIds->decode($idValue);

        if (isset($hashIdValues[0])) {
            return $hashIdValues[0];
        } else {
            throw FlaggedExceptionFactory::createWithMessageAndFlag('Invlaid id value.', 'INVALID_ID');
        }
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
    public function decodeSoft($idValue)
    {
        if ($this->isEncoded($idValue)) {
            return $this->decode($idValue);
        } else {
            return $idValue;
        }
    }

    /**
     * @param $id
     * @return bool
     */
    public function isEncoded($id)
    {
        return !is_numeric($id);
    }

}