<?php
/* Copyright (C) 2017, Logistio Limited. All Rights Reserved. */

namespace Logistio\Symmetry\PublicId;


use Illuminate\Support\Facades\Facade;

class PublicId extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'PublicId';
    }

}