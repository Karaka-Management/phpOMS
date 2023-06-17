<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\DataStorage\Database\Mapper
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\DataStorage\Database\Mapper;

use phpOMS\DataStorage\Database\Connection\ConnectionAbstract;
use phpOMS\DataStorage\Database\Query\Builder;
use phpOMS\DataStorage\Database\Query\JoinType;
use phpOMS\DataStorage\Database\Query\OrderType;

/**
 * Mapper abstract.
 *
 * @package phpOMS\DataStorage\Database\Mapper
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
abstract class DataMapperAbstract
{
    /**
     * Base mapper
     *
     * @var DataMapperFactory
     * @since 1.0.0
     */
    protected DataMapperFactory $mapper;

    /**
     * Mapper type (e.g. writer, reader, ...)
     *
     * @var int
     * @since 1.0.0
     */
    protected int $type = 0;

    /**
     * Mapper depths.
     *
     * Mappers may have relations to other models (e.g. hasMany) which can have other relations, ...
     * The depths indicates how deep in the relation tree we are
     *
     * @var int
     * @since 1.0.0
     */
    protected int $depth = 1;

    /**
     * Relations which should be loaded
     *
     * @var array
     * @since 1.0.0
     */
    protected array $with = [];

    /**
     * Sort order
     *
     * @var array
     * @since 1.0.0
     */
    protected array $sort = [];

    /**
     * Offset
     *
     * @var array
     * @since 1.0.0
     */
    protected array $offset = [];

    /**
     * Limit
     *
     * @var array
     * @since 1.0.0
     */
    protected array $limit = [];

    /**
     * Where conditions
     *
     * @var array
     * @since 1.0.0
     */
    protected array $where = [];

    /**
     * Join conditions
     *
     * @var array
     * @since 1.0.0
     */
    protected array $join = [];

    /**
     * Join conditions
     *
     * @var array
     * @since 1.0.0
     */
    protected array $on = [];

    /**
     * Base query which is merged with the query in the mapper
     *
     * Sometimes you want to merge two queries together.
     *
     * @var null|Builder
     * @since 1.0.0
     */
    protected ?Builder $query = null;

    /**
     * Database connection.
     *
     * @var ConnectionAbstract
     * @since 1.0.0
     */
    protected ConnectionAbstract $db;

    /** Constructor.
     *
     * @param DataMapperFactory  $mapper Base mapper
     * @param ConnectionAbstract $db     Database connection
     *
     * @since 1.0.0
     */
    public function __construct(DataMapperFactory $mapper, ConnectionAbstract $db)
    {
        $this->mapper = $mapper;
        $this->db     = $db;
    }

    /**
     * Define a query which is merged with the internal query generation.
     *
     * @param Builder $query Query
     *
     * @return static
     *
     * @since 1.0.0
     */
    public function query(Builder $query = null) : self
    {
        $this->query = $query;

        return $this;
    }

    /**
     * Define model relations which should be loaded
     *
     * @param string $member Property name of the relation (e.g. hasMany, belongsTo, ...)
     *
     * @return static
     *
     * @since 1.0.0
     */
    public function with(string $member) : self
    {
        $split       = \explode('/', $member);
        $memberSplit = \array_shift($split);

        $this->with[$memberSplit][] = [
            'child' => \implode('/', $split),
        ];

        return $this;
    }

    /**
     * Sort order
     *
     * @param string $member Property name to sort by
     * @param string $order  Order type (DESC/ASC)
     *
     * @return static
     *
     * @since 1.0.0
     */
    public function sort(string $member, string $order = OrderType::DESC) : self
    {
        $split       = \explode('/', $member);
        $memberSplit = \array_shift($split);

        $child       = \implode('/', $split);
        $overwritten = false;

        if (isset($this->sort[$memberSplit])) {
            foreach ($this->sort[$memberSplit] as $key => $element) {
                if ($element['child'] === $child) {
                    $this->sort[$memberSplit][$key]['order'] = $order;
                    $overwritten                             = true;

                    break;
                }
            }
        }

        if (!$overwritten) {
            $this->sort[$memberSplit][] = [
                'child' => \implode('/', $split),
                'order' => $order,
            ];
        }

        return $this;
    }

    /**
     * Define the result offset
     *
     * @param int    $offset Offset
     * @param string $member Property name to offset by ('' = base model, anything else for relations such as hasMany relations)
     *
     * @return static
     *
     * @since 1.0.0
     */
    public function offset(int $offset = 0, string $member = '') : self
    {
        $split       = \explode('/', $member);
        $memberSplit = \array_shift($split);

        $child       = \implode('/', $split);
        $overwritten = false;

        if (isset($this->offset[$memberSplit])) {
            foreach ($this->offset[$memberSplit] as $key => $element) {
                if ($element['child'] === $child) {
                    $this->offset[$memberSplit][$key]['offset'] = $offset;
                    $overwritten                                = true;

                    break;
                }
            }
        }

        if (!$overwritten) {
            $this->offset[$memberSplit][] = [
                'child'  => \implode('/', $split),
                'offset' => $offset,
            ];
        }

        return $this;
    }

    /**
     * Define the result limit
     *
     * @param int    $limit  Limit
     * @param string $member Property name to limit by ('' = base model, anything else for relations such as hasMany relations)
     *
     * @return static
     *
     * @since 1.0.0
     */
    public function limit(int $limit = 0, string $member = '') : self
    {
        $split       = \explode('/', $member);
        $memberSplit = \array_shift($split);

        $child       = \implode('/', $split);
        $overwritten = false;

        if (isset($this->limit[$memberSplit])) {
            foreach ($this->limit[$memberSplit] as $key => $element) {
                if ($element['child'] === $child) {
                    $this->limit[$memberSplit][$key]['limit'] = $limit;
                    $overwritten                              = true;

                    break;
                }
            }
        }

        if (!$overwritten) {
            $this->limit[$memberSplit][] = [
                'child' => \implode('/', $split),
                'limit' => $limit,
            ];
        }

        return $this;
    }

    /**
     * Define the result filtering
     *
     * @param string $member    Property name to filter by
     * @param mixed  $value     Filter value
     * @param string $logic     Comparison logic (e.g. =, in, ...)
     * @param string $connector Filter connector (e.g. AND, OR, ...)
     *
     * @return static
     *
     * @since 1.0.0
     */
    public function where(string $member, mixed $value, string $logic = '=', string $connector = 'AND') : self
    {
        $split       = \explode('/', $member);
        $memberSplit = \array_shift($split);

        $this->where[$memberSplit][] = [
            'child'      => \implode('/', $split),
            'value'      => $value,
            'logic'      => $logic,
            'comparison' => $connector,
        ];

        return $this;
    }

    /**
     * Define the joining data
     *
     * @param string $member Property name to filter by
     * @param string $mapper Mapper
     * @param mixed  $value  Filter value
     * @param string $logic  Comparison logic (e.g. =, in, ...)
     * @param string $type   Join type (e.g. left, right, inner)
     *
     * @return static
     *
     * @since 1.0.0
     */
    public function join(string $member, string $mapper, mixed $value, string $logic = '=', string $type = JoinType::LEFT_JOIN) : self
    {
        $split       = \explode('/', $member);
        $memberSplit = \array_shift($split);

        $this->join[$memberSplit][] = [
            'child'  => \implode('/', $split),
            'mapper' => $mapper,
            'value'  => $value,
            'logic'  => $logic,
            'type'   => $type,
        ];

        return $this;
    }

    /**
     * Define the joining data
     *
     * @param string $member    Property name to filter by
     * @param mixed  $value     Filter value
     * @param string $logic     Comparison logic (e.g. =, in, ...)
     * @param string $connector Filter connector (e.g. AND, OR, ...)
     *
     * @return self
     *
     * @since 1.0.0
     */
    public function on(string $member, mixed $value, string $logic = '=', string $connector = 'AND', string $relation = '') : self
    {
        $this->on[$relation][] = [
            'child'      => '',
            'member'     => $member,
            'value'      => $value,
            'logic'      => $logic,
            'comparison' => $connector,
        ];

        return $this;
    }

    /**
     * Define the joining data
     *
     * @param string $member Property name to filter by
     * @param string $mapper Mapper
     * @param mixed  $value  Filter value
     * @param string $logic  Comparison logic (e.g. =, in, ...)
     *
     * @return static
     *
     * @since 1.0.0
     */
    public function leftJoin(string $member, string $mapper, mixed $value, string $logic = '=') : self
    {
        return $this->join($member, $mapper, $value, $logic, JoinType::LEFT_JOIN);
    }

    /**
     * Define the joining data
     *
     * @param string $member Property name to filter by
     * @param string $mapper Mapper
     * @param mixed  $value  Filter value
     * @param string $logic  Comparison logic (e.g. =, in, ...)
     *
     * @return static
     *
     * @since 1.0.0
     */
    public function rightJoin(string $member, string $mapper, mixed $value, string $logic = '=') : self
    {
        return $this->join($member, $mapper, $value, $logic, JoinType::RIGHT_JOIN);
    }

    /**
     * Define the joining data
     *
     * @param string $member Property name to filter by
     * @param string $mapper Mapper
     * @param mixed  $value  Filter value
     * @param string $logic  Comparison logic (e.g. =, in, ...)
     *
     * @return static
     *
     * @since 1.0.0
     */
    public function innerJoin(string $member, string $mapper, mixed $value, string $logic = '=') : self
    {
        return $this->join($member, $mapper, $value, $logic, JoinType::INNER_JOIN);
    }

    /**
     * Populate a mapper (e.g. child mapper, relation mapper) based on the current mapper information.
     *
     * @param DataMapperAbstract $mapper Relation mapper to populate
     * @param string             $member Relation property (e.g. ownsOne, hasMany, ... property name)
     *
     * @return self
     *
     * @since 1.0.0
     */
    public function createRelationMapper(self $mapper, string $member) : self
    {
        $relMapper = $mapper;

        if (isset($this->with[$member])) {
            foreach ($this->with[$member] as $with) {
                if ($with['child'] === '') {
                    continue;
                }

                $relMapper->with($with['child']);
            }
        }

        if (isset($this->sort[$member])) {
            foreach ($this->sort[$member] as $sort) {
                // member = child element in this case
                if ($member === '') {
                    continue;
                }

                $relMapper->sort($sort['child'], $sort['order']);
            }
        }

        if (isset($this->offset[$member])) {
            foreach ($this->offset[$member] as $offset) {
                if ($offset['child'] === '') {
                    continue;
                }

                $relMapper->offset($offset['offset'], $offset['child']);
            }
        }

        if (isset($this->limit[$member])) {
            foreach ($this->limit[$member] as $limit) {
                // member = child element in this case
                if ($member === '') {
                    continue;
                }

                $relMapper->limit($limit['limit'], $limit['child']);
            }
        }

        if (isset($this->where[$member])) {
            foreach ($this->where[$member] as $where) {
                if ($where['child'] === '') {
                    continue;
                }

                $relMapper->where($where['child'], $where['value'], $where['logic'], $where['comparison']);
            }
        }

        if (isset($this->join[$member])) {
            foreach ($this->join[$member] as $join) {
                if ($join['child'] === '') {
                    continue;
                }

                $relMapper->join($join['child'], $join['mapper'], $join['value'], $join['logic'], $join['type']);
            }
        }

        if (isset($this->on[$member])) {
            foreach ($this->on[$member] as $on) {
                if ($on['child'] === '') {
                    continue;
                }

                $relMapper->on($on['child'], $on['value'], $on['logic'], $on['comparison'], $on['field']);
            }
        }

        return $relMapper;
    }

    /**
     * Parse value
     *
     * @param string $type  Value type
     * @param mixed  $value Value to parse
     *
     * @return mixed
     *
     * @since 1.0.0
     */
    public function parseValue(string $type, mixed $value = null) : mixed
    {
        if ($value === null) {
            return null;
        } elseif ($type === 'int') {
            return (int) $value;
        } elseif ($type === 'string') {
            return (string) $value;
        } elseif ($type === 'float') {
            return (float) $value;
        } elseif ($type === 'bool') {
            return (bool) $value;
        } elseif ($type === 'DateTime' || $type === 'DateTimeImmutable') {
            return $value === null ? null : $value->format($this->mapper::$datetimeFormat);
        } elseif ($type === 'Json') {
            return (string) \json_encode($value);
        } elseif ($type === 'compress') {
            return (string) \gzdeflate($value);
        } elseif ($type === 'Serializable') {
            return $value->serialize();
        } elseif (\is_object($value) && \method_exists($value, 'getId')) {
            return $value->getId();
        }

        return $value;
    }

    /**
     * Execute mapper
     *
     * @param mixed ...$options Data for the mapper
     *
     * @return mixed
     *
     * @since 1.0.0
     */
    abstract public function execute(mixed ...$options) : mixed;
}
