<?php

namespace Logistio\Symmetry\Test;

use Logistio\Symmetry\PublicId\PublicId;
use Logistio\Symmetry\SymmetryServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
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