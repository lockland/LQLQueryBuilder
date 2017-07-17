<?php

namespace LQL\Group;

use LQL\Operator;

/**
 * Class AbsGroup
 */
abstract class AbsGroup
{
    /**
     * @var array $operators Supported group operators
     */
    protected $operators = array(
        'And',
        'Or'
    );

    /**
     * @var int $counter Group members counter
     */
    protected $counter = 0;

    /**
     * @var bool $negate Negate group
     */
    protected $negate = false;

    /**
     * @var int $minimalLength
     */
    protected $minimalLength = 2;

    /**
     * @var Operator $operator
     */
    protected $operator;

    /**
     * @var string $query
     */
    protected $query = '';

    /**
     * Construct
     *
     * @param Operator $operator An And or Or operator
     */
    public function __construct(Operator $operator)
    {
        $this->checkOperator($operator);
        $this->operator = $operator;
    }

    private function checkOperator(Operator $operator)
    {
        if (! in_array($operator->__toString(), $this->operators, true)) {
            throw new \InvalidArgumentException('Unsupported operator use: And or Or');
        }
    }

    /**
     * Add a item to the group
     *
     * @param mixed $item
     *
     * @return $this;
     */
    public function add($item)
    {
        $this->query .= "$item\n";
        $this->counter++;

        return $this;
    }

    /**
     * Negate group
     *
     * @return void
     */
    public function negate($negate = true)
    {
        $this->negate = (bool) $negate;
    }

    /**
     * Convert Object to String
     */
    public function __toString()
    {
        $this->checkQuantity();

        $this->query .= $this->getOperator() . ": $this->counter";

        if ($this->negate) {
            $this->query .= "\n" . $this->getNegateString();
        }

        return $this->query;
    }

    private function checkQuantity()
    {
        if ($this->counter < $this->minimalLength) {
            throw new \RuntimeException('Minimal Length for a group is ' . $this->minimalLength);
        }
    }

    abstract protected function getNegateString();

    abstract protected function getOperator();
}
