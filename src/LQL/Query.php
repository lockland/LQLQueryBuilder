<?php

namespace LQL;

/**
 * Class Query
 */
class Query implements LQLObject
{
    protected $value;

    public function __construct($value)
    {
        $this->value = $value;
    }

    public function __toString()
    {
        return $this->value;
    }
}
