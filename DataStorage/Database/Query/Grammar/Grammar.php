<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\DataStorage\Database\Query\Grammar
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://karaka.app
 */
declare(strict_types=1);

namespace phpOMS\DataStorage\Database\Query\Grammar;

use phpOMS\Contract\SerializableInterface;
use phpOMS\DataStorage\Database\GrammarAbstract;
use phpOMS\DataStorage\Database\Query\Builder;
use phpOMS\DataStorage\Database\Query\Column;
use phpOMS\DataStorage\Database\Query\From;
use phpOMS\DataStorage\Database\Query\Parameter;
use phpOMS\DataStorage\Database\Query\QueryType;
use phpOMS\DataStorage\Database\Query\Where;

/**
 * Database query grammar.
 *
 * @package phpOMS\DataStorage\Database\Query\Grammar
 * @license OMS License 1.0
 * @link    https://karaka.app
 * @since   1.0.0
 *
 * @todo Karaka/phpOMS#33
 *  Implement missing grammar & builder functions
 *  Missing elements are e.g. sum, merge etc.
 */
class Grammar extends GrammarAbstract
{
    /**
     * Select components.
     *
     * @var string[]
     * @since 1.0.0
     */
    protected array $selectComponents = [
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
    protected array $insertComponents = [
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
    protected array $updateComponents = [
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
    protected array $deleteComponents = [
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
    protected array $randomComponents = [
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
                return $this->randomComponents;
            case queryType::NONE:
                return [];
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
     * @since 1.0.0
     */
    protected function compileSelects(Builder $query, array $columns) : string
    {
        $expression = $this->expressionizeTableColumn($columns, false);

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
     * @since 1.0.0
     */
    protected function compileUpdates(Builder $query, array $table) : string
    {
        $expression = $this->expressionizeTableColumn($table);

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
     * @since 1.0.0
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
     * @since 1.0.0
     */
    protected function compileFrom(Builder $query, array $table) : string
    {
        $expression = $this->expressionizeTableColumn($table);

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
     * @since 1.0.0
     */
    protected function compileWheres(Builder $query, array $wheres, bool $first = true) : string
    {
        $expression = '';

        foreach ($wheres as $where) {
            foreach ($where as $element) {
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
     * @since 1.0.0
     */
    protected function compileWhereElement(array $element, Builder $query, bool $first = true) : string
    {
        $expression = '';

        if (!$first) {
            $expression = ' ' . \strtoupper($element['boolean']) . ' ';
        }

        if (\is_string($element['column'])) {
            $expression .= $this->compileSystem($element['column']);
        } elseif ($element['column'] instanceof \Closure) {
            $expression .= $element['column']();
        } elseif ($element['column'] instanceof Where) {
            $where       = \rtrim($this->compileWhereQuery($element['column']), ';');
            $expression .= '(' . (\stripos($where, 'WHERE ') === 0 ? \substr($where, 6) : $where) . ')';
        } elseif ($element['column'] instanceof Builder) {
            $expression .= '(' . \rtrim($element['column']->toSql(), ';') . ')';
        }

        if (isset($element['value']) && (!empty($element['value']) || !\is_array($element['value']))) {
            $expression .= ' ' . \strtoupper($element['operator']) . ' ' . $this->compileValue($query, $element['value']);
        } elseif ($element['value'] === null && !($element['column'] instanceof Builder)) {
            $operator    = $element['operator'] === '=' ? 'IS' : 'IS NOT';
            $expression .= ' ' . $operator . ' ' . $this->compileValue($query, $element['value']);
        }

        return $expression;
    }

    /**
     * Compile where query.
     *
     * @param Where $where Where query
     *
     * @return string
     *
     * @since 1.0.0
     */
    protected function compileWhereQuery(Where $where) : string
    {
        return $where->toSql();
    }

    /**
     * Compile from query.
     *
     * @param From $from Where query
     *
     * @return string
     *
     * @since 1.0.0
     */
    protected function compileFromQuery(From $from) : string
    {
        return $from->toSql();
    }

    /**
     * Compile column query.
     *
     * @param column $column Where query
     *
     * @return string
     *
     * @since 1.0.0
     */
    protected function compileColumnQuery(column $column) : string
    {
        return $column->toSql();
    }

    /**
     * Compile value.
     *
     * @param Builder $query Query builder
     * @param mixed   $value Value
     *
     * @return string returns a string representation of the value
     *
     * @throws \InvalidArgumentException throws this exception if the value to compile is not supported by this function
     *
     * @since 1.0.0
     */
    protected function compileValue(Builder $query, mixed $value) : string
    {
        if (\is_string($value)) {
            return $query->quote($value);
        } elseif (\is_int($value)) {
            return (string) $value;
        } elseif (\is_array($value)) {
            $value  = \array_values($value);
            $count  = \count($value) - 1;
            $values = '(';

            for ($i = 0; $i < $count; ++$i) {
                $values .= $this->compileValue($query, $value[$i]) . ', ';
            }

            return $values . $this->compileValue($query, $value[$count]) . ')';
        } elseif ($value instanceof \DateTime) {
            return $query->quote($value->format($this->datetimeFormat));
        } elseif ($value === null) {
            return 'NULL';
        } elseif (\is_bool($value)) {
            return (string) ((int) $value);
        } elseif (\is_float($value)) {
            return \rtrim(\rtrim(\number_format($value, 5, '.', ''), '0'), '.');
        } elseif ($value instanceof Column) {
            return '(' . \rtrim($this->compileColumnQuery($value), ';') . ')';
        } elseif ($value instanceof Builder) {
            return '(' . \rtrim($value->toSql(), ';') . ')';
        } elseif ($value instanceof \JsonSerializable) {
            $encoded = \json_encode($value);

            return $encoded ? $encoded : 'NULL';
        } elseif ($value instanceof SerializableInterface) {
            return $value->serialize();
        } elseif ($value instanceof Parameter) {
            return $value->__toString();
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
     * @since 1.0.0
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
     * @since 1.0.0
     */
    protected function compileOffset(Builder $query, int $offset) : string
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
     * @since 1.0.0
     */
    protected function compileJoins(Builder $query, array $joins) : string
    {
        $expression = '';

        foreach ($joins as $table => $join) {
            $expression .= $join['type'] . ' ';

            if (\is_string($join['table'])) {
                $expression .= $this->compileSystem($join['table']) . (\is_string($join['alias']) ? ' as ' . $join['alias'] : '');
            } elseif ($join['table'] instanceof \Closure) {
                $expression .= $join['table']() . (\is_string($join['alias']) ? ' as ' . $join['alias'] : '');
            } elseif ($join['table'] instanceof Builder) {
                $expression .= '(' . \rtrim($join['table']->toSql(), ';') . ')' . (\is_string($join['alias']) ? ' as ' . $join['alias'] : '');
            }

            $expression .= $this->compileOn($query, $query->ons[$join['alias'] ?? $table]) . ' ';
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
     * @since 1.0.0
     */
    protected function compileOn(Builder $query, array $ons, bool $first = true) : string
    {
        $expression = '';

        foreach ($ons as $on) {
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
     * @since 1.0.0
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

            $expression .= $this->compileSystem($element['column']);
        } elseif ($element['column'] instanceof \Closure) {
            $expression .= $element['column']();
        } elseif ($element['column'] instanceof Builder) {
            $expression .= '(' . $element['column']->toSql() . ')';
        } elseif ($element['column'] instanceof Where) {
            $expression .= '(' . \rtrim($this->compileWhereQuery($element['column']), ';') . ')';
        }

        if (isset($element['value'])) {
            $expression .= ' ' . \strtoupper($element['operator']) . ' ' . $this->compileSystem($element['value']);
        } else {
            $operator    = $element['operator'] === '=' ? 'IS' : 'IS NOT';
            $expression .= ' ' . $operator . ' ' . $this->compileValue($query, $element['value']);
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
     * @since 1.0.0
     */
    protected function compileGroups(Builder $query, array $groups) : string
    {
        $expression = '';

        foreach ($groups as $group) {
            $expression .= $this->compileSystem($group) . ', ';
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
     * @since 1.0.0
     */
    protected function compileOrders(Builder $query, array $orders) : string
    {
        $expression    = '';
        $lastOrderType = '';

        foreach ($orders as $column => $order) {
            $expression .= $this->compileSystem($column) . ' ' . $order . ', ';
        }

        $expression = \rtrim($expression, ', ');

        if ($expression === '') {
            return '';
        }

        return 'ORDER BY ' . $expression;
    }

    /**
     * Compile unions.
     *
     * @return string
     *
     * @since 1.0.0
     */
    protected function compileUnions() : string
    {
        return '';
    }

    /**
     * Compile lock.
     *
     * @return string
     *
     * @since 1.0.0
     */
    protected function compileLock() : string
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
     * @since 1.0.0
     */
    protected function compileInto(Builder $query, string $table) : string
    {
        return 'INSERT INTO ' . $this->compileSystem($table);
    }

    /**
     * Compile insert into columns.
     *
     * @param Builder $query   Builder
     * @param array   $columns Columns
     *
     * @return string
     *
     * @since 1.0.0
     */
    protected function compileInserts(Builder $query, array $columns) : string
    {
        $count = \count($columns) - 1;

        if ($count === -1) {
            return '';
        }

        $cols = '(';
        for ($i = 0; $i < $count; ++$i) {
            $cols .= $this->compileSystem($columns[$i]) . ', ';
        }

        return $cols .= $this->compileSystem($columns[$count]) . ')';
    }

    /**
     * Compile insert values.
     *
     * @param Builder $query  Builder
     * @param array   $values Values
     *
     * @return string
     *
     * @since 1.0.0
     */
    protected function compileValues(Builder $query, array $values) : string
    {
        $values = \array_values($values);
        $count  = \count($values) - 1;
        if ($count === -1) {
            return '';
        }

        $vals = 'VALUES ';
        for ($i = 0; $i < $count; ++$i) {
            $vals .= $this->compileValue($query, $values[$i]) . ', ';
        }

        return $vals . $this->compileValue($query, $values[$count]);
    }

    /**
     * Compile insert values.
     *
     * @param Builder $query  Builder
     * @param array   $values Values
     *
     * @return string
     *
     * @since 1.0.0
     */
    protected function compileSets(Builder $query, array $values) : string
    {
        $vals = '';

        foreach ($values as $column => $value) {
            $expression = $this->expressionizeTableColumn([$column], false);

            $vals .= $expression . ' = ' . $this->compileValue($query, $value) . ', ';
        }

        if ($vals === '') {
            return '';
        }

        return 'SET ' . \rtrim($vals, ', ');
    }
}
