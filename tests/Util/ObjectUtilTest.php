<?php


namespace Logistio\Symmetry\Test\Util;


use Logistio\Symmetry\Test\TestCase;
use Logistio\Symmetry\Util\ObjectUtil;

class ObjectUtilTest extends TestCase
{
    public $cheese = 'gouda';

    public function testExtractParam()
    {
        self::assertEquals($this->cheese, ObjectUtil::extractParam($this, 'cheese'));

        $data = [
            'size' => 9,
            'name' => 'cake',
            'flavour' => 'tiramisu'
        ];

        self::assertEquals(9, ObjectUtil::extractParam($data, 'size'));
        self::assertEquals('cake', ObjectUtil::extractParam($data, 'name'));

        $result = ObjectUtil::extractParam($data, 'ladder', function () {
            return 'step_ladder';
        });

        self::assertEquals('step_ladder', $result);
    }


}