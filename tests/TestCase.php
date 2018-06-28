<?php

namespace Logistio\Symmetry\Test;

use Logistio\Symmetry\PublicId\PublicId;
use Logistio\Symmetry\SymmetryServiceProvider;

class TestCase extends Orchestra\Testbench\TestCase
{
    protected function getPackageProviders($app)
    {
        return [
            SymmetryServiceProvider::class
        ];
    }

    protected function getPackageAliases($app)
    {
        return [
            'PublicId' => PublicId::class
        ];
    }
}