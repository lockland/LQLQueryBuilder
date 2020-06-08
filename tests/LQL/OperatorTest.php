<?php

namespace LQL;

/**
 * Class OperatorTest
 */
class OperatorTest extends AbsTest
{
    /**
     * @test
     * @testdox Should create an Operator instance
     *
     * @dataProvider operators
     */
    public function constructor($operator)
    {
        $this->assertInstanceOf('LQL\Operator', new Operator($operator));
    }

    public function operators()
    {
        return array(
            array('='),
            array('~'),
            array('=~'),
            array('~~'),
            array('<'),
            array('>'),
            array('<='),
            array('>='),
            array('!='),
            array('And'),
            array('aNd'),
            array('anD'),
            array('AND'),
            array('and'),
            array('Or'),
            array('oR'),
            array('OR'),
            array('or'),
        );
    }

    protected function getObject()
    {
        return new Operator('=');
    }

    /**
     * @test
     * @testdox Should return the same operator passed on construct
     */
    public function getString()
    {
        $expected = '=';
        $this->verifyToString($expected, new Operator($expected));
    }
}
