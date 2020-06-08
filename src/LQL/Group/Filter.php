<?php

namespace LQL\Group;

use LQL\FilterInterface;

/**
 * Class Filter
 */
class Filter extends AbsGroup implements FilterInterface
{

    public function add($item)
    {
        if ($item instanceof FilterInterface) {
            return parent::add($item);
        }

        throw new \InvalidArgumentException('Excepted a ' . FilterInterface::class . ' instance');
    }

    protected function getNegateString()
    {
        return "Negate:";
    }

    protected function getOperator()
    {
        return $this->operator;
    }
}
