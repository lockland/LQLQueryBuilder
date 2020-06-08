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
     * @param string $table
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
     * For create a Filter the $data array must be a simple array like:
     * <code>
     *      array(<column>, <operator>, <value>);
     * </code>
     *
     * But for create a FilterGroup the operator must be setted up and
     * $data be a multidimensional array like:
     *
     * <code>
     *      array(
     *          array(<column>, <operator>, <value>),
     *          array(<column2>, <operator2>, <value2>)
     *      );
     * </code>
     *
     * @param array $data Filter data
     * @param Operator $groupOperator Group operator
     * @param bool $negate Negate group flag
     *
     * $return mixed
     */
    public static function createFilter(array $data, Operator $groupOperator = null, $negate = false)
    {
        $new = function ($args) {
            return new Filter($args[0], $args[1], $args[2]);
        };

        if (is_null($groupOperator)) {
            return $new($data);
        }

        return self::fillGroup(new FilterGroup($groupOperator), $data, $new, $negate);
    }

    /**
     * Returns a Stats or StatsGroup
     *
     * For create a Stats the $data array must be a simple array like:
     * <code>
     *      array(<column>, <operator>, <value>);
     * </code>
     *
     * But for create a StatsGroup the operator must be setted up and
     * $data be a multidimensional array like:
     *
     * <code>
     *      array(
     *          array(<column>, <operator>, <value>),
     *          array(<column2>, <operator2>, <value2>)
     *      );
     * </code>
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
            $element = ($args instanceof AbsGroup) ? $args : $new($args);
            $group->add($element);
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
