<?php


namespace Logistio\Symmetry\Util\Type;

use Illuminate\Support\Facades\Facade;

class TypeCaster extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor() {
        return PropertyTypeCaster::class;
    }
}