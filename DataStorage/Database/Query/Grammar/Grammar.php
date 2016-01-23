<?php
/**
 * Orange Management
 *
 * PHP Version 7.0
 *
 * @category   TBD
 * @package    TBD
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @copyright  2013 Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://orange-management.com
 */

namespace phpOMS\DataStorage\Database\Query\Grammar;

use phpOMS\DataStorage\Database\BuilderAbstract;
use phpOMS\DataStorage\Database\GrammarAbstract;
use phpOMS\DataStorage\Database\Query\Builder;
use phpOMS\DataStorage\Database\Query\Column;
use phpOMS\DataStorage\Database\Query\QueryType;

/**
 * Database query grammar.
 *
 * @category   Framework
 * @package    phpOMS\DataStorage\Database
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
class Grammar extends GrammarAbstract
{
    /**
     * Select components.
     *
     * @var string[]
     * @since 1.0.0
     */
    protected $selectComponents = [
        'aggregate',
        'selects',
        'from',
        'joins',
        'wheres',
        'havings',
        'groups',
        'orders',
        'limit',
        'offset',
        'unions',
        'lock',
    ];

    /**
     * Insert components.
     *
     * @var string[]
     * @since 1.0.0
     */
    protected $insertComponents = [
        'into',
        'inserts',
        'values',
    ];

    /**
     * Update components.
     *
     * @var string[]
     * @since 1.0.0
     */
    protected $updateComponents = [
        'updates',
        'sets',
        'wheres',
    ];

    /**
     * Compile components.
     *
     * @param BuilderAbstract $query Builder
     *
     * @return string[]
     *
     * @throws
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    protected function compileComponents(BuilderAbstract $query) : array
    {
        $sql = [];

        switch ($query->getType()) {
            case QueryType::SELECT:
                $components = $this->selectComponents;
                break;
            case QueryType::INSERT:
                $components = $this->insertComponents;
                break;
            case QueryType::UPDATE:
                $components = [];
                break;
            case QueryType::DELETE:
                $components = [];
                break;
            default:
                throw new \InvalidArgumentException('Unknown query type.');
        }

        /* Loop all possible query components and if they exist compile them. */
        foreach ($components as $component) {
            if (isset($query->{$component}) && !empty($query->{$component})) {
                $sql[$component] = $this->{'compile' . ucfirst($component)}($query, $query->{$component});
            }
        }

        return $sql;
    }

    /**
     * Compile select.
     *
     * @param Builder $query   Builder
     * @param array   $columns Columns
     *
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    protected function compileSelects(Builder $query, array $columns) : string
    {
        $expression = $this->expressionizeTableColumn($columns, $query->getPrefix());

        if ($expression === '') {
            $expression = '*';
        }

        return ($query->distinct ? 'SELECT DISTINCT ' : 'SELECT ') . $expression;
    }

    /**
     * Compile from.
     *
     * @param Builder $query Builder
     * @param array   $table Tables
     *
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    protected function compileFrom(Builder $query, array $table) : string
    {
        $expression = $this->expressionizeTableColumn($table, $query->getPrefix());

        if ($expression === '') {
            return '';
        }

        return 'FROM ' . $expression;
    }

    /**
     * Compile where.
     *
     * @param Builder $query  Builder
     * @param array   $wheres Where elmenets
     * @param bool   $first  Is first element (usefull for nesting)
     *
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    protected function compileWheres(Builder $query, array $wheres, bool $first = true) : string
    {
        $expression = '';

        foreach ($wheres as $key => $where) {
            foreach ($where as $key2 => $element) {
                if (!$first) {
                    $expression .= ' ' . strtoupper($element['boolean']) . ' ';
                }

                if (is_string($element['column'])) {
                    $expression .= $this->compileSystem($element['column'], $query->getPrefix()) . ' ' . strtoupper($element['operator']) . ' ' . $this->compileValue($element['value'], $query->getPrefix());
                } elseif ($element['column'] instanceof \Closure) {
                } elseif ($element['column'] instanceof Builder) {
                }

                if (is_string($element['value'])) {
                } elseif ($element['value'] instanceof \Closure) {
                } elseif ($element['value'] instanceof Builder) {
                } elseif ($element['value'] instanceof \DateTime) {
                } elseif (is_int($element['value'])) {
                } elseif (is_bool($element['value'])) {
                } elseif (is_null($element['value'])) {
                } elseif (is_float($element['value'])) {
                } elseif (is_array($element['value'])) {
                    // is bind
                }

                $first = false;
            }
        }

        if ($expression == '') {
            return '';
        }

        return 'WHERE ' . $expression;
    }

    /**
     * Compile value.
     *
     * @param array|string|\Closure $value  Value
     * @param string               $prefix Prefix in case value is a table
     *
     * @return string
     *
     * @throws \InvalidArgumentException
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    protected function compileValue($value, $prefix = '') : string
    {
        if (is_string($value)) {
            return $this->valueQuotes . $value . $this->valueQuotes;
        } elseif (is_int($value)) {
            return $value;
        } elseif (is_array($value)) {
            $values = '';

            foreach ($value as $val) {
                $values .= $this->compileValue($val) . ', ';
            }

            return '(' . rtrim($values, ', ') . ')';
        } elseif ($value instanceof \DateTime) {
            return $this->valueQuotes . $value->format('Y-m-d h:m:s') . $this->valueQuotes;
        } elseif (is_null($value)) {
            return 'NULL';
        } elseif (is_bool($value)) {
            return (string) ((int) $value);
        } elseif ($value instanceof Column) {
            return $this->compileSystem($value->getColumn(), $prefix);
        } else {
            throw new \InvalidArgumentException();
        }
    }

    /**
     * Compile limit.
     *
     * @param Builder $query Builder
     * @param int    $limit Limit
     *
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    protected function compileLimit(Builder $query, $limit) : string
    {
        return 'LIMIT ' . $limit;
    }

    /**
     * Compile offset.
     *
     * @param Builder $query  Builder
     * @param int    $offset Offset
     *
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    protected function compileOffset(Builder $query, $offset) : string
    {
        return 'OFFSET ' . $offset;
    }

    private function compileJoins()
    {
        return '';
    }

    /**
     * Compile offset.
     *
     * @param Builder $query  Builder
     * @param array   $groups Groups
     *
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    private function compileGroups(Builder $query, array $groups)
    {
        $expression = '';

        foreach ($groups as $group) {
            $expression .= $this->compileSystem($group, $query->getPrefix()) . ', ';
        }

        $expression = rtrim($expression, ', ');

        return 'GROUP BY ' . $expression;
    }

    /**
     * Compile offset.
     *
     * @param Builder $query  Builder
     * @param array   $orders Order
     *
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    private function compileOrders(Builder $query, array $orders) : string
    {
        $expression = '';

        foreach ($orders as $order) {
            $expression .= $this->compileSystem($order['column'], $query->getPrefix()) . ' ' . $order['order'] . ', ';
        }

        if ($expression == '') {
            return '';
        }

        $expression = rtrim($expression, ', ');

        return 'ORDER BY ' . $expression;
    }

    private function compileUnions()
    {
        return '';
    }

    private function compileLock()
    {
        return '';
    }

    /**
     * Compile insert into table.
     *
     * @param Builder $query Builder
     * @param string $table Table
     *
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    protected function compileInto(Builder $query, $table) : string
    {
        return 'INSERT INTO ' . $this->compileSystem($table, $query->getPrefix());
    }

    /**
     * Compile insert into columns.
     *
     * @param Builder $query   Builder
     * @param array   $columns Columns
     *
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    protected function compileInserts(Builder $query, array $columns) : string
    {
        $cols = '';

        foreach ($columns as $column) {
            $cols .= $this->compileSystem($column) . ', ';
        }

        if ($cols == '') {
            return '';
        }

        return '(' . rtrim($cols, ', ') . ')';
    }

    /**
     * Compile insert values.
     *
     * @param Builder $query  Builder
     * @param array   $values Values
     *
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    protected function compileValues(Builder $query, array $values) : string
    {
        $vals = '';

        foreach ($values as $value) {
            $vals .= $this->compileValue($value) . ', ';
        }

        if ($vals == '') {
            return '';
        }

        return 'VALUES ' . rtrim($vals, ', ');
    }
}
