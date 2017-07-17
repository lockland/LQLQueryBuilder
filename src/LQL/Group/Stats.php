<?php

namespace LQL\Group;

use LQL\StatsInterface as IStats;

/**
 * Class Stats
 */
class Stats extends AbsGroup implements IStats
{

    /**
     * @see AbsGroup::add()
     */
    public function add(IStats $item)
    {
        return parent::add($item);
    }

    protected function getNegateString()
    {
        return "StatsNegate:";
    }

    protected function getOperator()
    {
        return "Stats" . $this->operator;
    }
}
