<?php

namespace LQL;

/**
 * Class StatsTest
 */
class StatsTest extends AbsTest
{
    protected $operator;

    public function __construct()
    {
        parent::__construct();
        $this->operator = new Operator('=');
    }

    protected function getObject()
    {
        return new Stats('name', $this->operator, 'value');
    }

    /**
     * @test
     * @testdox Should throw an exception for a invalid operator
     * 
     */
    public function invalidOperator()
    {
        $this->expectException('InvalidArgumentException');
        new Stats('name', new Operator('~~'), 'value');
    }

    /**
     * @test
     * @testdox Should convert object to a string like 'Stats: <name> <operator[=|!=]> <value>'
     */
    public function getString()
    {
        $expected = 'Stats: name = value';
        $this->verifyToString($expected, new Stats('name', $this->operator, 'value'));
    }

    /**
     * @test
     * @testdox Should create a Stats from a string
     */
    public function createFromString()
    {
        $expected = 'Stats: name = value';
        $this->verifyToString($expected, Stats::createFromString('name = value'));
    }
}
