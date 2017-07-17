<?php

namespace LQL;

/**
 * Class FilterTest
 */
class FilterTest extends AbsTest
{

    protected $operator;

    public function __construct()
    {
        $this->operator = new Operator('=');
    }

    /**
     * @testdox Should construct object
     */
    public function constructor()
    {
        $this->assertInstanceOf(
            'LQL\Filter',
            new Filter('name', $this->operator, 'value')
        );
    }

    /**
     * @testdox Should return a string like 'Filter: <column> <operator> <value>'
     */
    public function getString()
    {
        $this->verifyToString(
            'Filter: name = test',
            new Filter('name', $this->operator, 'test')
        );

        $this->verifyToString(
            'Filter: name = ',
            new Filter('name', $this->operator)
        );
    }

    protected function getObject()
    {
        return new Filter('parents', $this->operator);
    }

    /**
     * @testdox Should create a instance of Filter by a string
     */
    public function namedConstructor()
    {
        $this->verifyToString(
            'Filter: name = test',
            Filter::createFromString('name = test')
        );

        $this->verifyToString(
            'Filter: name = ',
            Filter::createFromString('name = ')
        );

        $this->verifyToString(
            'Filter: name = ',
            Filter::createFromString('name =')
        );
    }
}
