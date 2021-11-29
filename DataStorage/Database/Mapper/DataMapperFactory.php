<?php
/**
 * Orange Management
 *
 * PHP Version 8.0
 *
 * @package   phpOMS\DataStorage\Database\Mapper
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\DataStorage\Database\Mapper;

use phpOMS\DataStorage\Database\Connection\ConnectionAbstract;
use phpOMS\DataStorage\Database\Query\Builder;

/**
 * Mapper factory.
 *
 * @package phpOMS\DataStorage\Database\Mapper
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
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
     * @var array<string, array{mapper:string, external:string, by?:string, column?:string, conditional?:bool}>
     * @since 1.0.0
     */
    public const OWNS_ONE = [];

    /**
     * Belongs to.
     *
     * @var array<string, array{mapper:string, external:string}>
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
     * @var string
     * @since 1.0.0
     */
    public const PARENT = '';

    /**
     * Model to use by the mapper.
     *
     * @var string
     * @since 1.0.0
     */
    public const MODEL = '';

	/**
     * Database connection.
     *
     * @var ConnectionAbstract
     * @since 1.0.0
     */
	public static ConnectionAbstract $db;

	/**
     * Initialized objects for cross reference to reduce initialization costs
     *
     * @var array[]
     * @since 1.0.0
     */
    protected static array $initObjects = [];

	/**
     * Constructor.
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    private function __construct()
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

    public static function db(ConnectionAbstract $db = null) : string
    {
        self::$db = $db;

        return static::class;
    }

    public static function reader(ConnectionAbstract $db = null) : ReadMapper
	{
		return new ReadMapper(new static(), $db ?? self::$db);
	}

	public static function get(ConnectionAbstract $db = null) : ReadMapper
	{
		return (new ReadMapper(new static(), $db ?? self::$db))->get();
	}

	public static function getRaw(ConnectionAbstract $db = null) : ReadMapper
	{
		return (new ReadMapper(new static(), $db ?? self::$db))->getRaw();
	}

	public static function getRandom(ConnectionAbstract $db = null) : ReadMapper
	{
		return (new ReadMapper(new static(), $db ?? self::$db))->getRandom();
	}

	public static function count(ConnectionAbstract $db = null) : ReadMapper
	{
		return (new ReadMapper(new static(), $db ?? self::$db))->count();
	}

	public static function getQuery(ConnectionAbstract $db = null) : Builder
	{
		return (new ReadMapper(new static(), $db ?? self::$db))->getQuery();
	}

	public static function getAll(ConnectionAbstract $db = null) : ReadMapper
	{
		return (new ReadMapper(new static(), $db ?? self::$db))->getAll();
    }

    public static function writer(ConnectionAbstract $db = null) : WriteMapper
	{
		return new WriteMapper(new static(), $db ?? self::$db);
	}

    public static function create(ConnectionAbstract $db = null) : WriteMapper
	{
		return (new WriteMapper(new static(), $db ?? self::$db))->create();
    }

    public static function updater(ConnectionAbstract $db = null) : UpdateMapper
	{
		return new UpdateMapper(new static(), $db ?? self::$db);
	}

	public static function update(ConnectionAbstract $db = null) : UpdateMapper
	{
		return (new UpdateMapper(new static(), $db ?? self::$db))->update();
    }

    public static function remover(ConnectionAbstract $db = null) : DeleteMapper
	{
		return new DeleteMapper(new static(), $db ?? self::$db);
	}

	public static function delete(ConnectionAbstract $db = null) : DeleteMapper
	{
		return (new DeleteMapper(new static(), $db ?? self::$db))->delete();
	}

	/**
     * Add initialized object to local cache
     *
     * @param string $mapper Mapper name
     * @param mixed  $id     Object id
     * @param object $obj    Model to cache locally
     *
     * @return void
     *
     * @since 1.0.0
     */
    public static function addInitialized(string $mapper, mixed $id, object $obj = null) : void
    {
        if (!isset(self::$initObjects[$mapper])) {
            self::$initObjects[$mapper] = [];
        }

        self::$initObjects[$mapper][$id] = [
            'obj'      => $obj,
        ];
    }

    /**
     * Check if a object is initialized
     *
     * @param string $mapper Mapper name
     * @param mixed  $id     Object id
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public static function isInitialized(string $mapper, mixed $id) : bool
    {
        return !empty($id)
            && isset(self::$initObjects[$mapper], self::$initObjects[$mapper][$id]);
    }

    /**
     * Clear cache
     *
     * @return void
     *
     * @since 1.0.0
     */
    public static function clearCache() : void
    {
        self::$initObjects = [];
    }


    /**
     * Get initialized object
     *
     * @param string $mapper Mapper name
     * @param mixed  $id     Object id
     *
     * @return mixed
     *
     * @since 1.0.0
     */
    public static function getInitialized(string $mapper, mixed $id) : mixed
    {
        if (!self::isInitialized($mapper, $id)) {
            return null;
        }

        return self::$initObjects[$mapper][$id]['obj'] ?? null;
    }

    /**
     * Remove initialized object
     *
     * @param string $mapper Mapper name
     * @param mixed  $id     Object id
     *
     * @return void
     *
     * @since 1.0.0
     */
    public static function removeInitialized(string $mapper, mixed $id) : void
    {
        if (isset(self::$initObjects[$mapper][$id])) {
            unset(self::$initObjects[$mapper][$id]);
        }
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
     * @return mixed
     *
     * @since 1.0.0
     */
    public static function createBaseModel() : mixed
    {
        $class = empty(static::MODEL) ? \substr(static::class, 0, -6) : static::MODEL;

        /**
         * @todo Orange-Management/phpOMS#67
         *  Since some models require special initialization a model factory should be implemented.
         *  This could be a simple initialize() function in the mapper where the default initialize() is the current defined empty initialization in the DataMapperAbstract.
         */
        return new $class();
    }

    /**
     * Get model from mapper
     *
     * @return string
     *
     * @since 1.0.0
     */
    public static function getModelName() : string
    {
        return empty(static::MODEL) ? \substr(static::class, 0, -6) : static::MODEL;
    }

	/**
     * Get id of object
     *
     * @param object           $obj      Model to create
     * @param \ReflectionClass $refClass Reflection class
     * @param string           $member   Member name for the id, if it is not the primary key
     *
     * @return mixed
     *
     * @since 1.0.0
     */
    public static function getObjectId(object $obj, \ReflectionClass $refClass = null, string $member = null) : mixed
    {
        $refClass   ??= new \ReflectionClass($obj);
        $propertyName = $member ?? static::COLUMNS[static::PRIMARYFIELD]['internal'];
        $refProp      = $refClass->getProperty($propertyName);

        if (!$refProp->isPublic()) {
            $refProp->setAccessible(true);
            $objectId = $refProp->getValue($obj);
            $refProp->setAccessible(false);
        } else {
            $objectId = $obj->{$propertyName};
        }

        return $objectId;
    }

    /**
     * Set id to model
     *
     * @param \ReflectionClass $refClass Reflection class
     * @param object           $obj      Object to create
     * @param mixed            $objId    Id to set
     *
     * @return void
     *
     * @since 1.0.0
     */
    public static function setObjectId(\ReflectionClass $refClass, object $obj, mixed $objId) : void
    {
        $propertyName = static::COLUMNS[static::PRIMARYFIELD]['internal'];
        $refProp      = $refClass->getProperty($propertyName);

        \settype($objId, static::COLUMNS[static::PRIMARYFIELD]['type']);
        if (!$refProp->isPublic()) {
            $refProp->setAccessible(true);
            $refProp->setValue($obj, $objId);
            $refProp->setAccessible(false);
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
}
