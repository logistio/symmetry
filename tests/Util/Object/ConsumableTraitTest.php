<?php


namespace Logistio\Symmetry\Test\Util\Object;


use Logistio\Symmetry\Test\TestCase;

class ConsumableTraitTest extends TestCase
{
    /**
     * Test that a Consumable cannot be consumed more than once.
     *
     * @test
     */
    public function testIsConsumed()
    {
        $sandwhich = new ConsumableSandwhich();

        $sandwhich->eat();

        try {
            $sandwhich->eat();
            self::fail("Should not be consumable twice");
        } catch (\Exception $expected) {
            self::assertInstanceOf(\BadMethodCallException::class, $expected);
        }

    }


}