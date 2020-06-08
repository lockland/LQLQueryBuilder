<?php

namespace LQL;

use InvalidArgumentException;

class ResultSet
{
    /**
     * Fetch rows as a stdclass
     */
    const FETCH_OBJ = 0;

    /**
     * Fetch rows as an array
     */
    const FETCH_ASSOC = 1;

    /**
     * @var int
     */
    protected $statusCode;

    /**
     * @var string
     */
    protected $data;

    /**
     * @var string $format
     */
    protected $format;

    /**
     * @var int $total Total rows
     */
    protected $total;

    /**
     * Contructor
     *
     * @param $statusCode
     * @param $data
     * @param $format
     */
    public function __construct($statusCode, $data, $format)
    {
        $this->format = $format;
        list($this->data, $this->total) = $this->decode($data);
        $this->data = $this->parse($this->data);
        $this->statusCode = $statusCode;
    }

    /**
     * 200  OK. Reponse contains the queried data.
     * 400  The request contains an invalid header.
     * 403  The user is not authorized (see AuthHeader)
     * 404  The target of the GET has not been found (e.g. the table).
     * 450  A non-existing column was being referred to
     * 451  The request is incomplete.
     * 452  The request is completely invalid.
     *
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @return string
     */
    public function fetchAll($mode = self::FETCH_OBJ)
    {
        return (self::FETCH_OBJ == $mode)
            ? json_decode(json_encode($this->data))
            : $this->data;
    }

    /**
     * Decode input data from mklivestatus
     *
     * @return array {
     *     @var array[] Data decoded
     *     @var int     Rows count with exists otherwise ZERO
     * }
     */
    protected function decode($data)
    {
        if ('json' == strtolower($this->format)) {
            return array(json_decode($data), 0);
        }

        if ('wrapped_json' == strtolower($this->format)) {
            $obj = json_decode($data);
            return array(
                array_merge($obj->columns, $obj->data),
                $obj->total_count
            );
        }

        return array(array_map('str_getcsv', str_getcsv($data, "\n")), 0);
    }

    protected function parse($data)
    {
        $header = array_shift($data);
        return array_map(function ($item) use ($header) {
            return array_combine($header, $item);
        }, $data);
    }

    /**
     * Append all custom variables passed by parameter to result
     *
     * @param array $customVars Custom variable names
     */
    public function appendCustomVariables(array $customVars)
    {
        if (empty($customVars)) {
            return;
        }

        /**
         * I tried using functional programing here but it have
         * appered really slow such twice more slow. So I used
         * procedural programing
         */
        foreach ($this->data as $index => $row) {
            foreach ($row['custom_variables'] as $customVarName => $value) {
                if (in_array(strtoupper($customVarName), $customVars)) {
                    $this->data[$index][$customVarName] = $value;
                }
            }
            unset($this->data[$index]['custom_variables']);
        }
    }

    public function getTotalCount()
    {
        return $this->total;
    }
}
