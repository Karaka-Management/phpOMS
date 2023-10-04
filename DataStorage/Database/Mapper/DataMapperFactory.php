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
use phpOMS\DataStorage\Database\Query\OrderType;
use phpOMS\DataStorage\Database\Query\Where;

/**
 * Mapper factory.
 *
 * @package phpOMS\DataStorage\Database\Mapper
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 *
 * @template T
 */
class DataMapperFactory
{
    /**
     * Datetime format of the database datetime
     *
     * This is only for the datetime stored in the database not the generated query.
     * For the query check the datetime in Grammar:$datetimeFormat
     *
     * @var string
     * @since 1.0.0
     */
    public static string $datetimeFormat = 'Y-m-d H:i:s';

    /**
     * Primary field name.
     *
     * @var string
     * @since 1.0.0
     */
    public const PRIMARYFIELD = '';

    /**
     * Autoincrement primary field.
     *
     * @var bool
     * @since 1.0.0
     */
    public const AUTOINCREMENT = true;

    /**
     * Primary field name.
     *
     * @var string
     * @since 1.0.0
     */
    public const CREATED_AT = '';

    /**
     * Columns.
     *
     * @var array<string, array{name:string, type:string, internal:string, autocomplete?:bool, readonly?:bool, writeonly?:bool, annotations?:array}>
     * @since 1.0.0
     */
    public const COLUMNS = [];

    /**
     * Has many relation.
     *
     * @var array<string, array>
     * @since 1.0.0
     */
    public const HAS_MANY = [];

    /**
     * Relations.
     *
     * Relation is defined in current mapper
     *
     * @var array<string, array{mapper:class-string, external:string, by?:string, column?:string, conditional?:bool}>
     * @since 1.0.0
     */
    public const OWNS_ONE = [];

    /**
     * Belongs to.
     *
     * @var array<string, array{mapper:class-string, external:string, column?:string, by?:string}>
     * @since 1.0.0
     */
    public const BELONGS_TO = [];

    /**
     * Table.
     *
     * @var string
     * @since 1.0.0
     */
    public const TABLE = '';

    /**
     * Parent column.
     *
     * @var class-string
     * @since 1.0.0
     */
    public const PARENT = '';

    /**
     * Model to use by the mapper.
     *
     * @var class-string<T>
     * @since 1.0.0
     */
    public const MODEL = '';

    /**
     * Model factory to use by the mapper.
     *
     * @var class-string
     * @since 1.0.0
     */
    public const FACTORY = '';

    /**
     * Database connection.
     *
     * @var ConnectionAbstract
     * @since 1.0.0
     */
    protected static ConnectionAbstract $db;

    /**
     * Constructor.
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    final private function __construct()
    {
    }

    /**
     * Clone.
     *
     * @return void
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    private function __clone()
    {
    }

    /**
     * Set default database connection
     *
     * @param ConnectionAbstract $db Database connection
     *
     * @return class-string<self>
     *
     * @since 1.0.0
     */
    public static function db(ConnectionAbstract $db) : string
    {
        self::$db = $db;

        return static::class;
    }

    /**
     * Create read mapper
     *
     * @param ConnectionAbstract $db Database connection
     *
     * @return ReadMapper
     *
     * @since 1.0.0
     */
    public static function reader(ConnectionAbstract $db = null) : ReadMapper
    {
        return new ReadMapper(new static(), $db ?? self::$db);
    }

    /**
     * Create read mapper
     *
     * @param ConnectionAbstract $db Database connection
     *
     * @return ReadMapper<T>
     *
     * @since 1.0.0
     */
    public static function get(ConnectionAbstract $db = null) : ReadMapper
    {
        /** @var ReadMapper<T> $reader */
        $reader = new ReadMapper(new static(), $db ?? self::$db);

        return $reader->get();
    }

    /**
     * Create read mapper
     *
     * @param ConnectionAbstract $db Database connection
     *
     * @return ReadMapper
     *
     * @since 1.0.0
     */
    public static function getRaw(ConnectionAbstract $db = null) : ReadMapper
    {
        /** @var ReadMapper<T> $reader */
        $reader = new ReadMapper(new static(), $db ?? self::$db);

        return $reader->getRaw();
    }

    /**
     * Create read mapper
     *
     * @param ConnectionAbstract $db Database connection
     *
     * @return ReadMapper
     *
     * @since 1.0.0
     */
    public static function getRandom(ConnectionAbstract $db = null) : ReadMapper
    {
        return (new ReadMapper(new static(), $db ?? self::$db))->getRandom();
    }

    /**
     * Create read mapper
     *
     * @param ConnectionAbstract $db Database connection
     *
     * @return ReadMapper
     *
     * @since 1.0.0
     */
    public static function count(ConnectionAbstract $db = null) : ReadMapper
    {
        return (new ReadMapper(new static(), $db ?? self::$db))->count();
    }

    /**
     * Create read mapper
     *
     * @param ConnectionAbstract $db Database connection
     *
     * @return ReadMapper
     *
     * @since 1.0.0
     */
    public static function exists(ConnectionAbstract $db = null) : ReadMapper
    {
        return (new ReadMapper(new static(), $db ?? self::$db))->exists();
    }

    /**
     * Create read mapper
     *
     * @param ConnectionAbstract $db Database connection
     *
     * @return Builder
     *
     * @since 1.0.0
     */
    public static function getQuery(ConnectionAbstract $db = null) : Builder
    {
        return (new ReadMapper(new static(), $db ?? self::$db))->getQuery();
    }

    /**
     * Create read mapper
     *
     * @param ConnectionAbstract $db Database connection
     *
     * @return ReadMapper
     *
     * @since 1.0.0
     */
    public static function getAll(ConnectionAbstract $db = null) : ReadMapper
    {
        /** @var ReadMapper<T> $reader */
        $reader = new ReadMapper(new static(), $db ?? self::$db);

        return $reader->getAll();
    }

    /**
     * Create write mapper
     *
     * @param ConnectionAbstract $db Database connection
     *
     * @return WriteMapper
     *
     * @since 1.0.0
     */
    public static function writer(ConnectionAbstract $db = null) : WriteMapper
    {
        return new WriteMapper(new static(), $db ?? self::$db);
    }

    /**
     * Create write mapper
     *
     * @param ConnectionAbstract $db Database connection
     *
     * @return WriteMapper
     *
     * @since 1.0.0
     */
    public static function create(ConnectionAbstract $db = null) : WriteMapper
    {
        return (new WriteMapper(new static(), $db ?? self::$db))->create();
    }

    /**
     * Create update mapper
     *
     * @param ConnectionAbstract $db Database connection
     *
     * @return UpdateMapper
     *
     * @since 1.0.0
     */
    public static function updater(ConnectionAbstract $db = null) : UpdateMapper
    {
        return new UpdateMapper(new static(), $db ?? self::$db);
    }

    /**
     * Create update mapper
     *
     * @param ConnectionAbstract $db Database connection
     *
     * @return UpdateMapper
     *
     * @since 1.0.0
     */
    public static function update(ConnectionAbstract $db = null) : UpdateMapper
    {
        return (new UpdateMapper(new static(), $db ?? self::$db))->update();
    }

    /**
     * Create delete mapper
     *
     * @param ConnectionAbstract $db Database connection
     *
     * @return DeleteMapper
     *
     * @since 1.0.0
     */
    public static function remover(ConnectionAbstract $db = null) : DeleteMapper
    {
        return new DeleteMapper(new static(), $db ?? self::$db);
    }

    /**
     * Create delete mapper
     *
     * @param ConnectionAbstract $db Database connection
     *
     * @return DeleteMapper
     *
     * @since 1.0.0
     */
    public static function delete(ConnectionAbstract $db = null) : DeleteMapper
    {
        return (new DeleteMapper(new static(), $db ?? self::$db))->delete();
    }

    /**
     * Test if object is null object
     *
     * @param mixed $obj Object to check
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public static function isNullModel(mixed $obj) : bool
    {
        return \is_object($obj) && \strpos(\get_class($obj), '\Null') !== false;
    }

    /**
     * Creates the current null object
     *
     * @param mixed $id Model id
     *
     * @return mixed
     *
     * @since 1.0.0
     */
    public static function createNullModel(mixed $id = null) : mixed
    {
        $class     = empty(static::MODEL) ? \substr(static::class, 0, -6) : static::MODEL;
        $parts     = \explode('\\', $class);
        $name      = $parts[$c = (\count($parts) - 1)];
        $parts[$c] = 'Null' . $name;
        $class     = \implode('\\', $parts);

        return $id !== null ? new $class($id) : new $class();
    }

    /**
     * Create the empty base model
     *
     * @param null|array $data Data to use for initialization
     *
     * @return object
     *
     * @since 1.0.0
     */
    public static function createBaseModel(array $data = null) : object
    {
        if (empty(static::FACTORY)) {
            $class = empty(static::MODEL) ? \substr(static::class, 0, -6) : static::MODEL;

            return new $class();
        }

        return static::FACTORY::createWith($data);
    }

    /**
     * Get id of object
     *
     * @param object                $obj      Model to create
     * @param string                $member   Member name for the id, if it is not the primary key
     * @param null|\ReflectionClass $refClass Reflection class
     *
     * @return mixed
     *
     * @since 1.0.0
     */
    public static function getObjectId(object $obj, string $member = null, \ReflectionClass &$refClass = null) : mixed
    {
        $propertyName = $member ?? static::COLUMNS[static::PRIMARYFIELD]['internal'];

        if (static::COLUMNS[static::PRIMARYFIELD]['private'] ?? false) {
            if ($refClass === null) {
                $refClass = new \ReflectionClass($obj);
            }

            $refProp = $refClass->getProperty($propertyName);

            return $refProp->getValue($obj);
        } else {
            return $obj->{$propertyName};
        }
    }

    /**
     * Set id to model
     *
     * @param object                $obj      Object to create
     * @param mixed                 $objId    Id to set
     * @param null|\ReflectionClass $refClass Reflection class
     *
     * @return void
     *
     * @since 1.0.0
     */
    public static function setObjectId(object $obj, mixed $objId, \ReflectionClass &$refClass = null) : void
    {
        $propertyName = static::COLUMNS[static::PRIMARYFIELD]['internal'];
        \settype($objId, static::COLUMNS[static::PRIMARYFIELD]['type']);

        if (static::COLUMNS[static::PRIMARYFIELD]['private'] ?? false) {
            if ($refClass === null) {
                $refClass = new \ReflectionClass($obj);
            }

            $refProp = $refClass->getProperty($propertyName);
            $refProp->setValue($obj, $objId);
        } else {
            $obj->{$propertyName} = $objId;
        }
    }

    /**
     * Find database column name by member name
     *
     * @param string $name member name
     *
     * @return null|string
     *
     * @since 1.0.0
     */
    public static function getColumnByMember(string $name) : ?string
    {
        foreach (static::COLUMNS as $cName => $column) {
            if ($column['internal'] === $name) {
                return $cName;
            }
        }

        return null;
    }

    /**
     * Find data.
     *
     * @param string             $search       Search string
     * @param DataMapperAbstract $mapper       Mapper to populate
     * @param int                $id           Pivot element id
     * @param string             $secondaryId  secondary id which becomes necessary for sorted results
     * @param string             $type         Page type (p = get previous elements, n = get next elements)
     * @param int                $pageLimit    Limit result set
     * @param string             $sortBy       Model member name to sort by
     * @param string             $sortOrder    Sort order
     * @param array              $searchFields Fields to search in. ([] = all) @todo: maybe change to all which have autocomplete = true defined?
     * @param array              $filters      Additional search filters applied ['type', 'value1', 'logic1', 'value2', 'logic2']
     *
     * @return array{hasPrevious:bool, hasNext:bool, data:object[]}
     *
     * @since 1.0.0
     */
    public static function find(
        string $search = null,
        DataMapperAbstract $mapper = null,
        int $id = 0,
        string $secondaryId = '',
        string $type = null,
        int $pageLimit = 25,
        string $sortBy = null,
        string $sortOrder = OrderType::DESC,
        array $searchFields = [],
        array $filters = []
    ) : array {
        $mapper  ??= static::getAll();
        $sortOrder = \strtoupper($sortOrder);

        $data = [];

        $type        = $id === 0 ? null : $type;
        $hasPrevious = false;
        $hasNext     = false;

        $primarySortField = static::COLUMNS[static::PRIMARYFIELD]['internal'];

        $sortBy = empty($sortBy) || static::getColumnByMember($sortBy) === null ? $primarySortField : $sortBy;

        $sortById    = $sortBy === $primarySortField;
        $secondaryId = $sortById ? $id : $secondaryId;

        foreach ($filters as $key => $filter) {
            $mapper->where($key, '%' . $filter['value1'] . '%', $filter['logic1'] ?? 'like');

            if (!empty($filter['value2'])) {
                $mapper->where($key, '%' . $filter['value2'] . '%', $filter['logic2'] ?? 'like');
            }
        }

        if (!empty($search)) {
            $where   = new Where(static::$db);
            $counter = 0;

            if (empty($searchFields)) {
                foreach (static::COLUMNS as $column) {
                    $searchFields[] = $column['internal'];
                }
            }

            foreach ($searchFields as $searchField) {
                if (($column = static::getColumnByMember($searchField)) === null) {
                    continue;
                }

                $where->where($column, 'like', '%' . $search . '%', 'OR');
                ++$counter;
            }

            if ($counter > 0) {
                $mapper->where('', $where);
            }
        }

        // @todo: how to handle columns which are NOT members (columns which are manipulated)
        //          Maybe pass callback array which can handle these cases?

        if ($type === 'p') {
            $cloned = clone $mapper;
            $mapper->sort(
                    $sortBy,
                    $sortOrder === OrderType::DESC ? OrderType::ASC : OrderType::DESC
                )
                ->where($sortBy, $secondaryId, $sortOrder === OrderType::DESC ? '>=' : '<=')
                ->limit($pageLimit + 2);

            if (!$sortById) {
                $where = new Where(static::$db);
                $where->where(static::PRIMARYFIELD, '>=', $id)
                    ->orWhere(
                        static::getColumnByMember($sortBy),
                        $sortOrder === OrderType::DESC ? '>' : '<',
                        $secondaryId
                    );

                $mapper->where('', $where)
                    ->sort($primarySortField, OrderType::ASC);
            }

            $data = $mapper->execute();

            if (($count = \count($data)) < 2) {
                $cloned->sort($sortBy, $sortOrder)
                    ->limit($pageLimit + 1);

                if (!$sortById) {
                    $where = new Where(static::$db);
                    $where->where(static::PRIMARYFIELD, '<=', $id)
                        ->orWhere(
                            static::getColumnByMember($sortBy),
                            $sortOrder === OrderType::DESC ? '<' : '>',
                            $secondaryId
                        );

                    $cloned->where('', $where)
                        ->sort($primarySortField, OrderType::DESC);
                }

                $data = $cloned->execute();

                $hasNext = $count > $pageLimit;
                if ($hasNext) {
                    \array_pop($data);
                    --$count;
                }
            } else {
                if (\reset($data)->getId() === $id) {
                    \array_shift($data);
                    $hasNext = true;
                    --$count;
                }

                if ($count > $pageLimit) {
                    if (!$hasNext) { // @todo: can be maybe removed?
                        \array_pop($data);
                        $hasNext = true;
                        --$count;
                    }

                    if ($count > $pageLimit) {
                        $hasPrevious = true;
                        \array_pop($data);
                    }
                }

                $data = \array_reverse($data);
            }
        } elseif ($type === 'n') {
            $mapper = $mapper->sort($sortBy, $sortOrder)
                ->where($sortBy, $secondaryId, $sortOrder === OrderType::DESC ? '<=' : '>=')
                ->limit($pageLimit + 2);

            if (!$sortById) {
                $where = new Where(static::$db);
                $where->where(static::PRIMARYFIELD, '<=', $id)
                    ->orWhere(
                        static::getColumnByMember($sortBy),
                        $sortOrder === OrderType::DESC ? '<' : '>',
                        $secondaryId
                    );

                $mapper = $mapper
                    ->where('', $where)
                    ->sort($primarySortField, OrderType::DESC);
            }

            $data  = $mapper->execute();
            $count = \count($data);

            if ($count < 1) {
                return [
                    'hasPrevious' => false,
                    'hasNext'     => false,
                    'data'        => [],
                ];
            }

            if (\reset($data)->getId() === $id) {
                \array_shift($data);
                $hasPrevious = true;
                --$count;
            }

            if ($count > $pageLimit) {
                \array_pop($data);
                $hasNext = true;
                --$count;
            }

            if ($count > $pageLimit) {
                \array_pop($data);
                --$count;
            }
        } else {
            $mapper->sort($sortBy, $sortOrder)
                ->limit($pageLimit + 1);

            if (!$sortById) {
                $mapper->sort($primarySortField, OrderType::DESC);
            }

            $data = $mapper->execute();

            $hasNext = ($count = \count($data)) > $pageLimit;
            if ($hasNext) {
                \array_pop($data);
            }
        }

        return [
            'hasPrevious' => $hasPrevious,
            'hasNext'     => $hasNext,
            'data'        => $data,
        ];
    }
}
