<?php

namespace LQL;

/**
 * Class QueryBuilderTest
 */
class QueryBuilderTest extends AbsTest
{
    protected $builder;

    protected $suffix = "\nOutputFormat: json\nResponseHeader: fixed16\nColumnHeaders: on";

    protected function setUp()
    {
        $this->builder = new QueryBuilder(QueryBuilder::TABLE_HOSTS);
    }

    protected function getObject()
    {
        return $this->builder;
    }

    /**
     * @testdox Should create a Querybuilder object for valid tables
     *
     * @dataProvider tables
     */
    public function constructor($table)
    {
        $this->assertInstanceOf('LQL\QueryBuilder', new QueryBuilder($table));
    }

    public function tables()
    {
        return array(
            array('hosts'),
            array('services'),
            array('hostgroups'),
            array('servicegroups'),
            array('contactgroups'),
            array('servicesbygroup'),
            array('servicesbyhostgroup'),
            array('hostsbygroup'),
            array('contacts'),
            array('commands'),
            array('timeperiods'),
            array('downtimes'),
            array('comments'),
            array('log'),
            array('status'),
            array('columns'),
            array('statehist'),
        );
    }

    /**
     * @testdox Should throw an InvalidArgumentException for a invalid table
     */
    public function invalidTable()
    {
        $this->setExpectedException('InvalidArgumentException', '[bla] is an unsupported table');
        new QueryBuilder('bla');
    }

    /**
     * @testdox Should return the GET line only
     */
    public function getAll()
    {
        $expected = "GET hosts"
            . $this->suffix;

        $this->assertEquals($expected, $this->builder);
    }

    /**
     * @testdox Should define which columns will be returned by Array
     */
    public function selectByArray()
    {
        $expected = "GET hosts\n"
            . "Columns: name descr"
            . $this->suffix;


        $this->assertEquals($expected, $this->builder->select(array('name', 'descr')));
    }

    /**
     * @testdox Should define which columns will be returned by string
     */
    public function selectByString()
    {
        $expected = "GET hosts\n"
            . "Columns: name descr"
            . $this->suffix;

        $ifs = '|';
        $this->assertEquals($expected, $this->builder->select('name|descr', $ifs));
    }

    /**
     * @testdox Should define which filters will be applied
     */
    public function filterByFilter()
    {
        $expected = "GET hosts\n"
            . "Filter: name = value"
            . $this->suffix;

        $this->assertEquals($expected, $this->builder->filterBy(Filter::createFromString('name = value')));
    }

    /**
     * @testdox Should define which FiltersGroup will be applied
     */
    public function filterByFilterGroup()
    {
        $expected = "GET hosts\n"
            . "Filter: name = value\n"
            . "Filter: name2 = value2\n"
            . "And: 2"
            . $this->suffix;

        list($items, $operator) = $this->getGroupData();
        $group = Factory::createFilter($items, $operator);

        $this->assertEquals($expected, $this->builder->filterBy($group));
    }

    /**
     * @testdox Should define which stats will be applied
     */
    public function statsForStats()
    {
        $expected = "GET hosts\n"
            . "Stats: name = value"
            . $this->suffix;

        $this->assertEquals($expected, $this->builder->statsFor(Stats::createFromString('name = value')));
    }

    /**
     * @testdox Should define which StatsGroup will be applied
     */
    public function statsForStatsGroup()
    {
        $expected = "GET hosts\n"
            . "Stats: name = value\n"
            . "Stats: name2 = value2\n"
            . "StatsAnd: 2"
            . $this->suffix;

        list($items, $operator) = $this->getGroupData();
        $group = Factory::createStats($items, $operator);

        $this->assertEquals($expected, $this->builder->statsFor($group));
    }

    private function getGroupData()
    {
        return array($this->createItems(), Factory::createOperator('and'));
    }

    private function createItems()
    {
        $operator = Factory::createOperator('=');
        return array(
            array('name', $operator, 'value'),
            array('name2', $operator, 'value2'),
        );
    }

    /**
     * @testdox Should add sort criteria to query
     */
    public function sortBy()
    {
        $expected = "GET hosts\n"
            . "Sort: name asc"
            . $this->suffix;

        $this->assertEquals($expected, $this->builder->sortBy('name', 'asc'));

        $expected = "GET hosts\n"
            . "Sort: name asc\n"
            . "Sort: name2 asc"
            . $this->suffix;

        $this->assertEquals($expected, $this->builder->sortBy('name2', 'asc'));
    }

    /**
     * @testdox Should add a multimensional sort array to query
     */
    public function sortsUsingArray()
    {
        $expected = "GET hosts\n"
            . "Sort: name asc\n"
            . "Sort: name2 asc"
            . $this->suffix;

        $items = array(
            array('name', 'asc'),
            array('name2', 'asc'),
        );

        $this->assertEquals($expected, $this->builder->sorts($items));
    }

    /**
     * @testdox Should add a sort array to query
     */
    public function sortsUsingStrings()
    {
        $expected = "GET hosts\n"
            . "Sort: name asc\n"
            . "Sort: name2 asc"
            . $this->suffix;

        $items = array('name asc', 'name2 asc');

        $this->assertEquals($expected, $this->builder->sorts($items));
    }

    /**
     * @testdox Should Aggregate result stats
     */
    public function aggregateBy()
    {
        $expected = "GET hosts\n"
            . "Stats: sum name"
            . $this->suffix;

        $this->assertEquals($expected, $this->builder->aggregateBy('sum', 'name'));

        $expected = "GET hosts\n"
            . "Stats: sum name\n"
            . "Stats: min name2"
            . $this->suffix;

        $this->assertEquals($expected, $this->builder->aggregateBy('min', 'name2'));
    }

    /**
     * @testdox Should add limit and offset token to query, zero for offset value
     */
    public function limitDefaultOffset()
    {
        $expected = "GET hosts\n"
            . "Limit: 10\n"
            . "Offset: 0"
            . $this->suffix;

        $this->assertEquals($expected, $this->builder->limit(10));
    }

    /**
     * @testdox Should add limit and offset token to query
     */
    public function limit()
    {
        $expected = "GET hosts\n"
            . "Limit: 10\n"
            . "Offset: 20"
            . $this->suffix;

        $this->assertEquals($expected, $this->builder->limit(10, 20));
    }

    /**
     * @testdox Should add authenticated user name token
     */
    public function authUser()
    {
        $expected = "GET hosts\n"
            . "AuthUser: test"
            . $this->suffix;

        $this->assertEquals($expected, $this->builder->authUser('test'));
    }

    /**
     * @testdox Should Format output result
     */
    public function outputFormat()
    {
        $expected = "GET hosts\n"
            . "OutputFormat: bla\n"
            . "ResponseHeader: fixed16\n"
            . "ColumnHeaders: off";

        $this->assertEquals($expected, $this->builder->outputFormat('bla', 'off'));
    }

    /**
     * @testdox Should create a Query object
     */
    public function createQuery()
    {
        $query = $this->builder->createQuery();
        $this->assertInstanceOf('LQL\Query', $query);

        $expected = "GET hosts"
            . $this->suffix;

        $this->assertEquals($expected, $query);
    }
}
