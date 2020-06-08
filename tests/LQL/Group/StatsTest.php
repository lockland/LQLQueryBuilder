<?php

namespace LQL\Group;

use LQL\Group\Stats as StatsGroup;
use LQL\AbsTest;
use LQL\Operator;
use LQL\Stats;

/**
 * Class StatsTest
 */
class StatsTest extends AbsTest
{
    protected $operator;
    protected $filterGroup;

    public function __construct()
    {
        parent::__construct();
        $this->operator = new Operator('and');
    }

    protected function setUp(): void
    {
        $this->filterGroup = new StatsGroup($this->operator);
    }

    public function getObject()
    {
        return new StatsGroup($this->operator);
    }

    /**
     * @test
     * @testdox Should throw an InvalidArgumentException for an invalid type
     */
    public function invalidType()
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->filterGroup->add('');
    }

    /**
     * @test
     * @testdox Should throw an RuntimeException for a group with less than 2 member
     */
    public function checkMinimalLength()
    {
        $this->expectException(\RuntimeException::class);
        $this->filterGroup->add(Stats::createFromString('name = value'));
        $this->filterGroup->__toString();
    }

    /**
     * @test
     * @testdox Should convert object to string
     */
    public function getString()
    {
        $operator = new Operator('=');
        $this->filterGroup
            ->add(new Stats('name1', $operator, 'value'))
            ->add(new Stats('name2', $operator, 'value'))
            ->add(new Stats('name3', $operator, 'value'))
            ->negate()
        ;

        $expected = "Stats: name1 = value\n"
            . "Stats: name2 = value\n"
            . "Stats: name3 = value\n"
            . "StatsAnd: 3\n"
            . "StatsNegate:\n"
            . "Stats: name4 = value\n"
            . "StatsOr: 2";

        $orGroup = new StatsGroup(new Operator('Or'));
        $orGroup->add($this->filterGroup)
            ->add(new Stats('name4', $operator, 'value'));

        $this->verifyToString($expected, $orGroup);
    }
}
