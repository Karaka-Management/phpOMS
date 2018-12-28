<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    phpOMS\DataStorage\Database\Query
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */
declare(strict_types=1);

namespace phpOMS\DataStorage\Database\Query;

use phpOMS\DataStorage\Database\BuilderAbstract;
use phpOMS\DataStorage\Database\Connection\ConnectionAbstract;

/**
 * Database query builder.
 *
 * @package    phpOMS\DataStorage\Database\Query
 * @license    OMS License 1.0
 * @link       http://website.orange-management.de
 * @since      1.0.0
 */
class Builder extends BuilderAbstract
{
    /**
     * Is read only.
     *
     * @var bool
     * @since 1.0.0
     */
    private $isReadOnly = false;

    /**
     * Columns.
     *
     * @var array
     * @since 1.0.0
     */
    public $selects = [];

    /**
     * Columns.
     *
     * @var array
     * @since 1.0.0
     */
    public $updates = [];

    /**
     * Stupid work around because value needs to be not null for it to work in Grammar.
     *
     * @var array
     * @since 1.0.0
     */
    public $deletes = [1];

    /**
     * Into.
     *
     * @var \Closure|string
     * @since 1.0.0
     */
    public $into = null;

    /**
     * Into columns.
     *
     * @var array
     * @since 1.0.0
     */
    public $inserts = [];

    /**
     * Into columns.
     *
     * @var array
     * @since 1.0.0
     */
    public $values = [];

    /**
     * Into columns.
     *
     * @var array
     * @since 1.0.0
     */
    public $sets = [];

    /**
     * Distinct.
     *
     * @var bool
     * @since 1.0.0
     */
    public $distinct = false;

    /**
     * From.
     *
     * @var array
     * @since 1.0.0
     */
    public $from = [];

    /**
     * Joins.
     *
     * @var array
     * @since 1.0.0
     */
    public $joins = [];

    /**
     * Ons of joins.
     *
     * @var array
     * @since 1.0.0
     */
    public $ons = [];

    /**
     * Where.
     *
     * @var array
     * @since 1.0.0
     */
    public $wheres = [];

    /**
     * Group.
     *
     * @var array
     * @since 1.0.0
     */
    public $groups = [];

    /**
     * Order.
     *
     * @var array
     * @since 1.0.0
     */
    public $orders = [];

    /**
     * Limit.
     *
     * @var int
     * @since 1.0.0
     */
    public $limit = null;

    /**
     * Offset.
     *
     * @var int
     * @since 1.0.0
     */
    public $offset = null;

    /**
     * Binds.
     *
     * @var array
     * @since 1.0.0
     */
    private $binds = [];

    /**
     * Union.
     *
     * @var array
     * @since 1.0.0
     */
    public $unions = [];

    /**
     * Lock.
     *
     * @var bool
     * @since 1.0.0
     */
    public $lock = false;

    /**
     * Comparison OPERATORS.
     *
     * @var string[]
     * @since 1.0.0
     */
    public const OPERATORS = [
        '=',
        '<',
        '>',
        '<=',
        '>=',
        '<>',
        '!=',
        'like',
        'like binary',
        'not like',
        'between',
        'ilike',
        '&',
        '|',
        '^',
        '<<',
        '>>',
        'rlike',
        'regexp',
        'not regexp',
        '~',
        '~*',
        '!~',
        '!~*',
        'similar to',
        'not similar to',
        'in',
    ];

    /**
     * Constructor.
     *
     * @param ConnectionAbstract $connection Database connection
     * @param bool               $readOnly   Query is read only
     *
     * @since  1.0.0
     */
    public function __construct(ConnectionAbstract $connection, bool $readOnly = false)
    {
        $this->isReadOnly = $readOnly;
        $this->setConnection($connection);
    }

    /**
     * Set connection for grammar.
     *
     * @param ConnectionAbstract $connection Database connection
     *
     * @return void
     *
     * @since  1.0.0
     */
    public function setConnection(ConnectionAbstract $connection) : void
    {
        $this->connection = $connection;
        $this->grammar    = $connection->getGrammar();
    }

    /**
     * Select.
     *
     * @param array ...$columns Columns
     *
     * @return Builder
     *
     * @todo   Closure is not working this way, needs to be evaluated befor assigning
     *
     * @since  1.0.0
     */
    public function select(...$columns) : self
    {
        $this->type = QueryType::SELECT;

        foreach ($columns as $key => $column) {
            if (\is_string($column) || $column instanceof \Closure) {
                $this->selects[] = $column;
            } else {
                throw new \InvalidArgumentException();
            }
        }

        return $this;
    }

    /**
     * Select.
     *
     * @param array ...$columns Columns
     *
     * @return Builder
     *
     * @todo   Closure is not working this way, needs to be evaluated befor assigning
     *
     * @since  1.0.0
     */
    public function random(...$columns) : self
    {
        $this->select(...$columns);

        $this->type = QueryType::RANDOM;

        return $this;
    }

    /**
     * Bind parameter.
     *
     * @param mixed $binds Binds
     *
     * @return Builder
     *
     * @since  1.0.0
     */
    public function bind($binds) : self
    {
        if (\is_array($binds)) {
            $this->binds += $binds;
        } elseif (\is_string($binds) || $binds instanceof \Closure) {
            $this->binds[] = $binds;
        } else {
            throw new \InvalidArgumentException();
        }

        return $this;
    }

    /**
     * Creating new.
     *
     * @return Builder
     *
     * @since  1.0.0
     */
    public function newQuery() : self
    {
        return new static($this->connection, $this->isReadOnly);
    }

    /**
     * Parsing to sql string.
     *
     * @return string
     *
     * @since  1.0.0
     */
    public function toSql() : string
    {
        return $this->grammar->compileQuery($this);
    }

    /**
     * Set raw query.
     *
     * @param string $raw Raw query
     *
     * @return Builder
     *
     * @throws \Exception
     *
     * @since  1.0.0
     */
    public function raw(string $raw) : self
    {
        if (!$this->isValidReadOnly($raw)) {
            throw new \Exception();
        }

        $this->type = QueryType::RAW;
        $this->raw  = \rtrim($raw, ';');

        return $this;
    }

    /**
     * Tests if a string contains a non read only component in case the builder is read only.
     * If the builder is not read only it will always return true
     *
     * @param string $raw Raw query
     *
     * @return bool
     *
     * @since  1.0.0
     */
    private function isValidReadOnly($raw) : bool
    {
        if (!$this->isReadOnly) {
            return true;
        }

        if (\stripos($raw, 'insert') !== false
            || \stripos($raw, 'update') !== false
            || \stripos($raw, 'drop') !== false
            || \stripos($raw, 'delete') !== false
            || \stripos($raw, 'create') !== false
            || \stripos($raw, 'alter') !== false
        ) {
            return false;
        }

        return true;
    }

    /**
     * Make raw column selection.
     *
     * @param \Closure|string $expression Raw expression
     *
     * @return Builder
     *
     * @since  1.0.0
     */
    public function selectRaw($expression) : self
    {
        $this->selects[null][] = $expression;

        return $this;
    }

    /**
     * Is distinct.
     *
     * @return Builder
     *
     * @since  1.0.0
     */
    public function distinct() : self
    {
        $this->distinct = true;

        return $this;
    }

    /**
     * From.
     *
     * @param array ...$tables Tables
     *
     * @return Builder
     *
     * @since  1.0.0
     */
    public function from(...$tables) : self
    {
        foreach ($tables as $key => $table) {
            if (\is_string($table) || $table instanceof \Closure) {
                $this->from[] = $table;
            } else {
                throw new \InvalidArgumentException();
            }
        }

        return $this;
    }

    /**
     * Make raw from.
     *
     * @param array|\Closure|string $expression Expression
     *
     * @return Builder
     *
     * @since  1.0.0
     */
    public function fromRaw($expression) : self
    {
        $this->from[null][] = $expression;

        return $this;
    }

    /**
     * Where.
     *
     * @param array|\Closure|string|Where $columns  Columns
     * @param array|string                $operator Operator
     * @param mixed                       $values   Values
     * @param array|string                $boolean  Boolean condition
     *
     * @return Builder
     *
     * @throws \InvalidArgumentException
     *
     * @since  1.0.0
     */
    public function where($columns, $operator = null, $values = null, $boolean = 'and') : self
    {
        if (!\is_array($columns)) {
            $columns  = [$columns];
            $operator = [$operator];
            $values   = [$values];
            $boolean  = [$boolean];
        }

        $i = 0;
        foreach ($columns as $key => $column) {
            if (isset($operator[$i]) && !\in_array(\strtolower($operator[$i]), self::OPERATORS)) {
                throw new \InvalidArgumentException('Unknown operator.');
            }

            $this->wheres[self::getPublicColumnName($column)][] = [
                'column'   => $column,
                'operator' => $operator[$i],
                'value'    => $values[$i],
                'boolean'  => $boolean[$i],
            ];

            $i++;
        }

        return $this;
    }

    /**
     * Get column of where condition
     *
     * One column can have multiple where conditions.
     * TODO: maybe think about a case where there is a where condition but no column but some other identifier?
     *
     * @param mixed $column Column
     *
     * @return null|array
     *
     * @since  1.0.0
     */
    public function getWhereByColumn($column) : ?array
    {
        return $this->wheres[self::getPublicColumnName($column)] ?? null;
    }

    /**
     * Where and sub condition.
     *
     * @param array|\Closure|string|Where $where    Where sub condition
     * @param mixed                       $operator Operator
     * @param mixed                       $values   Values
     *
     * @return Builder
     *
     * @since  1.0.0
     */
    public function andWhere($where, $operator = null, $values = null) : self
    {
        return $this->where($where, $operator, $values, 'and');
    }

    /**
     * Where or sub condition.
     *
     * @param array|\Closure|string|Where $where    Where sub condition
     * @param mixed                       $operator Operator
     * @param mixed                       $values   Values
     *
     * @return Builder
     *
     * @since  1.0.0
     */
    public function orWhere($where, $operator = null, $values = null) : self
    {
        return $this->where($where, $operator, $values, 'or');
    }

    /**
     * Where in.
     *
     * @param array|\Closure|string|Where $column  Column
     * @param mixed                       $values  Values
     * @param string                      $boolean Boolean condition
     *
     * @return Builder
     *
     * @since  1.0.0
     */
    public function whereIn($column, $values = null, string $boolean = 'and') : self
    {
        $this->where($column, 'in', $values, $boolean);

        return $this;
    }

    /**
     * Where null.
     *
     * @param array|\Closure|string|Where $column  Column
     * @param string                      $boolean Boolean condition
     *
     * @return Builder
     *
     * @since  1.0.0
     */
    public function whereNull($column, string $boolean = 'and') : self
    {
        $this->where($column, '=', null, $boolean);

        return $this;
    }

    /**
     * Where not null.
     *
     * @param array|\Closure|string|Where $column  Column
     * @param string                      $boolean Boolean condition
     *
     * @return Builder
     *
     * @since  1.0.0
     */
    public function whereNotNull($column, string $boolean = 'and') : self
    {
        $this->where($column, '!=', null, $boolean);

        return $this;
    }

    /**
     * Group by.
     *
     * @param array|\Closure|string ...$columns Grouping result
     *
     * @return Builder
     *
     * @since  1.0.0
     */
    public function groupBy(...$columns) : self
    {
        foreach ($columns as $key => $column) {
            if (\is_string($column) || $column instanceof \Closure) {
                $this->groups[] = $column;
            } else {
                throw new \InvalidArgumentException();
            }
        }

        return $this;
    }

    /**
     * Order by newest.
     *
     * @param \Closure|string $column Column
     *
     * @return Builder
     *
     * @since  1.0.0
     */
    public function newest($column) : self
    {
        $this->orderBy($column, 'DESC');

        return $this;
    }

    /**
     * Order by oldest.
     *
     * @param \Closure|string $column Column
     *
     * @return Builder
     *
     * @since  1.0.0
     */
    public function oldest($column) : self
    {
        $this->orderBy($column, 'ASC');

        return $this;
    }

    /**
     * Order by oldest.
     *
     * @param array|\Closure|string $columns Columns
     * @param string|string[]       $order   Orders
     *
     * @return Builder
     *
     * @since  1.0.0
     */
    public function orderBy($columns, $order = 'DESC') : self
    {
        if (\is_string($columns) || $columns instanceof \Closure) {
            if (!\is_string($order)) {
                throw new \InvalidArgumentException();
            }

            if (!isset($this->orders[$order])) {
                $this->orders[$order] = [];
            }

            $this->orders[$order][] = $columns;
        } elseif (\is_array($columns)) {
            foreach ($columns as $key => $column) {
                $this->orders[\is_string($order) ? $order : $order[$key]][] = $column;
            }
        } else {
            throw new \InvalidArgumentException();
        }

        return $this;
    }

    /**
     * Offset.
     *
     * @param int $offset Offset
     *
     * @return Builder
     *
     * @since  1.0.0
     */
    public function offset(int $offset) : self
    {
        $this->offset = $offset;

        return $this;
    }

    /**
     * Limit.
     *
     * @param int $limit Limit
     *
     * @return Builder
     *
     * @since  1.0.0
     */
    public function limit(int $limit) : self
    {
        $this->limit = $limit;

        return $this;
    }

    /**
     * Union.
     *
     * @param mixed $query Query
     *
     * @return Builder
     *
     * @since  1.0.0
     */
    public function union($query) : self
    {
        if (!\is_array($query)) {
            $this->unions[] = $query;
        } else {
            $this->unions += $query;
        }

        return $this;
    }

    /**
     * Lock query.
     *
     * @return void
     *
     * @since  1.0.0
     */
    public function lock() : void
    {
    }

    /**
     * Lock for update query.
     *
     * @return void
     *
     * @since  1.0.0
     */
    public function lockUpdate() : void
    {
    }

    /**
     * Create query string.
     *
     * @return string
     *
     * @since  1.0.0
     */
    public function __toString()
    {
        return $this->grammar->compileQuery($this);
    }

    /**
     * Find query.
     *
     * @return void
     *
     * @since  1.0.0
     */
    public function find() : void
    {
    }

    /**
     * Count results.
     *
     * @param string $table Table to count the result set
     *
     * @return Builder
     *
     * @since  1.0.0
     */
    public function count(string $table = '*') : self
    {
        // todo: don't do this as string, create new object new \count(); this can get handled by the grammar parser WAY better
        return $this->select('COUNT(' . $table . ')');
    }

    /**
     * Select minimum.
     *
     * @return void
     *
     * @since  1.0.0
     */
    public function min() : void
    {
    }

    /**
     * Select maximum.
     *
     * @return void
     *
     * @since  1.0.0
     */
    public function max() : void
    {
    }

    /**
     * Select sum.
     *
     * @return void
     *
     * @since  1.0.0
     */
    public function sum() : void
    {
    }

    /**
     * Select average.
     *
     * @return void
     *
     * @since  1.0.0
     */
    public function avg() : void
    {
    }

    /**
     * Insert into columns.
     *
     * @param array ...$columns Columns
     *
     * @return Builder
     *
     * @throws \Exception
     *
     * @since  1.0.0
     */
    public function insert(...$columns) : self
    {
        if ($this->isReadOnly) {
            throw new \Exception();
        }

        $this->type = QueryType::INSERT;

        foreach ($columns as $key => $column) {
            $this->inserts[] = $column;
        }

        return $this;
    }

    /**
     * Table to insert into.
     *
     * @param \Closure|string $table Table
     *
     * @return Builder
     *
     * @since  1.0.0
     */
    public function into($table) : self
    {
        $this->into = $table;

        return $this;
    }

    /**
     * Values to insert.
     *
     * @param array ...$values Values
     *
     * @return Builder
     *
     * @since  1.0.0
     */
    public function values(...$values) : self
    {
        $this->values[] = $values;

        return $this;
    }

    /**
     * Get insert values
     *
     * @return array
     *
     * @since  1.0.0
     */
    public function getValues() : array
    {
        return $this->values;
    }

    /**
     * Values to insert.
     *
     * @param mixed $value Values
     *
     * @return Builder
     *
     * @since  1.0.0
     */
    public function value($value) : self
    {
        \end($this->values);
        $key = \key($this->values);

        if (\is_array($value)) {
            $this->values[$key] = $value;
        } else {
            $this->values[$key][] = $value;
        }

        \reset($this->values);

        return $this;
    }

    /**
     * Values to insert.
     *
     * @param array ...$sets Values
     *
     * @return Builder
     *
     * @since  1.0.0
     */
    public function sets(...$sets) : self
    {
        $this->sets[$sets[0]] = $sets[1] ?? null;

        return $this;
    }

    /**
     * Values to insert.
     *
     * @param mixed $set Values
     *
     * @return Builder
     *
     * @since  1.0.0
     */
    public function set($set) : self
    {
        $this->sets[\key($set)] = \current($set);

        return $this;
    }

    /**
     * Update columns.
     *
     * @param array ...$tables Column names to update
     *
     * @return Builder
     *
     * @throws \Exception
     *
     * @since  1.0.0
     */
    public function update(...$tables) : self
    {
        if ($this->isReadOnly) {
            throw new \Exception();
        }

        $this->type = QueryType::UPDATE;

        foreach ($tables as $key => $table) {
            if (\is_string($table) || $table instanceof \Closure) {
                $this->updates[] = $table;
            } else {
                throw new \InvalidArgumentException();
            }
        }

        return $this;
    }

    /**
     * Delete query
     *
     * @return Builder
     *
     * @since  1.0.0
     */
    public function delete() : self
    {
        if ($this->isReadOnly) {
            throw new \Exception();
        }

        $this->type = QueryType::DELETE;

        return $this;
    }

    /**
     * Increment value.
     *
     * @return void
     *
     * @since  1.0.0
     */
    public function increment() : void
    {
    }

    /**
     * Decrement value.
     *
     * @return void
     *
     * @since  1.0.0
     */
    public function decrement() : void
    {
    }

    /**
     * Join.
     *
     * @return Builder
     *
     * @since  1.0.0
     */
    public function join($table, string $type = JoinType::JOIN) : self
    {
        if (\is_string($table) || $table instanceof \Closure) {
            $this->joins[] = ['type' => $type, 'table' => $table];
        } else {
            throw new \InvalidArgumentException();
        }

        return $this;
    }

    /**
     * Join.
     *
     * @return Builder
     *
     * @since  1.0.0
     */
    public function leftJoin($column) : self
    {
        return $this->join($column, JoinType::LEFT_JOIN);
    }

    /**
     * Join.
     *
     * @return Builder
     *
     * @since  1.0.0
     */
    public function leftOuterJoin($column) : self
    {
        return $this->join($column, JoinType::LEFT_OUTER_JOIN);
    }

    /**
     * Join.
     *
     * @return Builder
     *
     * @since  1.0.0
     */
    public function leftInnerJoin($column) : self
    {
        return $this->join($column, JoinType::LEFT_INNER_JOIN);
    }

    /**
     * Join.
     *
     * @return Builder
     *
     * @since  1.0.0
     */
    public function rightJoin($column) : self
    {
        return $this->join($column, JoinType::RIGHT_JOIN);
    }

    /**
     * Join.
     *
     * @return Builder
     *
     * @since  1.0.0
     */
    public function rightOuterJoin($column) : self
    {
        return $this->join($column, JoinType::RIGHT_OUTER_JOIN);
    }

    /**
     * Join.
     *
     * @return Builder
     *
     * @since  1.0.0
     */
    public function rightInnerJoin($column) : self
    {
        return $this->join($column, JoinType::RIGHT_INNER_JOIN);
    }

    /**
     * Join.
     *
     * @return Builder
     *
     * @since  1.0.0
     */
    public function outerJoin($column) : self
    {
        return $this->join($column, JoinType::OUTER_JOIN);
    }

    /**
     * Join.
     *
     * @return Builder
     *
     * @since  1.0.0
     */
    public function innerJoin($column) : self
    {
        return $this->join($column, JoinType::INNER_JOIN);
    }

    /**
     * Join.
     *
     * @return Builder
     *
     * @since  1.0.0
     */
    public function crossJoin($column) : self
    {
        return $this->join($column, JoinType::CROSS_JOIN);
    }

    /**
     * Join.
     *
     * @return Builder
     *
     * @since  1.0.0
     */
    public function fullJoin($column) : self
    {
        return $this->join($column, JoinType::FULL_JOIN);
    }

    /**
     * Join.
     *
     * @return Builder
     *
     * @since  1.0.0
     */
    public function fullOuterJoin($column) : self
    {
        return $this->join($column, JoinType::FULL_OUTER_JOIN);
    }

    /**
     * Rollback.
     *
     * @return Builder
     *
     * @since  1.0.0
     */
    public function rollback() : self
    {
        return $this;
    }

    /**
     * On.
     *
     * @return Builder
     *
     * @since  1.0.0
     */
    public function on($columns, $operator = null, $values = null, $boolean = 'and') : self
    {
        if ($operator !== null && !\is_array($operator) && !\in_array(\strtolower($operator), self::OPERATORS)) {
            throw new \InvalidArgumentException('Unknown operator.');
        }

        if (!\is_array($columns)) {
            $columns  = [$columns];
            $operator = [$operator];
            $values   = [$values];
            $boolean  = [$boolean];
        }

        $joinCount = \count($this->joins) - 1;
        $i         = 0;

        foreach ($columns as $key => $column) {
            if (isset($operator[$i]) && !\in_array(\strtolower($operator[$i]), self::OPERATORS)) {
                throw new \InvalidArgumentException('Unknown operator.');
            }

            $this->ons[$joinCount][] = [
                'column'   => $column,
                'operator' => $operator[$i],
                'value'    => $values[$i],
                'boolean'  => $boolean[$i],
            ];

            $i++;
        }

        return $this;
    }

    /**
     * On.
     *
     * @return Builder
     *
     * @since  1.0.0
     */
    public function orOn($columns, $operator = null, $values = null) : self
    {
        return $this->on($columns, $operator, $values, 'or');
    }

    /**
     * On.
     *
     * @return Builder
     *
     * @since  1.0.0
     */
    public function andOn($columns, $operator = null, $values = null) : self
    {
        return $this->on($columns, $operator, $values, 'and');
    }

    /**
     * Merging query.
     *
     * Merging query in order to remove database query volume
     *
     * @param Builder $query Query
     *
     * @return Builder
     *
     * @since  1.0.0
     */
    public function merge(self $query) : self
    {
        return clone($this);
    }

    /**
     * Execute query.
     *
     * @return mixed
     *
     * @since  1.0.0
     */
    public function execute()
    {
        $sth = $this->connection->con->prepare($this->toSql());

        foreach ($this->binds as $key => $bind) {
            $type = self::getBindParamType($bind);

            $sth->bindParam($key, $bind, $type);
        }

        $sth->execute();

        return $sth;
    }

    /**
     * Get bind parameter type.
     *
     * @param mixed $value Value to bind
     *
     * @return int
     *
     * @throws \Exception
     *
     * @since  1.0.0
     */
    public static function getBindParamType($value) : int
    {
        if (\is_int($value)) {
            return \PDO::PARAM_INT;
        } elseif (\is_string($value) || \is_float($value)) {
            return \PDO::PARAM_STR;
        }

        throw new \Exception();
    }

    /**
     * Get column name
     *
     * @param mixed $column Column name
     *
     * @return string
     *
     * @throws \Exception
     *
     * @since  1.0.0
     */
    public static function getPublicColumnName($column) : string
    {
        if (\is_string($column)) {
            return $column;
        } elseif ($column instanceof Column) {
            return $column->getColumn();
        } elseif ($column instanceof \Closure) {
            return $column();
        } elseif ($column instanceof \Serializable) {
            return $column->serialize();
        }

        throw new \Exception();
    }
}
