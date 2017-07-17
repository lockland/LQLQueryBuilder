<?php

namespace LQL;

/**
 * Class QueryBuilder
 *
 * Create Query Object Step by Step
 */
class QueryBuilder
{
    const TABLE_HOSTS = 'hosts';

    const TABLE_SERVICES = 'services';

    const TABLE_HOSTGROUPS = 'hostgroups';

    const TABLE_SERVICEGROUPS = 'servicegroups';

    const TABLE_CONTACTGROUPS = 'contactgroups';

    const TABLE_SERVICESBYGROUP = 'servicesbygroup';

    const TABLE_SERVICESBYHOSTGROUP = 'servicesbyhostgroup';

    const TABLE_HOSTSBYGROUP = 'hostsbygroup';

    const TABLE_CONTACTS = 'contacts';

    const TABLE_COMMANDS = 'commands';

    const TABLE_TIMEPERIODS = 'timeperiods';

    const TABLE_DOWNTIMES = 'downtimes';

    const TABLE_COMMENTS = 'comments';

    const TABLE_LOG = 'log';

    const TABLE_STATUS = 'status';

    const TABLE_COLUMNS = 'columns';

    const TABLE_STATEHIST = 'statehist';

    protected $query = "GET ";

    protected $format = 'json';

    protected $columnHeaders = 'on';

    public function __construct($table)
    {
        $this->checkTable($table);
        $this->query .= $table;
    }

    private function checkTable($table)
    {
        $reflection = new \ReflectionClass(__CLASS__);
        if (! in_array($table, array_values($reflection->getConstants()), true)) {
            throw new \InvalidArgumentException("[$table] is an unsupported table");
        }
    }

    /**
     * Select which columns will be returned
     *
     * @param array|string  $columns
     * @param string    $ifs Input Field Separator
     *
     * @return $this
     */
    public function select($columns, $ifs = null)
    {
        if (! is_null($ifs)) {
            $columns = explode($ifs, $columns);
        }

        $this->query .= "\nColumns: " . implode(' ', $columns);
        return $this;
    }

    /**
     * Define filters
     *
     * @param Filter
     * @return $this
     */
    public function filterBy(FilterInterface $item)
    {
        $this->query .= "\n$item";
        return $this;
    }

    /**
     * Define Stats
     *
     * @param Filter
     * @return $this
     */
    public function statsFor(StatsInterface $item)
    {
        $this->query .= "\n$item";
        return $this;
    }

    /**
     * Add sort criteria to query
     *
     * @param string $column
     * @param string $order Sort order that must be asc|desc
     *
     * @return $this
     */
    public function sortBy($column, $order)
    {
        $this->query .= "\nSort: $column $order";
        return $this;
    }

    /**
     * Add a sort array to query
     *
     * The array with sort criterias may be either an array with string
     * formated like: "<column> <asc|desc>" or a multidimensional array
     * like: array(array('column', 'asc|desc'), array('column2', 'asc|desc'))
     *
     * @param array $sorts Array with sort criteria
     *
     * @return $this
     */
    public function sorts(array $sorts)
    {
        foreach ($sorts as $sort) {
            call_user_func_array(
                array($this, 'sortBy'),
                is_array($sort) ? $sort : explode(' ', $sort)
            );
        }
        return $this;
    }

    /**
     * Aggregate result stats
     *
     * Supports the below basic statistical operations
     *
     * Operations:
     *     sum
     *     min
     *     max
     *     avg
     *     std
     *     suminv
     *     avginv
     *
     * @param string $statsOperation Basic statistical operation
     * @param string $column
     *
     * @return $this
     */
    public function aggregateBy($statsOperation, $column)
    {
        $this->query .= "\nStats: $statsOperation $column";
        return $this;
    }

    /**
     * Add limit and offset token to query
     *
     * It will limit livestatus resultset
     *
     * $param int $limit Maximum amount of rows
     * $param int $offset Number of elements to skip, default ZERO
     *
     * @return $this
     */
    public function limit($limit, $offset = 0)
    {
        $this->query .= "\nLimit: $limit\nOffset: $offset";
        return $this;
    }

    /**
     * Add authenticated user name token
     *
     * @return $this
     */
    public function authUser($name)
    {
        $this->query .= "\nAuthUser: $name";
        return $this;
    }

    /**
     * Should Format output result
     *
     * @param string $format The output format
     * @param string $columnHeaders Enable or disable columns header, default 'on'
     *
     * @return $this
     */
    public function outputFormat($format, $columnHeaders = "on")
    {
        $this->format = empty($format) ? 'json' : $format;
        $this->columnHeaders = $columnHeaders;

        return $this;
    }

    /**
     * Create a Query object
     *
     * @return Query
     */
    public function createQuery()
    {
        return new Query($this->__toString());
    }

    public function __toString()
    {
        return  $this->query
            . "\nOutputFormat: $this->format"
            . "\nResponseHeader: fixed16"
            . "\nColumnHeaders: $this->columnHeaders";
    }
}
