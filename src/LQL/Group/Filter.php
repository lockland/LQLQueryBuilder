<?php

namespace LQL\Group;

use LQL\FilterInterface as IFilter;

/**
 * Class Filter
 */
class Filter extends AbsGroup implements IFilter
{
    /**
     * @see AbsGroup::add()
     */
    public function add(IFilter $item)
    {
        return parent::add($item);
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
