<?php

namespace LQL;

use LQL\Group\AbsGroup;
use LQL\Group\Filter as FilterGroup;
use LQL\Group\Stats as StatsGroup;
use LQL\Filter;
use LQL\Stats;

/**
 * Class Factory
 *
 * This class will helping to create LQL objects
 */
abstract class Factory
{
    /**
     * Returns a QueryBuilder object
     *
     * $return Table
     */
    public static function from($table)
    {
        return new QueryBuilder($table);
    }

    /**
     * Returns a Filter or FilterGroup
     *
     * For create a Filter the $data array must be a simple array like: array(<column>, <operator>, <value>).
     * But for create a FilterGroup the $data array must be a multidimensional array like:
     * array(array(<column>, <operator>, <value>), array(<column2>, <operator2>, <value2>)) and set up
     * the group operator
     *
     * @param array $data Filter data
     * @param Operator $operator Group operator
     * @param bool $negate Negate group flag
     *
     * $return mixed
     */
    public static function createFilter(array $data, Operator $operator = null, $negate = false)
    {
        $new = function ($args) {
            return new Filter($args[0], $args[1], $args[2]);
        };

        if (is_null($operator)) {
            return $new($data);
        }

        return self::fillGroup(new FilterGroup($operator), $data, $new, $negate);
    }

    /**
     * Returns a Stats or StatsGroup
     *
     * For create a Stats the $data array must be a simple array like: array(<column>, <operator>, <value>).
     * But for create a StatsGroup the $data array must be a multidimensional array like:
     * array(array(<column>, <operator>, <value>), array(<column2>, <operator2>, <value2>)) and set up
     * the group operator
     *
     * @param array $data Stats data
     * @param Operator $operator Group operator
     * @param bool $negate Negate group flag
     *
     * @return mixed
     */
    public static function createStats(array $data, Operator $operator = null, $negate = false)
    {
        $new = function ($args) {
            return new Stats($args[0], $args[1], $args[2]);
        };

        if (is_null($operator)) {
            return $new($data);
        }

        return self::fillGroup(new StatsGroup($operator), $data, $new, $negate);
    }

    private static function fillGroup(AbsGroup $group, array $data, \Closure $new, $negate = false)
    {
        $group->negate($negate);

        foreach ($data as $args) {
            $group->add($new($args));
        }

        return $group;
    }

    /**
     * Returns a Operator object
     *
     * @param string $operator
     *
     * @return Operator
     */
    public static function createOperator($operator)
    {
        return new Operator($operator);
    }
}
