<?php

namespace LQL\Group;

use LQL\Group\Filter as FilterGroup;
use LQL\AbsTest;
use LQL\Operator;
use LQL\Filter;

/**
 * Class FilterTest
 */
class FilterTest extends AbsTest
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
        $this->filterGroup = new FilterGroup($this->operator);
    }

    public function getObject()
    {
        return new FilterGroup($this->operator);
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
        $this->filterGroup->add(Filter::createFromString('name = value'));
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
            ->add(new Filter('name1', $operator, 'value'))
            ->add(new Filter('name2', $operator, 'value'))
            ->add(new Filter('name3', $operator, 'value'))
            ->negate()
        ;

        $expected = "Filter: name1 = value\n"
            . "Filter: name2 = value\n"
            . "Filter: name3 = value\n"
            . "And: 3\n"
            . "Negate:\n"
            . "Filter: name4 = value\n"
            . "Or: 2";

        $orGroup = new FilterGroup(new Operator('Or'));
        $orGroup->add($this->filterGroup)
            ->add(new Filter('name4', $operator, 'value'));

        $this->verifyToString($expected, $orGroup);
    }
}
