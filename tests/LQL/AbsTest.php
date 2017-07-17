<?php

namespace LQL;

abstract class AbsTest extends \PHPUnit_Framework_TestCase
{

    abstract protected function getObject();

    /**
     * @testdox Should implement __toString method
     */
    public function isImplementingToString()
    {

        $this->assertTrue(
            method_exists($this->getObject(), '__toString'),
            'Object does not implementing __toString'
        );
    }

    protected function verifyToString($expected, $actual)
    {
        $this->assertEquals($expected, $actual);
    }
}
