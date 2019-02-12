<?php


namespace Logistio\Symmetry\Test\Auth\Passport\Mock;


use Logistio\Symmetry\Auth\Passport\Mock\MockAccessTokenService;
use Logistio\Symmetry\Test\Auth\MockAuthEntityRepository;
use Logistio\Symmetry\Test\TestCase;


/**
 * MockAccessTokenServiceTest
 * ----
 * This class is very difficult to test outside of a larger Laravel project,
 * so the tests performed here are basically useless.
 *
 */
class MockAccessTokenServiceTest extends TestCase
{

    /**
     * Test that we can construct the service.
     *
     * @test
     */
    public function testConstructService()
    {
        $mockAuthRepo = new MockAuthEntityRepository();
        $mockAccessTokenService = new MockAccessTokenService($mockAuthRepo);

        self::assertInstanceOf(MockAccessTokenService::class, $mockAccessTokenService);
    }



}