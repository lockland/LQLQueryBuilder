<?php

namespace LQL;

/**
 * Class Operator
 *
 * This class will represent LQL operators
 */
class Operator
{
    /**
     * @var string $operators Supported operators on LQL language
     */
    protected $operators = array(
        '=',
        '~',
        '=~',
        '~~',
        '<',
        '>',
        '<=',
        '>=',
        '!=',
        'And',
        'Or',
    );

    /**
     * @var string $value Current value
     */
    protected $operator;

    /**
     * Construct
     *
     * @param string $operator
     */
    public function __construct($operator)
    {
        $this->setOperator($operator);
    }

    /**
     * Set the operator character
     *
     * @param string $operator
     * @return void
     */
    protected function setOperator($operator)
    {
        $operator = ucfirst(strtolower($operator));

        if (! in_array($operator, $this->operators, true)) {
            throw new \InvalidArgumentException("Unsupported operator [$operator]");
        }

        $this->operator = $operator;
    }

    public function __toString()
    {
        return $this->operator;
    }
}
