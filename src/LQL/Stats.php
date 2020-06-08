<?php

namespace LQL;

/**
 * Class Stats
 *
 * This class will represent LQL Stats
 */
class Stats implements StatsInterface
{
    /**
     * @var array $operators Supported operators
     */
    protected $operators = array(
        '=',
        '!=',
        '<=',
        '>=',
        '<',
        '>'
    );

    /**
     * @var string $stats
     */
    protected $stats = 'Stats: ';

    /**
     * Construct
     *
     * @param string $column Table column name
     * @param Operator $operator Logical Operator
     * @param $string $value Value to do logical operation
     */
    public function __construct($column, Operator $operator, $value)
    {
        $this->checkOperator($operator);
        $this->stats .= "$column $operator $value";
    }

    protected function checkOperator($operator)
    {
        if (! in_array($operator->__toString(), $this->operators)) {
            throw new \InvalidArgumentException(
                sprintf(
                    'Unsupported operator use "%s"',
                    implode('" or "', $this->operators)
                )
            );
        }
    }

    public function __toString()
    {
        return $this->stats;
    }

    /**
     * Construct
     *
     * Create a Stats object from a string like: name = value
     *
     * @param string $stats
     *
     * @return Stats
     */
    public static function createFromString($stats)
    {
        $args = explode(' ', $stats);
        $operator = new Operator($args[1]);

        return new self($args[0], $operator, $args[2]);
    }
}
