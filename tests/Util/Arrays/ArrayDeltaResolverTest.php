<?php

namespace Logistio\Symmetry\Test\Util\Arrays;

use Logistio\Symmetry\Test\TestCase;
use Logistio\Symmetry\Util\Arrays\ArrayDeltaResolver;

class ArrayDeltaResolverTest extends TestCase
{
    /**
     * @test
     * @throws \Exception
     */
    public function test()
    {
        $currentPeriodArray = [
            'total_bookings' => 100,
            'total_revenue' => 50,
            'name' => 'Dan Pinzaru',
            'total_items' => 0,
            'total_shipments' => "0",
            'total_consignments' => null,
            'total_drivers' => 10,
            'worth' => 100,
            'json_doc' => [
                'total_bookings' => 100,
                'total_revenue' => 50,
                'total_consignments' => null,
            ],
            'profile' => [
                'total_bookings' => 100,
                'total_revenue' => 50,
                'total_consignments' => null,
            ]
        ];

        $previousPeriodArray = [
            'total_bookings' => 50,
            'total_revenue' => 100,
            'name' => 'Dan Pinzaru',
            'total_items' => 100,
            'total_shipments' => "500a",
            'total_consignments' => 50,
            'total_drivers' => null,
            'json_doc' => [
                'total_bookings' => 50,
                'total_revenue' => 100,
                'total_consignments' => 20
            ],
            'profile' => null
        ];

        $delta = ArrayDeltaResolver::resolveDelta($currentPeriodArray, $previousPeriodArray);

        $expectedResult = [
            "total_bookings" => 50.0,
            "total_revenue" => -50.0,
            "name" => null,
            "total_items" => -100.0,
            "total_shipments" => "0",
            "total_consignments" => null,
            "total_drivers" => 10,
            "worth" => 100,
            "json_doc" => [
                "total_bookings" => 50.0,
                "total_revenue" => -50.0,
                "total_consignments" => null
            ],
            'profile' => [
                'total_bookings' => 100,
                'total_revenue' => 50,
                'total_consignments' => null,
            ]
        ];

        $this->assertEquals($expectedResult, $delta);
    }

    /**
     * @test
     */
    public function it_throws_exception_if_corresponding_value_is_not_an_array()
    {
        $currentPeriodArray = [
            'json_doc' => [
                'total_bookings' => 100,
            ]
        ];

        $previousPeriodArray = [
            'json_doc' => 20
        ];

        $expectedMessage = "Failed to perform the delta comparison. The corresponding value for key `json_doc` is not an array.";

        try {
            $delta = ArrayDeltaResolver::resolveDelta($currentPeriodArray, $previousPeriodArray);
        } catch (\Exception $e) {
            $this->assertEquals($expectedMessage, $e->getMessage());

            return;
        }

        $this->assertTrue(false, "Expected exception was not thrown");
    }
}