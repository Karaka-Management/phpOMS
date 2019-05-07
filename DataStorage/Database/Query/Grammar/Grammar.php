<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    phpOMS\DataStorage\Database\Query\Grammar
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */
declare(strict_types=1);

namespace phpOMS\DataStorage\Database\Query\Grammar;

use phpOMS\DataStorage\Database\GrammarAbstract;
use phpOMS\DataStorage\Database\Query\Builder;
use phpOMS\DataStorage\Database\Query\Column;
use phpOMS\DataStorage\Database\Query\QueryType;
use phpOMS\DataStorage\Database\Query\Where;

/**
 * Database query grammar.
 *
 * @package    phpOMS\DataStorage\Database\Query\Grammar
 * @license    OMS License 1.0
 * @link       http://website.orange-management.de
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
     * Update components.
     *
     * @var string[]
     * @since 1.0.0
     */
    protected $deleteComponents = [
        'deletes',
        'from',
        'wheres',
    ];

    /**
     * Random components.
     *
     * @var string[]
     * @since 1.0.0
     */
    protected $randomComponents = [
        'random',
    ];

    /**
     * {@inheritdoc}
     */
    protected function getComponents(int $type) : array
    {
        switch ($type) {
            case QueryType::SELECT:
                return $this->selectComponents;
            case QueryType::INSERT:
                return $this->insertComponents;
            case QueryType::UPDATE:
                return $this->updateComponents;
            case QueryType::DELETE:
                return $this->deleteComponents;
            case QueryType::RANDOM:
                return $this->selectComponents;
            default:
                throw new \InvalidArgumentException('Unknown query type.');
        }
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
     */
    protected function compileSelects(Builder $query, array $columns) : string
    {
        $expression = $this->expressionizeTableColumn($columns, $query->getPrefix(), false);

        if ($expression === '') {
            $expression = '*';
        }

        return ($query->distinct ? 'SELECT DISTINCT ' : 'SELECT ') . $expression;
    }

    /**
     * Compile select.
     *
     * @param Builder $query Builder
     * @param array   $table Table
     *
     * @return string
     *
     * @since  1.0.0
     */
    protected function compileUpdates(Builder $query, array $table) : string
    {
        $expression = $this->expressionizeTableColumn($table, $query->getPrefix());

        if ($expression === '') {
            return '';
        }

        return 'UPDATE ' . $expression;
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
     */
    protected function compileDeletes(Builder $query, array $columns) : string
    {
        return 'DELETE';
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
     * @param bool    $first  Is first element (usefull for nesting)
     *
     * @return string
     *
     * @since  1.0.0
     */
    protected function compileWheres(Builder $query, array $wheres, bool $first = true) : string
    {
        $expression = '';

        foreach ($wheres as $key => $where) {
            foreach ($where as $key2 => $element) {
                $expression .= $this->compileWhereElement($element, $query, $first);
                $first       = false;
            }
        }

        if ($expression === '') {
            return '';
        }

        return 'WHERE ' . $expression;
    }

    /**
     * Compile where element.
     *
     * @param array   $element Element data
     * @param Builder $query   Query builder
     * @param bool    $first   Is first element (usefull for nesting)
     *
     * @return string
     *
     * @since  1.0.0
     */
    protected function compileWhereElement(array $element, Builder $query, bool $first = true) : string
    {
        $expression = '';

        if (!$first) {
            $expression = ' ' . \strtoupper($element['boolean']) . ' ';
        }

        if (\is_string($element['column'])) {
            // No prefix if it is ONLY a field
            $prefix = \stripos($element['column'], '.') === false ? '' : $query->getPrefix();

            $expression .= $this->compileSystem($element['column'], $prefix);
        } elseif ($element['column'] instanceof \Closure) {
            $expression .= $element['column']();
        } elseif ($element['column'] instanceof Builder) {
            $expression .= '(' . $element['column']->toSql() . ')';
        } elseif ($element['column'] instanceof Where) {
            $expression .= '(' . $this->compileWhere($element['column'], $query->getPrefix()) . ')';
        }

        if (isset($element['value'])) {
            $expression .= ' ' . \strtoupper($element['operator']) . ' ' . $this->compileValue($query, $element['value'], $query->getPrefix());
        } else {
            $operator    = \strtoupper($element['operator']) === '=' ? 'IS' : 'IS NOT';
            $expression .= ' ' . $operator . ' ' . $this->compileValue($query, $element['value'], $query->getPrefix());
        }

        return $expression;
    }

    protected function compileWhere(Where $where, string $prefix = '', bool $first = true) : string
    {

        return '';
    }

    /**
     * Compile value.
     *
     * @param Builder $query  Query builder
     * @param mixed   $value  Value
     * @param string  $prefix Prefix in case value is a table
     *
     * @return string returns a string representation of the value
     *
     * @throws \InvalidArgumentException throws this exception if the value to compile is not supported by this function
     *
     * @since  1.0.0
     */
    protected function compileValue(Builder $query, $value, string $prefix = '') : string
    {
        if (\is_string($value)) {
            if (\strpos($value, ':') === 0) {
                return $value;
            }

            return $query->quote($value);
        } elseif (\is_int($value)) {
            return (string) $value;
        } elseif (\is_array($value)) {
            $values = '';

            foreach ($value as $val) {
                $values .= $this->compileValue($query, $val, $prefix) . ', ';
            }

            return '(' . \rtrim($values, ', ') . ')';
        } elseif ($value instanceof \DateTime) {
            return $query->quote($value->format('Y-m-d H:i:s'));
        } elseif ($value === null) {
            return 'NULL';
        } elseif (\is_bool($value)) {
            return (string) ((int) $value);
        } elseif (\is_float($value)) {
            return \rtrim(\rtrim(\number_format($value, 5, '.', ''), '0'), '.');
        } elseif ($value instanceof Column) {
            return $this->compileSystem($value->getColumn(), $prefix);
        } elseif ($value instanceof Builder) {
            return '(' . \rtrim($value->toSql(), ';') . ')';
        } elseif ($value  instanceof \JsonSerializable) {
            $encoded = \json_encode($value);

            return $encoded ? $encoded : null;
        } elseif ($value  instanceof \Serializable) {
            return $element->serialize();
        } else {
            throw new \InvalidArgumentException(\gettype($value));
        }
    }

    /**
     * Compile limit.
     *
     * @param Builder $query Builder
     * @param int     $limit Limit
     *
     * @return string
     *
     * @since  1.0.0
     */
    protected function compileLimit(Builder $query, int $limit) : string
    {
        return 'LIMIT ' . $limit;
    }

    /**
     * Compile offset.
     *
     * @param Builder $query  Builder
     * @param int     $offset Offset
     *
     * @return string
     *
     * @since  1.0.0
     */
    protected function compileOffset(Builder $query, $offset) : string
    {
        return 'OFFSET ' . $offset;
    }

    /**
     * Compile joins.
     *
     * @param Builder $query Builder
     * @param array   $joins Joins
     *
     * @return string
     *
     * @since  1.0.0
     */
    protected function compileJoins(Builder $query, array $joins) : string
    {
        $expression = '';

        foreach ($joins as $key => $join) {
            $expression .= $join['type'] . ' ';
            $expression .= $this->compileSystem($join['table'], $query->getPrefix());
            $expression .= $this->compileOn($query, $query->ons[$key]);
        }

        $expression = \rtrim($expression, ', ');

        return $expression;
    }

    /**
     * Compile on.
     *
     * @param Builder $query Builder
     * @param array   $ons   On values
     * @param bool    $first Is first on element
     *
     * @return string
     *
     * @since  1.0.0
     */
    protected function compileOn(Builder $query, array $ons, bool $first = true) : string
    {
        $expression = '';

        foreach ($ons as $key => $on) {
            $expression .= $this->compileOnElement($on, $query, $first);
            $first       = false;
        }

        if ($expression === '') {
            return '';
        }

        return ' ON ' . $expression;
    }

    /**
     * Compile where element.
     *
     * @param array   $element Element data
     * @param Builder $query   Query builder
     * @param bool    $first   Is first element (usefull for nesting)
     *
     * @return string
     *
     * @since  1.0.0
     */
    protected function compileOnElement(array $element, Builder $query, bool $first = true) : string
    {
        $expression = '';

        if (!$first) {
            $expression = ' ' . \strtoupper($element['boolean']) . ' ';
        }

        if (\is_string($element['column'])) {
            // handle bug when no table is specified in the where column
            if (\count($query->from) === 1 && \stripos($element['column'], '.') === false) {
                $element['column'] = $query->from[0] . '.' . $element['column'];
            }

            $expression .= $this->compileSystem($element['column'], $query->getPrefix());
        } elseif ($element['column'] instanceof \Closure) {
            $expression .= $element['column']();
        } elseif ($element['column'] instanceof Builder) {
            $expression .= '(' . $element['column']->toSql() . ')';
        } elseif ($element['column'] instanceof Where) {
            $expression .= '(' . $this->compileWhere($element['column'], $query->getPrefix()) . ')';
        }

        if (isset($element['value'])) {
            $expression .= ' ' . \strtoupper($element['operator']) . ' ' . $this->compileSystem($element['value'], $query->getPrefix());
        }

        return $expression;
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
     */
    protected function compileGroups(Builder $query, array $groups)
    {
        $expression = '';

        foreach ($groups as $group) {
            $expression .= $this->compileSystem($group, $query->getPrefix()) . ', ';
        }

        $expression = \rtrim($expression, ', ');

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
     */
    protected function compileOrders(Builder $query, array $orders) : string
    {
        $expression = '';

        foreach ($orders as $key => $order) {
            foreach ($order as $column) {
                $expression .= $this->compileSystem($column, $query->getPrefix()) . ', ';
            }

            $expression  = \rtrim($expression, ', ');
            $expression .= ' ' . $key . ', ';
        }

        if ($expression === '') {
            return '';
        }

        $expression = \rtrim($expression, ', ');

        return 'ORDER BY ' . $expression;
    }

    protected function compileUnions()
    {
        return '';
    }

    protected function compileLock()
    {
        return '';
    }

    /**
     * Compile insert into table.
     *
     * @param Builder $query Builder
     * @param string  $table Table
     *
     * @return string
     *
     * @since  1.0.0
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
     */
    protected function compileInserts(Builder $query, array $columns) : string
    {
        $cols = '';

        foreach ($columns as $column) {
            $cols .= $this->compileSystem($column) . ', ';
        }

        if ($cols === '') {
            return '';
        }

        return '(' . \rtrim($cols, ', ') . ')';
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
     */
    protected function compileValues(Builder $query, array $values) : string
    {
        $vals = '';

        foreach ($values as $value) {
            $vals .= $this->compileValue($query, $value) . ', ';
        }

        if ($vals === '') {
            return '';
        }

        return 'VALUES ' . \rtrim($vals, ', ');
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
     */
    protected function compileSets(Builder $query, array $values) : string
    {
        $vals = '';

        foreach ($values as $column => $value) {
            $expression = $this->expressionizeTableColumn([$column], $query->getPrefix(), false);

            $vals .= $expression . ' = ' . $this->compileValue($query, $value) . ', ';
        }

        if ($vals === '') {
            return '';
        }

        return 'SET ' . \rtrim($vals, ', ');
    }
}
