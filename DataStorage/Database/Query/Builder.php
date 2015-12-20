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

namespace phpOMS\DataStorage\Database\Query;

use phpOMS\DataStorage\Database\Connection\ConnectionAbstract;
use phpOMS\DataStorage\Database\Query;

/**
 * Database query builder.
 *
 * @category   Framework
 * @package    phpOMS\DataStorage\Database
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
class Builder
{

    /**
     * Columns.
     *
     * @var array
     * @since 1.0.0
     */
    public $selects = [];

    /**
     * Into.
     *
     * @var array
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
     * Distinct.
     *
     * @var \bool
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
     * @var array
     * @since 1.0.0
     */
    public $limit = null;

    /**
     * Offset.
     *
     * @var array
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
     * @var \bool
     * @since 1.0.0
     */
    public $lock = false;

    /**
     * Database connectino.
     *
     * @var ConnectionAbstract
     * @since 1.0.0
     */
    protected $connection = null;

    /**
     * Grammar.
     *
     * @var \phpOMS\DataStorage\Database\Query\Grammar\GrammarInterface
     * @since 1.0.0
     */
    protected $grammar = null;

    /**
     * Query type.
     *
     * @var QueryType
     * @since 1.0.0
     */
    protected $type = QueryType::INSERT;

    protected $unionLimit = null;

    protected $unionOffset = null;

    protected $unionOrders = [];

    /**
     * Prefix.
     *
     * @var \string
     * @since 1.0.0
     */
    protected $prefix = '';

    /**
     * Comparison operators.
     *
     * @var \string[]
     * @since 1.0.0
     */
    const operators = [
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
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function __construct(ConnectionAbstract $connection)
    {
        $this->connection = $connection;
        $this->grammar    = $connection->getGrammar();
    }

    /**
     * Set prefix.
     *
     * @param \string $prefix Prefix
     *
     * @return Builder
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function prefix(\string $prefix)
    {
        $this->prefix = $prefix;

        return $this;
    }

    /**
     * Get prefix.
     *
     * @return \string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getPrefix() : \string
    {
        return $this->prefix;
    }

    /**
     * Select.
     *
     * @param array $columns Columns
     *
     * @return Builder
     *
     * @todo   Closure is not working this way, needs to be evaluated befor assigning
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function select(...$columns) : Builder
    {
        // todo handle wrong parameters

        $this->type = QueryType::SELECT;

        foreach ($columns as $key => $column) {
            if (is_string($column) || $column instanceof \Closure) {
                $this->selects[] = $column;
            } else {
                throw new \InvalidArgumentException();
            }
        }

        return $this;
    }

    /**
     * Bind parameter.
     *
     * @param \string|array|\Closure $binds Binds
     *
     * @return Builder
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function bind($binds) : Builder
    {
        if (is_array($binds)) {
            $this->binds += $binds;
        } elseif (is_string($binds) || $binds instanceof \Closure) {
            $this->binds[] = $binds;
        } else {
            throw new \InvalidArgumentException();
        }

        return $this;
    }

    /**
     * Creating new.
     *
     * @return self
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function newQuery() : Builder
    {
        return new static($this->connection, $this->grammar);
    }

    /**
     * Parsing to string.
     *
     * @return \string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function toSql() : \string
    {
        return $this->grammar->compileQuery($this);
    }

    /**
     * Executing.
     *
     * @return mixed
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function execute()
    {
        // todo: handle different query types (select, insert etc)
    }

    /**
     * Make raw column selection.
     *
     * @param \string|\Closure $expression Raw expression
     *
     * @return Builder
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function selectRaw($expression) : Builder
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
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function distinct() : Builder
    {
        $this->distinct = true;

        return $this;
    }

    /**
     * From.
     *
     * @param array $tables Tables
     *
     * @return Builder
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function from(...$tables) : Builder
    {
        foreach ($tables as $key => $table) {
            if (is_string($table) || $table instanceof \Closure) {
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
     * @param \string|array|\Closure $expression Expression
     *
     * @return Builder
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function fromRaw($expression) : Builder
    {
        $this->from[null][] = $expression;

        return $this;
    }

    /**
     * Or Where.
     *
     * @param \string|array|\Closure $columns  Columns
     * @param \string                $operator Operator
     * @param \string|array|\Closure $values   Values
     *
     * @return Builder
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function orWhere($columns, \string $operator = null, $values = null) : Builder
    {
        return $this->where($columns, $operator, $values, 'or');
    }

    /**
     * Where.
     *
     * @param \string|array|\Closure $columns  Columns
     * @param \string|array          $operator Operator
     * @param mixed                  $values   Values
     * @param \string|array          $boolean  Boolean condition
     *
     * @return Builder
     *
     * @throws
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function where($columns, $operator = null, $values = null, $boolean = 'and') : Builder
    {
        // TODO: handle $value is null -> operator NULL
        if (isset($operator) && !is_array($operator) && !in_array($operator, self::operators)) {
            throw new \InvalidArgumentException('Unknown operator.');
        }

        if (is_array($columns)) {
            $i = 0;
            foreach ($columns as $key => $column) {
                if (isset($operator[$i]) && !in_array($operator[$i], self::operators)) {
                    throw new \InvalidArgumentException('Unknown operator.');
                }

                $this->wheres[$key][] = ['column'  => $column, 'operator' => $operator[$i],
                                         'value'   => $values[$i],
                                         'boolean' => $boolean[$i],];
                $i++;
            }
        } elseif (is_string($columns)) {
            if (isset($operator) && !in_array($operator, self::operators)) {
                throw new \InvalidArgumentException('Unknown operator.');
            }

            $this->wheres[null][] = ['column'  => $columns, 'operator' => $operator, 'value' => $values,
                                     'boolean' => $boolean,];
        } else {
            throw new \InvalidArgumentException();
        }

        return $this;
    }

    /**
     * Where in.
     *
     * @param \string|array|\Closure $column  Column
     * @param mixed                  $values  Values
     * @param \string                $boolean Boolean condition
     *
     * @return Builder
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function whereIn($column, $values = null, \string $boolean = 'and') : Builder
    {
        $this->where($column, 'in', $values, $boolean);

        return $this;
    }

    /**
     * Where null.
     *
     * @param \string|array|\Closure $column  Column
     * @param \string                $boolean Boolean condition
     *
     * @return Builder
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function whereNull($column, \string $boolean = 'and') : Builder
    {
        $this->where($column, '=', null, $boolean);

        return $this;
    }

    /**
     * Where not null.
     *
     * @param \string|array|\Closure $column  Column
     * @param \string                $boolean Boolean condition
     *
     * @return Builder
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function whereNotNull($column, \string $boolean = 'and') : Builder
    {
        $this->where($column, '!=', null, $boolean);

        return $this;
    }

    /**
     * Group by.
     *
     * @param \string|array|\Closure $columns Grouping result
     *
     * @return Builder
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function groupBy(...$columns) : Builder
    {
        foreach ($columns as $key => $column) {
            if (is_string($column) || $column instanceof \Closure) {
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
     * @param \string|\Closure $column Column
     *
     * @return Builder
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function newest($column) : Builder
    {
        $this->orderBy($column, 'DESC');

        return $this;
    }

    /**
     * Order by oldest.
     *
     * @param \string|\Closure $column Column
     *
     * @return Builder
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function oldest($column) : Builder
    {
        $this->orderBy($column, 'ASC');

        return $this;
    }

    /**
     * Order by oldest.
     *
     * @param \string|array|\Closure $columns Columns
     * @param \string|string[]       $order   Orders
     *
     * @return Builder
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function orderBy($columns, $order = 'DESC') : Builder
    {
        if (is_string($columns) || $columns instanceof \Closure) {
            $this->orders[] = ['column' => $columns, 'order' => $order];
        } elseif (is_array($columns)) {
            foreach ($columns as $key => $column) {
                $this->orders[] = ['column' => $column, 'order' => $order[$key]];
            }
        } else {
            throw new \InvalidArgumentException();
        }

        return $this;
    }

    /**
     * Offset.
     *
     * @param \int|\Closure $offset Offset
     *
     * @return Builder
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function offset($offset) : Builder
    {
        $this->offset = $offset;

        return $this;
    }

    /**
     * Limit.
     *
     * @param \int|\Closure $limit Limit
     *
     * @return Builder
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function limit($limit) : Builder
    {
        $this->limit = $limit;

        return $this;
    }

    /**
     * Limit.
     *
     * @param \string|\phpOMS\DataStorage\Database\Query\Builder $query Query
     *
     * @return Builder
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function union($query) : Builder
    {
        if (!is_array($query)) {
            $this->unions[] = $query;
        } else {
            $this->unions += $query;
        }

        return $this;
    }

    public function lock()
    {
    }

    public function lockUpdate()
    {
    }

    public function __toString()
    {
        return $this->grammar->compileQuery($this);
    }

    public function find()
    {
    }

    public function count()
    {
    }

    public function exists()
    {
    }

    public function min()
    {
    }

    public function max()
    {
    }

    public function sum()
    {
    }

    public function avg()
    {
    }

    public function get()
    {
        return $this;
    }

    /**
     * Insert into columns.
     *
     * @param array $columns Columns
     *
     * @return Builder
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function insert(...$columns) : Builder
    {
        $this->type = QueryType::INSERT;

        foreach ($columns as $key => $column) {
            $this->inserts[] = $column;
        }

        return $this;
    }

    /**
     * Table to insert into.
     *
     * @param \string|\Closure $table Table
     *
     * @return Builder
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function into($table) : Builder
    {
        $this->into = $table;

        return $this;
    }

    /**
     * Values to insert.
     *
     * @param array $values Values
     *
     * @return Builder
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function values(...$values) : Builder
    {
        $this->values[] = $values;

        return $this;
    }

    /**
     * Values to insert.
     *
     * @param mixed $value Values
     *
     * @return Builder
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function value($value) : Builder
    {
        end($this->values);
        $key                  = key($this->values);
        $this->values[$key][] = $value;
        reset($this->values);

        return $this;
    }

    public function update() : Builder
    {
        $this->type = QueryType::UPDATE;

        return $this;
    }

    public function increment()
    {
    }

    public function decrement()
    {
    }

    public function delete()
    {
        $this->type = QueryType::DELETE;

        return $this;
    }

    public function create($table)
    {
        return $this;
    }

    public function drop()
    {
        return $this;
    }

    public function raw()
    {
        return $this;
    }

    public function join($table1, $table2, $column1, $opperator, $column2)
    {
        return $this;
    }

    public function joinWhere()
    {
    }

    public function leftJoin()
    {
    }

    public function leftJoinWhere()
    {
    }

    public function rightJoin()
    {
    }

    public function rightJoinWhere()
    {
    }

    public function rollback()
    {
        return $this;
    }

    public function commit() : Builder
    {
        return $this;
    }

    /**
     * Get query type.
     *
     * @return \int
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getType() : \int
    {
        return $this->type;
    }

    public function merge($query) : Builder
    {
        return clone($this);
    }
}
