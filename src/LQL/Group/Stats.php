<?php

namespace LQL\Group;

use LQL\StatsInterface;

/**
 * Class Stats
 */
class Stats extends AbsGroup implements StatsInterface
{
    public function add($item)
    {
        if ($item instanceof StatsInterface) {
            return parent::add($item);
        }

        throw new \InvalidArgumentException('Excepted a ' . StatsInterface::class . ' instance');
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
