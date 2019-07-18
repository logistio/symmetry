<?php


namespace Logistio\Symmetry\Test\Util\Object;


use Logistio\Symmetry\Util\Object\Consumable;

class ConsumableSandwhich
{
    use Consumable;

    public function eat()
    {
        $this->consume();
    }

}