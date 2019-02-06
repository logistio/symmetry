<?php


namespace Logistio\Symmetry\Test\PublicId;


use Logistio\Symmetry\PublicId\PublicIdManager;
use Logistio\Symmetry\Test\TestCase;

/**
 * PublicIdManagerTest
 * ----
 *
 *
 * @package Logistio\Symmetry\Test\PublicId
 */
class PublicIdManagerTest extends TestCase
{

    /**
     * @test
     */
    public function it_is_globally_available()
    {
        $globalConverter1 = PublicIdManager::getGlobalConverter();
        $globalConverter2 = PublicIdManager::getGlobalConverter();
        self::assertEquals($globalConverter1, $globalConverter2);

        $appConverter = $this->app->make('PublicId');
        self::assertEquals($globalConverter1, $appConverter);
    }

    /**
     * @test
     */
    public function it_can_encode_and_decode()
    {
        $rawId = 11231232;
        $pubId = PublicIdManager::encode($rawId);
        $decodedId = PublicIdManager::decode($pubId);
        self::assertEquals($rawId, $decodedId);
    }

    /**
     * The static methods offered by PublicIdManager should produce
     * the same values as are provided by its inner PublicIdConverter.
     *
     * @test
     */
    public function it_produces_same_results_as_converter()
    {
        $globalConverter = PublicIdManager::getGlobalConverter();
        $appConverter = $this->app->make('PublicId');

        $rawId = 1231283;
        $encodedId = PublicIdManager::encode($rawId);

        // Both converters should encode the same value:
        self::assertEquals($encodedId, $globalConverter->encode($rawId));
        self::assertEquals($encodedId, $appConverter->encode($rawId));

        // Both converters should decode the same value:
        self::assertEquals($rawId, $globalConverter->decode($encodedId));
        self::assertEquals($rawId, $appConverter->decode($encodedId));
    }


}