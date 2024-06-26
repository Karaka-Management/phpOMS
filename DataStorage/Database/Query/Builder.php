<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   phpOMS\DataStorage\Database\Query
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\DataStorage\Database\Query;

use phpOMS\Algorithm\Graph\DependencyResolver;
use phpOMS\Contract\SerializableInterface;
use phpOMS\DataStorage\Database\BuilderAbstract;
use phpOMS\DataStorage\Database\Connection\ConnectionAbstract;
use phpOMS\DataStorage\Database\Query\Grammar\Grammar;

/**
 * Database query builder.
 *
 * @package phpOMS\DataStorage\Database\Query
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 *
 * @question Consider to delete the builder but create a Select, Insert, ... builder
 *      Then directly call the compileSelect + compileFrom ... from the toSql
 *      This way the object generated would be much slimmer since we don't need to initialize empty data for
 *      Insert etc. We also wouldn't have to call compileComponents since this would happen directly in toSql().
 */
class Builder extends BuilderAbstract
{
    /**
     * Grammar.
     *
     * @var Grammar
     * @since 1.0.0
     */
    protected Grammar $grammar;

    /**
     * Log queries.
     *
     * @var bool
     * @since 1.0.0
     */
    public static bool $log = false;

    /**
     * Columns.
     *
     * @var array
     * @since 1.0.0
     */
    public array $selects = [];

    /**
     * Columns.
     *
     * @var array
     * @since 1.0.0
     */
    public array $random;

    /**
     * Columns.
     *
     * @var array
     * @since 1.0.0
     */
    public array $updates = [];

    /**
     * Deletes.
     *
     * @todo Find fix for stupid work around because value needs to be not null for it to work in Grammar.
     *
     * @var array
     * @since 1.0.0
     */
    public array $deletes = [1];

    /**
     * Into.
     *
     * @var string
     * @since 1.0.0
     */
    public string $into = '';

    /**
     * Into columns.
     *
     * @var array
     * @since 1.0.0
     */
    public array $inserts = [];

    /**
     * Into columns.
     *
     * @var array<int, mixed>
     * @since 1.0.0
     */
    public array $values = [];

    /**
     * Into columns.
     *
     * @var array
     * @since 1.0.0
     */
    public array $sets = [];

    /**
     * Distinct.
     *
     * @var bool
     * @since 1.0.0
     */
    public bool $distinct = false;

    /**
     * From.
     *
     * @var array
     * @since 1.0.0
     */
    public array $from = [];

    /**
     * Joins.
     *
     * @var array
     * @since 1.0.0
     */
    public array $joins = [];

    /**
     * Ons of joins.
     *
     * @var array
     * @since 1.0.0
     */
    public array $ons = [];

    /**
     * Where.
     *
     * @var array
     * @since 1.0.0
     */
    public array $wheres = [];

    /**
     * Group.
     *
     * @var string[]|self[]
     * @since 1.0.0
     */
    public array $groups = [];

    /**
     * Order.
     *
     * @var array
     * @since 1.0.0
     */
    public array $orders = [];

    /**
     * Limit.
     *
     * @var null|int
     * @since 1.0.0
     */
    public ?int $limit = null;

    /**
     * Offset.
     *
     * @var null|int
     * @since 1.0.0
     */
    public ?int $offset = null;

    /**
     * Binds.
     *
     * @var array
     * @since 1.0.0
     */
    private array $binds = [];

    /**
     * Union.
     *
     * @var array
     * @since 1.0.0
     */
    public array $unions = [];

    /**
     * Lock.
     *
     * @var bool
     * @since 1.0.0
     */
    public bool $lock = false;

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
        'exists',
        'not exists',
    ];

    /**
     * Constructor.
     *
     * @param ConnectionAbstract $connection Database connection
     * @param bool               $readOnly   Query is read only
     *
     * @since 1.0.0
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
     * @since 1.0.0
     */
    public function setConnection(ConnectionAbstract $connection) : void
    {
        $this->connection = $connection;
        $this->grammar    = $connection->getGrammar();
    }

    /**
     * Select.
     *
     * @param mixed ...$columns Columns
     *
     * @return Builder
     *
     * @throws \InvalidArgumentException
     *
     * @since 1.0.0
     */
    public function select(mixed ...$columns) : self
    {
        $this->type = QueryType::SELECT;

        /** @var mixed[] $columns */
        /** @var mixed $column */
        foreach ($columns as $column) {
            $this->selects[] = $column;
        }

        return $this;
    }

    /**
     * Select with alias.
     *
     * @param mixed  $column Column query
     * @param string $alias  Alias
     *
     * @return Builder
     *
     * @since 1.0.0
     */
    public function selectAs(mixed $column, string $alias) : self
    {
        $this->type            = QueryType::SELECT;
        $this->selects[$alias] = $column;

        return $this;
    }

    /**
     * Select.
     *
     * @param array ...$columns Columns
     *
     * @return Builder
     *
     * @since 1.0.0
     */
    public function random(mixed ...$columns) : self
    {
        $this->select(...$columns);

        $this->type   = QueryType::RANDOM;
        $this->random = &$this->selects;

        return $this;
    }

    /**
     * Bind parameter.
     *
     * @param string|array $binds Binds
     *
     * @return Builder
     *
     * @since 1.0.0
     */
    public function bind(string | array $binds) : self
    {
        if (\is_array($binds)) {
            $this->binds += $binds;
        } else {
            $this->binds[] = $binds;
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function toSql() : string
    {
        if (!empty($this->joins)) {
            $this->resolveJoinDependencies();
        }

        $components  = $this->grammar->compileComponents($this);
        $queryString = '';

        foreach ($components as $component) {
            if ($component !== '') {
                $queryString .= $component . ' ';
            }
        }

        $query = \substr($queryString, 0, -1) . ';';

        if (self::$log) {
            \phpOMS\Log\FileLogger::getInstance()->debug($query);
        }

        return $query;
    }

    /**
     * Resolves join dependencies
     *
     * @return void
     *
     * @since 1.0.0
     */
    private function resolveJoinDependencies() : void
    {
        // create dependencies
        $dependencies = [];
        foreach ($this->joins as $table => $_) {
            $dependencies[$table] = [];

            foreach ($this->ons[$table] as $on) {
                if (!\is_string($on)) {
                    continue;
                }

                if (\stripos($on['column'], '.')) {
                    $dependencies[$table][] = \explode('.', $on['column'])[0];
                }

                if (\stripos($on['value'], '.')) {
                    $dependencies[$table][] = \explode('.', $on['value'])[0];
                }
            }
        }

        // add from to existing dependencies
        foreach ($this->from as $table => $_) {
            $dependencies[$table] = [];
        }

        $resolved = DependencyResolver::resolve($dependencies);

        // cyclic dependencies
        if ($resolved === null) {
            return;
        }

        $temp        = $this->joins;
        $this->joins = [];
        foreach ($resolved as $table) {
            if (isset($temp[$table])) {
                $this->joins[$table] = $temp[$table];
            }
        }
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
     * @since 1.0.0
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
     * @since 1.0.0
     */
    private function isValidReadOnly(string $raw) : bool
    {
        if (!$this->isReadOnly) {
            return true;
        }

        $raw = \strtolower($raw);

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
     * Is distinct.
     *
     * @return Builder
     *
     * @since 1.0.0
     */
    public function distinct() : self
    {
        $this->distinct = true;

        return $this;
    }

    /**
     * From.
     *
     * @param mixed ...$tables Tables
     *
     * @return Builder
     *
     * @throws \InvalidArgumentException
     *
     * @since 1.0.0
     */
    public function from(mixed ...$tables) : self
    {
        /** @var mixed[] $tables */
        /** @var mixed $table */
        foreach ($tables as $key => $table) {
            if (\is_string($table) || $table instanceof self) {
                $this->from[] = $table;
            } else {
                throw new \InvalidArgumentException();
            }
        }

        return $this;
    }

    /**
     * From with alias.
     *
     * @param mixed  $column Column query
     * @param string $alias  Alias
     *
     * @return Builder
     *
     * @since 1.0.0
     */
    public function fromAs(mixed $column, string $alias) : self
    {
        $this->from[$alias] = $column;

        return $this;
    }

    /**
     * Where.
     *
     * @param string|array|Where $columns  Columns
     * @param string|array       $operator Operator
     * @param mixed              $values   Values
     * @param string|array       $boolean  Boolean condition
     *
     * @return Builder
     *
     * @throws \InvalidArgumentException
     *
     * @since 1.0.0
     */
    public function where(string | array | self $columns, string | array|null $operator = null, mixed $values = null, string | array $boolean = 'and') : self
    {
        if (!\is_array($columns)) {
            $columns  = [$columns];
            $operator = [$operator];
            $values   = [$values];
            $boolean  = [$boolean];
        }

        $i = 0;
        foreach ($columns as $column) {
            if (isset($operator[$i]) && !\in_array(\strtolower($operator[$i]), self::OPERATORS)) {
                throw new \InvalidArgumentException('Unknown operator: "' . $operator[$i] . '"');
            }

            $this->wheres[self::getPublicColumnName($column)][] = [
                'column'   => $column,
                'operator' => $operator[$i],
                'value'    => $values[$i],
                'boolean'  => $boolean[$i],
            ];

            ++$i;
        }

        return $this;
    }

    /**
     * Where and sub condition.
     *
     * @param string|array|Where $where    Where sub condition
     * @param string|array       $operator Operator
     * @param mixed              $values   Values
     *
     * @return Builder
     *
     * @since 1.0.0
     */
    public function andWhere(string | array | Where $where, string | array|null $operator = null, mixed $values = null) : self
    {
        return $this->where($where, $operator, $values, 'and');
    }

    /**
     * Where or sub condition.
     *
     * @param string|array|Where $where    Where sub condition
     * @param string|array       $operator Operator
     * @param mixed              $values   Values
     *
     * @return Builder
     *
     * @since 1.0.0
     */
    public function orWhere(string | array | self $where, string | array|null $operator = null, mixed $values = null) : self
    {
        return $this->where($where, $operator, $values, 'or');
    }

    /**
     * Where in.
     *
     * @param string|array|Where $column  Column
     * @param string|array       $values  Values
     * @param string             $boolean Boolean condition
     *
     * @return Builder
     *
     * @since 1.0.0
     */
    public function whereIn(string | array | Where $column, mixed $values = null, string $boolean = 'and') : self
    {
        $this->where($column, 'in', $values, $boolean);

        return $this;
    }

    /**
     * Where null.
     *
     * @param string|array|Where $column  Column
     * @param string             $boolean Boolean condition
     *
     * @return Builder
     *
     * @since 1.0.0
     */
    public function whereNull(string | array | Where $column, string $boolean = 'and') : self
    {
        $this->where($column, '=', null, $boolean);

        return $this;
    }

    /**
     * Where not null.
     *
     * @param string|array|Where $column  Column
     * @param string             $boolean Boolean condition
     *
     * @return Builder
     *
     * @since 1.0.0
     */
    public function whereNotNull(string | array | Where $column, string $boolean = 'and') : self
    {
        $this->where($column, '!=', null, $boolean);

        return $this;
    }

    /**
     * Group by.
     *
     * @param mixed ...$columns Grouping result
     *
     * @return Builder
     *
     * @throws \InvalidArgumentException
     *
     * @since 1.0.0
     */
    public function groupBy(mixed ...$columns) : self
    {
        /** @var mixed[] $columns */
        /** @var mixed $column */
        foreach ($columns as $column) {
            if (\is_string($column) || $column instanceof self) {
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
     * @param string $column Column
     *
     * @return Builder
     *
     * @since 1.0.0
     */
    public function newest(string $column) : self
    {
        $this->orderBy($column, OrderType::DESC);

        return $this;
    }

    /**
     * Order by oldest.
     *
     * @param string $column Column
     *
     * @return Builder
     *
     * @since 1.0.0
     */
    public function oldest(string $column) : self
    {
        $this->orderBy($column, OrderType::ASC);

        return $this;
    }

    /**
     * Order by.
     *
     * @param string|array    $columns Columns
     * @param string|string[] $order   Orders
     *
     * @return Builder
     *
     * @since 1.0.0
     */
    public function orderBy(string | array $columns, string | array $order = OrderType::DESC) : self
    {
        if (\is_string($columns)) {
            $columns = [$columns];
        }

        foreach ($columns as $key => $column) {
            $tOrder = \is_string($order) ? $order : $order[$key];

            $this->orders[$column] = $tOrder;
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
     * @since 1.0.0
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
     * @since 1.0.0
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
     * @since 1.0.0
     */
    public function union(mixed $query) : self
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
     * @since 1.0.0
     */
    public function lock() : void
    {
    }

    /**
     * Lock for update query.
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function lockUpdate() : void
    {
    }

    /**
     * Create query string.
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function __toString()
    {
        $components  = $this->grammar->compileComponents($this);
        $queryString = '';

        foreach ($components as $component) {
            if ($component !== '') {
                $queryString .= $component . ' ';
            }
        }

        return \substr($queryString, 0, -1) . ';';
    }

    /**
     * Count results.
     *
     * @param string $column Table to count the result set
     *
     * @return Builder
     *
     * @since 1.0.0
     */
    public function count(string $column = '*', ?string $as = null) : self
    {
        return $as === null
            ? $this->select('COUNT(' . $column . ')')
            : $this->selectAs('COUNT(' . $column . ')', $as);
    }

    /**
     * Select minimum.
     *
     * @return Builder
     *
     * @since 1.0.0
     */
    public function min(string $column = '*', ?string $as = null) : self
    {
        return $as === null
            ? $this->select('MIN(' . $column . ')')
            : $this->selectAs('MIN(' . $column . ')', $as);
    }

    /**
     * Select maximum.
     *
     * @return Builder
     *
     * @since 1.0.0
     */
    public function max(string $column = '*', ?string $as = null) : self
    {
        return $as === null
            ? $this->select('MAX(' . $column . ')')
            : $this->selectAs('MAX(' . $column . ')', $as);
    }

    /**
     * Select sum.
     *
     * @return Builder
     *
     * @since 1.0.0
     */
    public function sum(string $column = '*', ?string $as = null) : self
    {
        return $as === null
            ? $this->select('SUM(' . $column . ')')
            : $this->selectAs('SUM(' . $column . ')', $as);
    }

    /**
     * Select average.
     *
     * @return Builder
     *
     * @since 1.0.0
     */
    public function avg(string $column = '*', ?string $as = null) : self
    {
        return $as === null
            ? $this->select('AVG(' . $column . ')')
            : $this->selectAs('AVG(' . $column . ')', $as);
    }

    /**
     * Insert into columns.
     *
     * @param mixed ...$columns Columns
     *
     * @return Builder
     *
     * @throws \Exception
     *
     * @since 1.0.0
     */
    public function insert(mixed ...$columns) : self
    {
        if ($this->isReadOnly) {
            throw new \Exception();
        }

        $this->type = QueryType::INSERT;

        foreach ($columns as $column) {
            $this->inserts[] = $column;
        }

        return $this;
    }

    /**
     * Table to insert into.
     *
     * @param string $table Table
     *
     * @return Builder
     *
     * @since 1.0.0
     */
    public function into(string $table) : self
    {
        $this->into = $table;

        return $this;
    }

    /**
     * Values to insert.
     *
     * @param mixed ...$values Values
     *
     * @return Builder
     *
     * @since 1.0.0
     */
    public function values(mixed ...$values) : self
    {
        $this->values[] = $values;

        return $this;
    }

    /**
     * Get insert values
     *
     * @return array
     *
     * @since 1.0.0
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
     * @since 1.0.0
     */
    public function value(mixed $value) : self
    {
        \end($this->values);

        $key = \key($this->values);
        $key ??= 0;

        if (\is_array($value)) {
            $this->values[$key + 1] = $value;
        } else {
            $this->values[$key][] = $value;
        }

        \reset($this->values);

        return $this;
    }

    /**
     * Values to insert.
     *
     * @param mixed ...$sets Values
     *
     * @return Builder
     *
     * @since 1.0.0
     */
    public function sets(mixed ...$sets) : self
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
     * @since 1.0.0
     */
    public function set(mixed $set) : self
    {
        $this->sets[\key($set)] = \current($set);

        return $this;
    }

    /**
     * Update columns.
     *
     * @param string $table Column names to update
     *
     * @return Builder
     *
     * @throws \Exception
     * @throws \InvalidArgumentException
     *
     * @since 1.0.0
     */
    public function update(string $table) : self
    {
        if ($this->isReadOnly) {
            throw new \Exception();
        }

        $this->type      = QueryType::UPDATE;
        $this->updates[] = $table;

        return $this;
    }

    /**
     * Delete query
     *
     * @return Builder
     *
     * @since 1.0.0
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
     * Join.
     *
     * @param string|self $table Join query
     * @param string      $type  Join type
     * @param null|string $alias Alias name (empty = none)
     *
     * @return Builder
     *
     * @since 1.0.0
     */
    public function join(string | self $table, string $type = JoinType::JOIN, ?string $alias = null) : self
    {
        $this->joins[$alias ?? $table] = ['type' => $type, 'table' => $table, 'alias' => $alias];

        return $this;
    }

    /**
     * Join.
     *
     * @param string|self $table Join query
     * @param null|string $alias Alias name (empty = none)
     *
     * @return Builder
     *
     * @since 1.0.0
     */
    public function leftJoin(string | self $table, ?string $alias = null) : self
    {
        return $this->join($table, JoinType::LEFT_JOIN, $alias);
    }

    /**
     * Join.
     *
     * @param string|self $table Join query
     * @param null|string $alias Alias name (empty = none)
     *
     * @return Builder
     *
     * @since 1.0.0
     */
    public function leftOuterJoin(string | self $table, ?string $alias = null) : self
    {
        return $this->join($table, JoinType::LEFT_OUTER_JOIN, $alias);
    }

    /**
     * Join.
     *
     * @param string|self $table Join query
     * @param null|string $alias Alias name (empty = none)
     *
     * @return Builder
     *
     * @since 1.0.0
     */
    public function leftInnerJoin(string | self $table, ?string $alias = null) : self
    {
        return $this->join($table, JoinType::LEFT_INNER_JOIN, $alias);
    }

    /**
     * Join.
     *
     * @param string|self $table Join query
     * @param null|string $alias Alias name (empty = none)
     *
     * @return Builder
     *
     * @since 1.0.0
     */
    public function rightJoin(string | self $table, ?string $alias = null) : self
    {
        return $this->join($table, JoinType::RIGHT_JOIN, $alias);
    }

    /**
     * Join.
     *
     * @param string|self $table Join query
     * @param null|string $alias Alias name (empty = none)
     *
     * @return Builder
     *
     * @since 1.0.0
     */
    public function rightOuterJoin(string | self $table, ?string $alias = null) : self
    {
        return $this->join($table, JoinType::RIGHT_OUTER_JOIN, $alias);
    }

    /**
     * Join.
     *
     * @param string|self $table Join query
     * @param null|string $alias Alias name (empty = none)
     *
     * @return Builder
     *
     * @since 1.0.0
     */
    public function rightInnerJoin(string | self $table, ?string $alias = null) : self
    {
        return $this->join($table, JoinType::RIGHT_INNER_JOIN, $alias);
    }

    /**
     * Join.
     *
     * @param string|self $table Join query
     * @param null|string $alias Alias name (empty = none)
     *
     * @return Builder
     *
     * @since 1.0.0
     */
    public function outerJoin(string | self $table, ?string $alias = null) : self
    {
        return $this->join($table, JoinType::OUTER_JOIN, $alias);
    }

    /**
     * Join.
     *
     * @param string|self $table Join query
     * @param null|string $alias Alias name (empty = none)
     *
     * @return Builder
     *
     * @since 1.0.0
     */
    public function innerJoin(string | self $table, ?string $alias = null) : self
    {
        return $this->join($table, JoinType::INNER_JOIN, $alias);
    }

    /**
     * Join.
     *
     * @param string|self $table Join query
     * @param null|string $alias Alias name (empty = none)
     *
     * @return Builder
     *
     * @since 1.0.0
     */
    public function crossJoin(string | self $table, ?string $alias = null) : self
    {
        return $this->join($table, JoinType::CROSS_JOIN, $alias);
    }

    /**
     * Join.
     *
     * @param string|self $table Join query
     * @param null|string $alias Alias name (empty = none)
     *
     * @return Builder
     *
     * @since 1.0.0
     */
    public function fullJoin(string | self $table, ?string $alias = null) : self
    {
        return $this->join($table, JoinType::FULL_JOIN, $alias);
    }

    /**
     * Join.
     *
     * @param string|self $table Join query
     * @param null|string $alias Alias name (empty = none)
     *
     * @return Builder
     *
     * @since 1.0.0
     */
    public function fullOuterJoin(string | self $table, ?string $alias = null) : self
    {
        return $this->join($table, JoinType::FULL_OUTER_JOIN, $alias);
    }

    /**
     * Rollback.
     *
     * @return Builder
     *
     * @since 1.0.0
     */
    public function rollback() : self
    {
        return $this;
    }

    /**
     * On.
     *
     * @param string|array      $columns  Columns to join on
     * @param null|string|array $operator Comparison operator
     * @param mixed             $values   Values to compare with
     * @param string|array      $boolean  Concatenation
     * @param null|string       $table    Table this belongs to
     *
     * @return Builder
     *
     * @throws \InvalidArgumentException
     *
     * @since 1.0.0
     */
    public function on(string | array $columns, string | array|null $operator = null, mixed $values = null, string | array $boolean = 'and', ?string $table = null) : self
    {
        if (!\is_array($columns)) {
            $columns  = [$columns];
            $operator = [$operator];
            $values   = [$values];
            $boolean  = [$boolean];
        }

        $joinCount = \count($this->joins) - 1;
        $i         = 0;
        $table ??= \array_keys($this->joins)[$joinCount];

        foreach ($columns as $column) {
            if (isset($operator[$i]) && !\in_array(\strtolower($operator[$i]), self::OPERATORS)) {
                throw new \InvalidArgumentException('Unknown operator.');
            }

            $this->ons[$table][] = [
                'column'   => $column,
                'operator' => $operator[$i],
                'value'    => $values[$i],
                'boolean'  => $boolean[$i],
            ];

            ++$i;
        }

        return $this;
    }

    /**
     * On.
     *
     * @param string|array      $columns  Columns to join on
     * @param null|string|array $operator Comparison operator
     * @param null|string|array $values   Values to compare with
     *
     * @return Builder
     *
     * @since 1.0.0
     */
    public function orOn(string | array $columns, string | array|null $operator = null, string | array|null $values = null) : self
    {
        return $this->on($columns, $operator, $values, 'or');
    }

    /**
     * On.
     *
     * @param string|array      $columns  Columns to join on
     * @param null|string|array $operator Comparison operator
     * @param null|string|array $values   Values to compare with
     *
     * @return Builder
     *
     * @since 1.0.0
     */
    public function andOn(string | array $columns, string | array|null $operator = null, string | array|null $values = null) : self
    {
        return $this->on($columns, $operator, $values, 'and');
    }

    /**
     * {@inheritdoc}
     */
    public function execute() : ?\PDOStatement
    {
        $sth = null;
        $sql = '';

        try {
            $sth = $this->connection->con->prepare($sql = $this->toSql());
            if ($sth === false) {
                return null;
            }

            foreach ($this->binds as $key => $bind) {
                $type = self::getBindParamType($bind);

                $sth->bindParam($key, $bind, $type);
            }

            $sth->execute();
        } catch (\Throwable $t) {
            // @codeCoverageIgnoreStart
            \phpOMS\Log\FileLogger::getInstance()->error(
                \phpOMS\Log\FileLogger::MSG_FULL, [
                    'message' => $t->getMessage() . ':' . $sql,
                    'line'    => __LINE__,
                    'file'    => self::class,
                ]
            );

            $sth = null;
            // @codeCoverageIgnoreEnd
        }

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
     * @since 1.0.0
     */
    public static function getBindParamType(mixed $value) : int
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
     * @since 1.0.0
     */
    public static function getPublicColumnName(mixed $column) : string
    {
        if (\is_string($column)) {
            return $column;
        } elseif ($column instanceof SerializableInterface) {
            return $column->serialize();
        } elseif ($column instanceof self) {
            return \md5($column->toSql());
        }

        throw new \Exception();
    }
}
