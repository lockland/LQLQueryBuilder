<?php

namespace LQL;

/**
 * Class Filter
 */
class Filter implements FilterInterface
{
    /**
     * @var string $filter
     */
    protected $filter = 'Filter: ';

    /**
     * Construct
     *
     *
     * Value can be empty when you want compare a group columns
     *
     * Example:
     * If you want get hosts without parents you must use a filter like:
     *
     * <code>
     * GET hosts
     * Columns: name
     * Filter: parents =
     * </code>
     *
     * @param string $field Table column name
     * @param Operator $operator Logical operator
     * @param string $value Value to do logical operation
     *
     */
    public function __construct($column, Operator $operator, $value = '')
    {
        $this->filter .= "$column $operator $value";
    }

    public function __toString()
    {
        return $this->filter;
    }

    /**
     * Construct
     *
     * Create a Filter object from a string like: name = value
     *
     * @param string $filter
     *
     * @return Filter
     */
    public static function createFromString($filter)
    {
        $args = explode(' ', $filter);
        $operator = new Operator($args[1]);

        if (isset($args[2])) {
             return new self($args[0], $operator, $args[2]);
        }

        return new self($args[0], $operator);
    }
}
