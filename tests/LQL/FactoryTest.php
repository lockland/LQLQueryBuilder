<?php

namespace LQL;

/**
 * Class FactoryTest
 */
class FactoryTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @testdox Should return a QueryBuilder Object
     */
    public function createTable()
    {
        $this->assertInstanceOf('LQL\QueryBuilder', Factory::from('hosts'));
    }

    /**
     * @testdox Should return a Operator Object
     */
    public function createOperator()
    {
        $this->assertInstanceOf('LQL\Operator', Factory::createOperator('='));
    }

    /**
     * @testdox Should return a Filter Object
     */
    public function createFilter()
    {
        $operator = Factory::createOperator('=');
        $object = Factory::createFilter(array('column', $operator, 'value'));
        $this->assertInstanceOf('LQL\Filter', $object);
    }

    /**
     * @testdox Should return a FilterGroup Object
     */
    public function createFilterGroup()
    {
        list($items, $operator, $negate) = $this->getGroupData();
        $object = Factory::createFilter($items, $operator, $negate);
        $this->assertInstanceOf('LQL\Group\Filter', $object);

        $expected = "Filter: column = value\n"
            . "Filter: column2 = value2\n"
            . "And: 2\n"
            . "Negate:";

        $this->assertEquals($expected, $object);
    }

    /**
     * @testdox Should return a Stats Object
     */
    public function createStats()
    {
        $operator = Factory::createOperator('=');
        $object = Factory::createStats(array('column', $operator, 'value'));
        $this->assertInstanceOf('LQL\Stats', $object);
    }

    /**
     * @testdox Should return a StatsGroup Object
     */
    public function createStatsGroup()
    {
        list($items, $operator, $negate) = $this->getGroupData();
        $object = Factory::createStats($items, $operator, $negate);
        $this->assertInstanceOf('LQL\Group\Stats', $object);

        $expected = "Stats: column = value\n"
            . "Stats: column2 = value2\n"
            . "StatsAnd: 2\n"
            . "StatsNegate:";

        $this->assertEquals($expected, $object);
    }


    private function getGroupData()
    {
        return array(
            $this->createItems(),
            Factory::createOperator('and'),
            true,
        );
    }

    private function createItems()
    {
        $operator = Factory::createOperator('=');
        return array(
            array('column', $operator, 'value'),
            array('column2', $operator, 'value2'),
        );
    }
}
