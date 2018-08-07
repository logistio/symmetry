<?php

namespace Logistio\Symmetry\Test\Util\Type;

use Logistio\Symmetry\Test\TestCase;
use Logistio\Symmetry\Util\Type\PropertyTypeCaster;

class PropertyTypeCasterTest extends TestCase
{
    /**
     * @test
     */
    public function testTypeCastObjectArray()
    {
        $data = [];

        $obj = new \stdClass();

        $obj->{'total_sales'} = "100";
        $obj->{'total_revenue'} = "250.75";

        $data[] = $obj;

        $typeCastConfig = [[
            'property' => 'total_sales',
            'type' => PropertyTypeCaster::TYPE_INT
        ], [
            'property' => 'total_revenue',
            'type' => PropertyTypeCaster::TYPE_FLOAT
        ]];

        $caster = new PropertyTypeCaster();

        $newData = $caster->typeCastObjectArray($data, $typeCastConfig);

        $firstElement = $newData[0];

        $this->assertTrue(is_int($firstElement->{'total_sales'}));

        $this->assertTrue(is_float($firstElement->{'total_revenue'}));
    }

    /**
     * @test
     */
    public function testTypeCastAssociativeArray()
    {
        $data = [[
            'total_sales' => "100",
            'total_revenue' => '250.75'
        ]];

        $typeCastConfig = [[
            'property' => 'total_sales',
            'type' => PropertyTypeCaster::TYPE_INT
        ], [
            'property' => 'total_revenue',
            'type' => PropertyTypeCaster::TYPE_FLOAT
        ]];

        $caster = new PropertyTypeCaster();

        $newData = $caster->typeCastAssociativeArray($data, $typeCastConfig);

        $firstElement = $newData[0];

        $this->assertTrue(is_int($firstElement['total_sales']));

        $this->assertTrue(is_float($firstElement['total_revenue']));
    }
}