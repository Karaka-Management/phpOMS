<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    phpOMS\DataStorage\Database
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */
declare(strict_types=1);

namespace phpOMS\DataStorage\Database;

use phpOMS\DataStorage\Database\Connection\ConnectionAbstract;
use phpOMS\DataStorage\Database\Exception\InvalidMapperException;
use phpOMS\DataStorage\Database\Query\Builder;
use phpOMS\DataStorage\Database\Query\QueryType;
use phpOMS\DataStorage\DataMapperInterface;
use phpOMS\Message\RequestAbstract;
use phpOMS\Utils\ArrayUtils;

/**
 * Datamapper for databases.
 *
 * DB, Cache, Session
 *
 * @package    phpOMS\DataStorage\Database
 * @license    OMS License 1.0
 * @link       http://website.orange-management.de
 * @since      1.0.0
 */
class DataMapperAbstract implements DataMapperInterface
{
    /**
     * Database connection.
     *
     * @var ConnectionAbstract
     * @since 1.0.0
     */
    protected static $db = null;

    /**
     * Overwriting extended values.
     *
     * @var bool
     * @since 1.0.0
     */
    protected static $overwrite = true;

    /**
     * Primary field name.
     *
     * @var string
     * @since 1.0.0
     */
    protected static $primaryField = '';

    /**
     * Primary field name.
     *
     * @var string
     * @since 1.0.0
     */
    protected static $createdAt = '';

    /**
     * Language
     *
     * @var string
     * @since 1.0.0
     */
    protected static $languageField = '';

    /**
     * Columns.
     *
     * @var array<string, array<string, string>>
     * @since 1.0.0
     */
    protected static $columns = [];

    /**
     * Has many relation.
     *
     * @var array<string, array>
     * @since 1.0.0
     */
    protected static $hasMany = [];

    /**
     * Relations.
     *
     * Relation is defined in current mapper
     *
     * @var array<string, array>
     * @since 1.0.0
     */
    protected static $ownsOne = [];

    /**
     * Relations.
     *
     * Relation is defined in current mapper
     *
     * @var string[]
     * @since 1.0.0
     */    /**
     * Belongs to.
     *
     * @var array<string, array<string, string>>
     * @since 1.0.0
     */
    protected static $belongsTo = [];

    /**
     * Table.
     *
     * @var string
     * @since 1.0.0
     */
    protected static $table = '';

    /**
     * Fields to load.
     *
     * @var array[]
     * @since 1.0.0
     */
    protected static $fields = [];

    /**
     * Initialized objects for cross reference to reduce initialization costs
     *
     * @var array[]
     * @since 1.0.0
     */
    protected static $initObjects = [];

    /**
     * Initialized arrays for cross reference to reduce initialization costs
     *
     * @var array[]
     * @since 1.0.0
     */
    protected static $initArrays = [];

    /**
     * Highest mapper to know when to clear initialized objects
     *
     * @var null|string
     * @since 1.0.0
     */
    protected static $parentMapper = null;

    /**
     * Extended value collection.
     *
     * @var array
     * @since 1.0.0
     */
    protected static $collection = [
        'primaryField' => [],
        'createdAt'    => [],
        'columns'      => [],
        'hasMany'      => [],
        'ownsOne'      => [],
        'table'        => [],
    ];

    /**
     * Constructor.
     *
     * @since  1.0.0
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
     * @since  1.0.0
     * @codeCoverageIgnore
     */
    private function __clone()
    {
    }

    /**
     * Set database connection.
     *
     * @param ConnectionAbstract $con Database connection
     *
     * @return void
     *
     * @since  1.0.0
     */
    public static function setConnection(ConnectionAbstract $con) : void
    {
        self::$db = $con;
    }

    /**
     * Get primary field.
     *
     * @return string
     *
     * @since  1.0.0
     */
    public static function getPrimaryField() : string
    {
        return static::$primaryField;
    }

    /**
     * Get main table.
     *
     * @return string
     *
     * @since  1.0.0
     */
    public static function getTable() : string
    {
        return static::$table;
    }

    /**
     * Collect values from extension.
     *
     * @param mixed $class Current extended mapper
     *
     * @return void
     *
     * @since  1.0.0
     */
    private static function extend($class) : void
    {
        /* todo: have to implement this in the queries, so far not used */
        self::$collection['primaryField'][] = $class::$primaryField;
        self::$collection['createdAt'][]    = $class::$createdAt;
        self::$collection['columns'][]      = $class::$columns;
        self::$collection['hasMany'][]      = $class::$hasMany;
        self::$collection['ownsOne'][]      = $class::$ownsOne;
        self::$collection['table'][]        = $class::$table;

        if (($parent = \get_parent_class($class)) !== false && !$class::$overwrite) {
            self::extend($parent);
        }
    }

    /**
     * Load.
     *
     * @param array ...$objects Objects to load
     *
     * @return void
     *
     * @since  1.0.0
     */
    public static function with(...$objects) : void
    {
        // todo: how to handle with of parent objects/extends/relations

        self::$fields = $objects;
    }

    /**
     * Resets all loaded mapper variables.
     *
     * This is used after one action is performed otherwise other models would use wrong settings.
     *
     * @return void
     *
     * @since  1.0.0
     */
    public static function clear() : void
    {
        self::$overwrite    = true;
        self::$primaryField = '';
        self::$createdAt    = '';
        self::$columns      = [];
        self::$hasMany      = [];
        self::$ownsOne      = [];
        self::$table        = '';
        self::$fields       = [];
        self::$collection   = [
            'primaryField' => [],
            'createdAt'    => [],
            'columns'      => [],
            'ownsMany'     => [],
            'ownsOne'      => [],
            'table'        => [],
        ];

        // clear parent and objects
        if (static::class === self::$parentMapper) {
            //self::$initObjects = []; // todo: now all objects are cached for the whole request
            //self::$initArrays = []; // todo: now all objects are cached for the whole request
            self::$parentMapper = null;
        }
    }

    /**
     * Find data.
     *
     * @param string $search Search for
     *
     * @return array
     *
     * @since  1.0.0
     */
    public static function find(string $search) : array
    {
        self::extend(__CLASS__);

        $query = static::getQuery();

        foreach (static::$columns as $col) {
            if (isset($col['autocomplete']) && $col['autocomplete']) {
                $query->where(static::$table . '.' . $col['name'], 'LIKE', '%' . $search . '%', 'OR');
            }
        }

        return static::getAllByQuery($query);
    }

    /**
     * Create object in db.
     *
     * @param mixed $obj       Object reference (gets filled with insert id)
     * @param int   $relations Create all relations as well
     * @param bool  $force     Force creation even if id is set in model
     *
     * @return mixed
     *
     * @since  1.0.0
     */
    public static function create($obj, int $relations = RelationType::ALL, bool $force = false)
    {
        self::extend(__CLASS__);

        if (!isset($obj) || self::isNullObject($obj)) {
            return null;
        }

        $refClass = new \ReflectionClass($obj);

        // todo: remove force and instead check if object is autoincrement. if not autoincrement then create even if id is set!!!
        if (!empty($id = self::getObjectId($obj, $refClass)) && !$force) {
            $objId = $id;
        } else {
            $objId = self::createModel($obj, $refClass);
            self::setObjectId($refClass, $obj, $objId);
        }

        if ($relations === RelationType::ALL) {
            self::createHasMany($refClass, $obj, $objId);
        }

        return $objId;
    }

    /**
     * Create object in db.
     *
     * @param array $obj       Object reference (gets filled with insert id)
     * @param int   $relations Create all relations as well
     *
     * @return mixed
     *
     * @since  1.0.0
     */
    public static function createArray(array &$obj, int $relations = RelationType::ALL)
    {
        self::extend(__CLASS__);

        if (!empty($id = $obj[static::$columns[static::$primaryField]['internal']])) {
            $objId = $id;
        } else {
            $objId = self::createModelArray($obj);
            \settype($objId, static::$columns[static::$primaryField]['type']);
            $obj[static::$columns[static::$primaryField]['internal']] = $objId;
        }

        if ($relations === RelationType::ALL) {
            self::createHasManyArray($obj, $objId);
        }

        return $objId;
    }

    /**
     * Create base model.
     *
     * @param object           $obj      Model to create
     * @param \ReflectionClass $refClass Reflection class
     *
     * @return mixed
     *
     * @since  1.0.0
     */
    private static function createModel(object $obj, \ReflectionClass $refClass)
    {
        $query = new Builder(self::$db);
        $query->prefix(self::$db->getPrefix())->into(static::$table);

        foreach (static::$columns as $key => $column) {
            $propertyName = \stripos($column['internal'], '/') !== false ? \explode('/', $column['internal'])[0] : $column['internal'];
            if (isset(static::$hasMany[$propertyName])) {
                continue;
            }

            $property = $refClass->getProperty($propertyName);

            if (!($isPublic = $property->isPublic())) {
                $property->setAccessible(true);
            }

            if (isset(static::$ownsOne[$propertyName])) {
                $id    = self::createOwnsOne($propertyName, $property->getValue($obj));
                $value = self::parseValue($column['type'], $id);

                $query->insert($column['name'])->value($value);
            } elseif (isset(static::$belongsTo[$propertyName])) {
                $id    = self::createBelongsTo($propertyName, $property->getValue($obj));
                $value = self::parseValue($column['type'], $id);

                $query->insert($column['name'])->value($value);
            } elseif ($column['name'] !== static::$primaryField || !empty($property->getValue($obj))) {
                $tValue = $property->getValue($obj);
                if (\stripos($column['internal'], '/') !== false) {
                    $path = \explode('/', $column['internal']);

                    \array_shift($path);
                    $path   = \implode('/', $path);
                    $tValue = ArrayUtils::getArray($path, $tValue, '/');
                }

                $value = self::parseValue($column['type'], $tValue);

                $query->insert($column['name'])->value($value);
            }

            if (!$isPublic) {
                $property->setAccessible(false);
            }
        }

        // if a table only has a single column = primary key column. This must be done otherwise the query is empty
        if ($query->getType() === QueryType::NONE) {
            $query->insert(static::$primaryField)->value(0);
        }

        self::$db->con->prepare($query->toSql())->execute();

        return self::$db->con->lastInsertId();
    }

    /**
     * Create base model.
     *
     * @param array $obj Model to create
     *
     * @return mixed
     *
     * @since  1.0.0
     */
    private static function createModelArray(array &$obj)
    {
        $query = new Builder(self::$db);
        $query->prefix(self::$db->getPrefix())->into(static::$table);

        foreach (static::$columns as $key => $column) {
            if (isset(static::$hasMany[$key])) {
                continue;
            }

            $path = $column['internal'];
            if (\stripos($column['internal'], '/') !== false) {
                $path = \explode('/', $column['internal']);

                \array_shift($path); // todo: why am I doing this?
                $path = \implode('/', $path);
            }

            $property = ArrayUtils::getArray($path, $obj, '/');

            if (isset(static::$ownsOne[$path])) {
                $id    = self::createOwnsOneArray($column['internal'], $property);
                $value = self::parseValue($column['type'], $id);

                $query->insert($column['name'])->value($value);
            } elseif (isset(static::$belongsTo[$path])) {
                $id    = self::createBelongsToArray($column['internal'], $property);
                $value = self::parseValue($column['type'], $id);

                $query->insert($column['name'])->value($value);
            } elseif ($column['internal'] === $path && $column['name'] !== static::$primaryField) {
                $value = self::parseValue($column['type'], $property);

                $query->insert($column['name'])->value($value);
            }
        }

        // if a table only has a single column = primary key column. This must be done otherwise the query is empty
        if ($query->getType() === QueryType::NONE) {
            $query->insert(static::$primaryField)->value(0);
        }

        self::$db->con->prepare($query->toSql())->execute();

        return self::$db->con->lastInsertId();
    }

    /**
     * Get id of object
     *
     * @param object           $obj      Model to create
     * @param \ReflectionClass $refClass Reflection class
     *
     * @return mixed
     *
     * @since  1.0.0
     */
    private static function getObjectId(object $obj, \ReflectionClass $refClass = null)
    {
        $refClass = $refClass ?? new \ReflectionClass($obj);
        $refProp  = $refClass->getProperty(static::$columns[static::$primaryField]['internal']);

        if (!($isPublic = $refProp->isPublic())) {
            $refProp->setAccessible(true);
        }

        $objectId = $refProp->getValue($obj);

        if (!$isPublic) {
            $refProp->setAccessible(false);
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
     * @since  1.0.0
     */
    private static function setObjectId(\ReflectionClass $refClass, object $obj, $objId) : void
    {
        $refProp = $refClass->getProperty(static::$columns[static::$primaryField]['internal']);

        if (!($isPublic = $refProp->isPublic())) {
            $refProp->setAccessible(true);
        }

        \settype($objId, static::$columns[static::$primaryField]['type']);
        $refProp->setValue($obj, $objId);

        if (!$isPublic) {
            $refProp->setAccessible(false);
        }
    }

    /**
     * Create relation
     *
     * This is only possible for hasMany objects which are stored in a relation table
     *
     * @param string $member Member name of the relation
     * @param mixed  $id1    Id of the primary object
     * @param mixed  $id2    Id of the secondary object
     *
     * @return bool
     *
     * @since  1.0.0
     */
    public static function createRelation(string $member, $id1, $id2) : bool
    {
        if (!isset(static::$hasMany[$member]) || !isset(static::$hasMany[$member]['src'])) {
            return false;
        }

        self::createRelationTable($member, \is_array($id2) ? $id2 : [$id2], $id1);

        return true;
    }

    /**
     * Create has many
     *
     * @param \ReflectionClass $refClass Reflection class
     * @param object           $obj      Object to create
     * @param mixed            $objId    Id to set
     *
     * @return void
     *
     * @throws InvalidMapperException Throws this exception if the mapper in the has many relation is invalid
     *
     * @since  1.0.0
     */
    private static function createHasMany(\ReflectionClass $refClass, object $obj, $objId) : void
    {
        foreach (static::$hasMany as $propertyName => $rel) {
            $property = $refClass->getProperty($propertyName);

            if (!($isPublic = $property->isPublic())) {
                $property->setAccessible(true);
            }

            $values = $property->getValue($obj);

            if (!$isPublic) {
                $property->setAccessible(false);
            }

            if (!isset(static::$hasMany[$propertyName]['mapper'])) {
                throw new InvalidMapperException();
            }

            /** @var string $mapper */
            $mapper             = static::$hasMany[$propertyName]['mapper'];
            $objsIds            = [];
            $relReflectionClass = null;

            foreach ($values as $key => &$value) {
                if (!\is_object($value)) {
                    // Is scalar => already in database
                    $objsIds[$key] = $value;

                    continue;
                }

                if ($relReflectionClass === null) {
                    $relReflectionClass = new \ReflectionClass($value);
                }

                $primaryKey = $mapper::getObjectId($value, $relReflectionClass);

                // already in db
                if (!empty($primaryKey)) {
                    $objsIds[$key] = $value;

                    continue;
                }

                // Setting relation value (id) for relation (since the relation is not stored in an extra relation table)
                /**
                 * @todo: this if comparison is correct, trust me. however,
                 * manybe it makes more sense to simply check if 'src' isset(static::$hasMany[$propertyName]['src'])
                 * source shouldn't be set if the relation is stored in the object itself
                 */
                /** @var string $table */
                /** @var array $columns */
                if (static::$hasMany[$propertyName]['table'] === static::$hasMany[$propertyName]['mapper']::$table
                    && isset($mapper::$columns[static::$hasMany[$propertyName]['dst']])
                ) {
                    $relProperty = $relReflectionClass->getProperty($mapper::$columns[static::$hasMany[$propertyName]['dst']]['internal']);

                    if (!$isPublic) {
                        $relProperty->setAccessible(true);
                    }

                    $relProperty->setValue($value, $objId);

                    if (!$isPublic) {
                        $relProperty->setAccessible(false);
                    }
                }

                $objsIds[$key] = $mapper::create($value);
            }

            self::createRelationTable($propertyName, $objsIds, $objId);
        }
    }

    /**
     * Create has many
     *
     * @param array $obj   Object to create
     * @param mixed $objId Id to set
     *
     * @return void
     *
     * @throws InvalidMapperException Throws this exception if the mapper in the has many relation is invalid
     *
     * @since  1.0.0
     */
    private static function createHasManyArray(array &$obj, $objId) : void
    {
        foreach (static::$hasMany as $propertyName => $rel) {
            $values = $obj[$propertyName];

            if (!isset(static::$hasMany[$propertyName]['mapper'])) {
                throw new InvalidMapperException();
            }

            /** @var string $mapper */
            $mapper  = static::$hasMany[$propertyName]['mapper'];
            $objsIds = [];

            foreach ($values as $key => &$value) {
                if (!\is_array($value)) {
                    // Is scalar => already in database
                    $objsIds[$key] = $value;

                    continue;
                }

                $primaryKey = $value[$mapper::$columns[$mapper::$primaryField]['internal']];

                // already in db
                if (!empty($primaryKey)) {
                    $objsIds[$key] = $value;

                    continue;
                }

                // Setting relation value (id) for relation (since the relation is not stored in an extra relation table)
                /** @var string $table */
                /** @var array $columns */
                if (static::$hasMany[$propertyName]['table'] === static::$hasMany[$propertyName]['mapper']::$table
                    && isset($mapper::$columns[static::$hasMany[$propertyName]['dst']])
                ) {
                    $value[$mapper::$columns[static::$hasMany[$propertyName]['dst']]['internal']] = $objId;
                }

                $objsIds[$key] = $mapper::createArray($value);
            }

            self::createRelationTable($propertyName, $objsIds, $objId);
        }
    }

    /**
     * Create owns one
     *
     * The reference is stored in the main model
     *
     * @param string $propertyName Property name to initialize
     * @param mixed  $obj          Object to create
     *
     * @return mixed
     *
     * @since  1.0.0
     */
    private static function createOwnsOne(string $propertyName, $obj)
    {
        if (\is_object($obj)) {
            $mapper     = static::$ownsOne[$propertyName]['mapper'];
            $primaryKey = $mapper::getObjectId($obj);

            if (empty($primaryKey)) {
                return $mapper::create($obj);
            }

            return $primaryKey;
        }

        return $obj;
    }

    /**
     * Create owns one
     *
     * The reference is stored in the main model
     *
     * @param string $propertyName Property name to initialize
     * @param array  $obj          Object to create
     *
     * @return mixed
     *
     * @since  1.0.0
     */
    private static function createOwnsOneArray(string $propertyName, array &$obj)
    {
        if (\is_array($obj)) {
            $mapper     = static::$ownsOne[$propertyName]['mapper'];
            $primaryKey = $obj[static::$columns[static::$primaryField]['internal']];

            if (empty($primaryKey)) {
                return $mapper::createArray($obj);
            }

            return $primaryKey;
        }

        return $obj;
    }

    /**
     * Create owns one
     *
     * The reference is stored in the main model
     *
     * @param string $propertyName Property name to initialize
     * @param mixed  $obj          Object to create
     *
     * @return mixed
     *
     * @since  1.0.0
     */
    private static function createBelongsTo(string $propertyName, $obj)
    {
        if (\is_object($obj)) {
            /** @var string $mapper */
            $mapper     = static::$belongsTo[$propertyName]['mapper'];
            $primaryKey = $mapper::getObjectId($obj);

            if (empty($primaryKey)) {
                return $mapper::create($obj);
            }

            return $primaryKey;
        }

        return $obj;
    }

    /**
     * Create owns one
     *
     * The reference is stored in the main model
     *
     * @param string $propertyName Property name to initialize
     * @param array  $obj          Object to create
     *
     * @return mixed
     *
     * @since  1.0.0
     */
    private static function createBelongsToArray(string $propertyName, array $obj)
    {
        if (\is_array($obj)) {
            /** @var string $mapper */
            $mapper     = static::$belongsTo[$propertyName]['mapper'];
            $primaryKey = $obj[static::$columns[static::$primaryField]['internal']];

            if (empty($primaryKey)) {
                return $mapper::createArray($obj);
            }

            return $primaryKey;
        }

        return $obj;
    }

    /**
     * Create relation table entry
     *
     * In case of a many to many relation the relation has to be stored in a relation table
     *
     * @param string $propertyName Property name to initialize
     * @param array  $objsIds      Object ids to insert
     * @param mixed  $objId        Model to reference
     *
     * @return void
     *
     * @since  1.0.0
     */
    private static function createRelationTable(string $propertyName, array $objsIds, $objId) : void
    {
        /** @todo: see hasMany implementation, checking isset(src) might be enough. although second condition MUST remain. */
        /** @var string $table */
        if (!empty($objsIds)
            && static::$hasMany[$propertyName]['table'] !== static::$table
            && static::$hasMany[$propertyName]['table'] !== static::$hasMany[$propertyName]['mapper']::$table
        ) {
            $relQuery = new Builder(self::$db);
            $relQuery->prefix(self::$db->getPrefix())
                ->into(static::$hasMany[$propertyName]['table'])
                ->insert(static::$hasMany[$propertyName]['src'], static::$hasMany[$propertyName]['dst']);

            foreach ($objsIds as $key => $src) {
                $relQuery->values($src, $objId);
            }

            self::$db->con->prepare($relQuery->toSql())->execute();
        }
    }

    /**
     * Parse value
     *
     * @param string $type  Value type
     * @param mixed  $value Value to parse
     *
     * @return mixed
     *
     * @since  1.0.0
     */
    private static function parseValue(string $type, $value = null)
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
        } elseif ($type === 'DateTime') {
            return $value->format('Y-m-d H:i:s');
        } elseif ($type === 'Json' || $type === 'jsonSerializable') {
            return (string) \json_encode($value);
        } elseif ($type === 'Serializable') {
            return $value->serialize();
        } elseif ($value instanceof \JsonSerializable) {
            return (string) \json_encode($value->jsonSerialize());
        } elseif (\is_object($value) && \method_exists($value, 'getId')) {
            return $value->getId();
        }

        return $value;
    }

    /**
     * Update has many
     *
     * @param \ReflectionClass $refClass  Reflection class
     * @param object           $obj       Object to create
     * @param mixed            $objId     Id to set
     * @param int              $relations Create all relations as well
     * @param int              $depth     Depth of relations to update (default = 1 = none)
     *
     * @return void
     *
     * @throws InvalidMapperException Throws this exception if the mapper in the has many relation is invalid
     *
     * @since  1.0.0
     */
    private static function updateHasMany(\ReflectionClass $refClass, object $obj, $objId, int $relations = RelationType::ALL, $depth = 1) : void
    {
        $objsIds = [];

        foreach (static::$hasMany as $propertyName => $rel) {
            $property = $refClass->getProperty($propertyName);

            if (!($isPublic = $property->isPublic())) {
                $property->setAccessible(true);
            }

            $values = $property->getValue($obj);

            if (!$isPublic) {
                $property->setAccessible(false);
            }

            if (!isset(static::$hasMany[$propertyName]['mapper'])) {
                throw new InvalidMapperException();
            }

            /** @var string $mapper */
            $mapper                 = static::$hasMany[$propertyName]['mapper'];
            $relReflectionClass     = null;
            $objsIds[$propertyName] = [];

            foreach ($values as $key => &$value) {
                if (!\is_object($value)) {
                    // Is scalar => already in database
                    $objsIds[$propertyName][$key] = $value;

                    continue;
                }

                if ($relReflectionClass === null) {
                    $relReflectionClass = new \ReflectionClass($value);
                }

                $primaryKey = $mapper::getObjectId($value, $relReflectionClass);

                // already in db
                if (!empty($primaryKey)) {
                    $mapper::update($value, $relations, $depth);

                    $objsIds[$propertyName][$key] = $value;

                    continue;
                }

                // create if not existing
                /** @var string $table */
                /** @var array $columns */
                if (static::$hasMany[$propertyName]['table'] === static::$hasMany[$propertyName]['mapper']::$table
                    && isset($mapper::$columns[static::$hasMany[$propertyName]['dst']])
                ) {
                    $relProperty = $relReflectionClass->getProperty($mapper::$columns[static::$hasMany[$propertyName]['dst']]['internal']);

                    if (!$isPublic) {
                        $relProperty->setAccessible(true);
                    }

                    $relProperty->setValue($value, $objId);

                    if (!$isPublic) {
                        $relProperty->setAccessible(false);
                    }
                }

                $objsIds[$propertyName][$key] = $mapper::create($value);
            }
        }

        self::updateRelationTable($objsIds, $objId);
    }

    /**
     * Update has many
     *
     * @param array $obj       Object to create
     * @param mixed $objId     Id to set
     * @param int   $relations Create all relations as well
     * @param int   $depth     Depth of relations to update (default = 1 = none)
     *
     * @return void
     *
     * @throws InvalidMapperException Throws this exception if the mapper in the has many relation is invalid
     *
     * @since  1.0.0
     */
    private static function updateHasManyArray(array &$obj, $objId, int $relations = RelationType::ALL, $depth = 1) : void
    {
        $objsIds = [];

        foreach (static::$hasMany as $propertyName => $rel) {
            $values = $obj[$propertyName];

            if (!isset(static::$hasMany[$propertyName]['mapper'])) {
                throw new InvalidMapperException();
            }

            /** @var string $mapper */
            $mapper                 = static::$hasMany[$propertyName]['mapper'];
            $objsIds[$propertyName] = [];

            foreach ($values as $key => &$value) {
                // todo: carefull what if a value is an object or another array?
                if (!\is_array($value)) {
                    // Is scalar => already in database
                    $objsIds[$propertyName][$key] = $value;

                    continue;
                }

                $primaryKey = $value[$mapper::$columns[$mapper::$primaryField]['internal']];

                // already in db
                if (!empty($primaryKey)) {
                    $mapper::updateArray($value, $relations, $depth);

                    $objsIds[$propertyName][$key] = $value;

                    continue;
                }

                // create if not existing
                /** @var string $table */
                /** @var array $columns */
                if (static::$hasMany[$propertyName]['table'] === static::$hasMany[$propertyName]['mapper']::$table
                    && isset($mapper::$columns[static::$hasMany[$propertyName]['dst']])
                ) {
                    $value[$mapper::$columns[static::$hasMany[$propertyName]['dst']]['internal']] = $objId;
                }

                $objsIds[$propertyName][$key] = $mapper::createArray($value);
            }
        }

        self::updateRelationTable($objsIds, $objId);
    }

    /**
     * Update relation table entry
     *
     * Deletes old entries and creates new ones
     *
     * @param array $objsIds Object ids to insert
     * @param mixed $objId   Model to reference
     *
     * @return mixed
     *
     * @since  1.0.0
     */
    private static function updateRelationTable(array $objsIds, $objId)
    {
        $many = self::getHasManyRaw($objId);

        foreach (static::$hasMany as $propertyName => $rel) {
            $removes = \array_diff($many[$propertyName], \array_keys($objsIds[$propertyName] ?? []));
            $adds    = \array_diff(\array_keys($objsIds[$propertyName] ?? []), $many[$propertyName]);

            if (!empty($removes)) {
                self::deleteRelationTable($propertyName, $removes, $objId);
            }

            if (!empty($adds)) {
                self::createRelationTable($propertyName, $adds, $objId);
            }
        }
    }

    /**
     * Delete relation table entry
     *
     * @param string $propertyName Property name to initialize
     * @param array  $objsIds      Object ids to insert
     * @param mixed  $objId        Model to reference
     *
     * @return mixed
     *
     * @since  1.0.0
     */
    private static function deleteRelationTable(string $propertyName, array $objsIds, $objId)
    {
        /** @var string $table */
        if (!empty($objsIds)
            && static::$hasMany[$propertyName]['table'] !== static::$table
            && static::$hasMany[$propertyName]['table'] !== static::$hasMany[$propertyName]['mapper']::$table
        ) {
            foreach ($objsIds as $key => $src) {
                $relQuery = new Builder(self::$db);
                $relQuery->prefix(self::$db->getPrefix())
                    ->delete()
                    ->from(static::$hasMany[$propertyName]['table'])
                    ->where(static::$hasMany[$propertyName]['table'] . '.' . static::$hasMany[$propertyName]['src'], '=', $src)
                    ->where(static::$hasMany[$propertyName]['table'] . '.' . static::$hasMany[$propertyName]['dst'], '=', $objId, 'and');

                self::$db->con->prepare($relQuery->toSql())->execute();
            }
        }
    }

    /**
     * Update owns one
     *
     * The reference is stored in the main model
     *
     * @param string $propertyName Property name to initialize
     * @param object $obj          Object to update
     * @param int    $relations    Create all relations as well
     * @param int    $depth        Depth of relations to update (default = 1 = none)
     *
     * @return mixed
     *
     * @since  1.0.0
     */
    private static function updateOwnsOne(string $propertyName, object $obj, int $relations = RelationType::ALL, int $depth = 1)
    {
        /** @var string $mapper */
        $mapper = static::$ownsOne[$propertyName]['mapper'];

        // todo: delete owned one object is not recommended since it can be owned by by something else? or does owns one mean that nothing else can have a relation to this one?

        return $mapper::update($obj, $relations, $depth);
    }

    /**
     * Update owns one
     *
     * The reference is stored in the main model
     *
     * @param string $propertyName Property name to initialize
     * @param array  $obj          Object to update
     * @param int    $relations    Create all relations as well
     * @param int    $depth        Depth of relations to update (default = 1 = none)
     *
     * @return mixed
     *
     * @since  1.0.0
     */
    private static function updateOwnsOneArray(string $propertyName, array $obj, int $relations = RelationType::ALL, int $depth = 1)
    {
        /** @var string $mapper */
        $mapper = static::$ownsOne[$propertyName]['mapper'];

        // todo: delete owned one object is not recommended since it can be owned by by something else? or does owns one mean that nothing else can have a relation to this one?

        return $mapper::updateArray($obj, $relations, $depth);
    }

    /**
     * Update owns one
     *
     * The reference is stored in the main model
     *
     * @param string $propertyName Property name to initialize
     * @param mixed  $obj          Object to update
     * @param int    $relations    Create all relations as well
     * @param int    $depth        Depth of relations to update (default = 1 = none)
     *
     * @return mixed
     *
     * @since  1.0.0
     */
    private static function updateBelongsTo(string $propertyName, $obj, int $relations = RelationType::ALL, int $depth = 1)
    {
        if (\is_object($obj)) {
            /** @var string $mapper */
            $mapper = static::$belongsTo[$propertyName]['mapper'];

            return $mapper::update($obj, $relations, $depth);
        }

        return $obj;
    }

    /**
     * Update owns one
     *
     * The reference is stored in the main model
     *
     * @param string $propertyName Property name to initialize
     * @param mixed  $obj          Object to update
     * @param int    $relations    Create all relations as well
     * @param int    $depth        Depth of relations to update (default = 1 = none)
     *
     * @return mixed
     *
     * @since  1.0.0
     */
    private static function updateBelongsToArray(string $propertyName, $obj, int $relations = RelationType::ALL, int $depth = 1)
    {
        if (\is_array($obj)) {
            /** @var string $mapper */
            $mapper = static::$belongsTo[$propertyName]['mapper'];

            return $mapper::updateArray($obj, $relations, $depth);
        }

        return $obj;
    }

    /**
     * Update object in db.
     *
     * @param object           $obj       Model to update
     * @param mixed            $objId     Model id
     * @param \ReflectionClass $refClass  Reflection class
     * @param int              $relations Create all relations as well
     * @param int              $depth     Depth of relations to update (default = 1 = none)
     *
     * @return void
     *
     * @since  1.0.0
     */
    private static function updateModel(object $obj, $objId, \ReflectionClass $refClass = null, int $relations = RelationType::ALL, int $depth = 1) : void
    {
        $query = new Builder(self::$db);
        $query->prefix(self::$db->getPrefix())
            ->update(static::$table)
            ->where(static::$table . '.' . static::$primaryField, '=', $objId);

        foreach (static::$columns as $key => $column) {
            $propertyName = \stripos($column['internal'], '/') !== false ? \explode('/', $column['internal'])[0] : $column['internal'];
            if (isset(static::$hasMany[$propertyName])
                || $column['internal'] === static::$primaryField
            ) {
                continue;
            }

            $refClass = $refClass ?? new \ReflectionClass($obj);
            $property = $refClass->getProperty($propertyName);

            if (!($isPublic = $property->isPublic())) {
                $property->setAccessible(true);
            }

            if (isset(static::$ownsOne[$propertyName])) {
                $id    = self::updateOwnsOne($propertyName, $property->getValue($obj), $relations, $depth);
                $value = self::parseValue($column['type'], $id);

                // todo: should not be done if the id didn't change. but for now don't know if id changed
                $query->set([static::$table . '.' . $column['name'] => $value]);
            } elseif (isset(static::$belongsTo[$propertyName])) {
                $id    = self::updateBelongsTo($propertyName, $property->getValue($obj), $relations, $depth);
                $value = self::parseValue($column['type'], $id);

                // todo: should not be done if the id didn't change. but for now don't know if id changed
                $query->set([static::$table . '.' . $column['name'] => $value]);
            } elseif ($column['name'] !== static::$primaryField) {
                $tValue = $property->getValue($obj);
                if (\stripos($column['internal'], '/') !== false) {
                    $path = \explode('/', $column['internal']);

                    \array_shift($path);
                    $path   = \implode('/', $path);
                    $tValue = ArrayUtils::getArray($path, $tValue, '/');
                }
                $value = self::parseValue($column['type'], $tValue);

                $query->set([static::$table . '.' . $column['name'] => $value]);
            }

            if (!$isPublic) {
                $property->setAccessible(false);
            }
        }

        self::$db->con->prepare($query->toSql())->execute();
    }

    /**
     * Update object in db.
     *
     * @param array $obj       Model to update
     * @param mixed $objId     Model id
     * @param int   $relations Create all relations as well
     * @param int   $depth     Depth of relations to update (default = 1 = none)
     *
     * @return void
     *
     * @since  1.0.0
     */
    private static function updateModelArray(array $obj, $objId, int $relations = RelationType::ALL, int $depth = 1) : void
    {
        $query = new Builder(self::$db);
        $query->prefix(self::$db->getPrefix())
            ->update(static::$table)
            ->where(static::$table . '.' . static::$primaryField, '=', $objId);

        foreach (static::$columns as $key => $column) {
            if (isset(static::$hasMany[$key])) {
                continue;
            }

            $path = $column['internal'];
            if (\stripos($column['internal'], '/') !== false) {
                $path = \explode('/', $column['internal']);

                \array_shift($path); // todo: why am I doing this?
                $path = \implode('/', $path);
            }

            $property = ArrayUtils::getArray($path, $obj, '/');

            if (isset(static::$ownsOne[$path])) {
                $id    = self::updateOwnsOneArray($column['internal'], $property, $relations, $depth);
                $value = self::parseValue($column['type'], $id);

                // todo: should not be done if the id didn't change. but for now don't know if id changed
                $query->set([static::$table . '.' . $column['name'] => $value]);
            } elseif (isset(static::$belongsTo[$path])) {
                $id    = self::updateBelongsToArray($column['internal'], $property, $relations, $depth);
                $value = self::parseValue($column['type'], $id);

                // todo: should not be done if the id didn't change. but for now don't know if id changed
                $query->set([static::$table . '.' . $column['name'] => $value]);
            } elseif ($column['name'] !== static::$primaryField) {
                $value = self::parseValue($column['type'], $property);

                $query->set([static::$table . '.' . $column['name'] => $value]);
            }
        }

        self::$db->con->prepare($query->toSql())->execute();
    }

    /**
     * Update object in db.
     *
     * @param mixed $obj       Object reference (gets filled with insert id)
     * @param int   $relations Create all relations as well
     * @param int   $depth     Depth of relations to update (default = 1 = none)
     *
     * @return mixed
     *
     * @since  1.0.0
     */
    public static function update($obj, int $relations = RelationType::ALL, int $depth = 1)
    {
        self::extend(__CLASS__);

        if (!isset($obj) || self::isNullObject($obj)) {
            return null;
        }

        $refClass = new \ReflectionClass($obj);
        $objId    = self::getObjectId($obj, $refClass);
        $update   = true;

        if ($depth < 1) {
            return $objId;
        }

        self::addInitialized(static::class, $objId, $obj);

        if ($relations === RelationType::ALL) {
            self::updateHasMany($refClass, $obj, $objId, --$depth);
        }

        if (empty($objId)) {
            return self::create($obj, $relations);
        }

        self::updateModel($obj, $objId, $refClass, --$depth);

        return $objId;
    }

    /**
     * Update object in db.
     *
     * @param array $obj       Object reference (gets filled with insert id)
     * @param int   $relations Create all relations as well
     * @param int   $depth     Depth of relations to update (default = 1 = none)
     *
     * @return mixed
     *
     * @since  1.0.0
     */
    public static function updateArray(array &$obj, int $relations = RelationType::ALL, int $depth = 1)
    {
        self::extend(__CLASS__);

        if (empty($obj)) {
            return null;
        }

        $objId  = $obj[static::$columns[static::$primaryField]['internal']];
        $update = true;

        if ($depth < 1) {
            return $objId;
        }

        self::addInitializedArray(static::class, $objId, $obj);

        if (empty($objId)) {
            $update = false;
            self::createArray($obj, $relations);
        }

        if ($relations === RelationType::ALL) {
            self::updateHasManyArray($obj, $objId, --$depth);
        }

        if ($update) {
            self::updateModelArray($obj, $objId, --$depth);
        }

        return $objId;
    }

    /**
     * Delete has many
     *
     * @param \ReflectionClass $refClass  Reflection class
     * @param object           $obj       Object to create
     * @param mixed            $objId     Id to set
     * @param int              $relations Delete all relations as well
     *
     * @return void
     *
     * @throws InvalidMapperException Throws this exception if the mapper in the has many relation is invalid
     *
     * @since  1.0.0
     */
    private static function deleteHasMany(\ReflectionClass $refClass, object $obj, $objId, int $relations) : void
    {
        foreach (static::$hasMany as $propertyName => $rel) {
            $property = $refClass->getProperty($propertyName);

            if (!($isPublic = $property->isPublic())) {
                $property->setAccessible(true);
            }

            $values = $property->getValue($obj);

            if (!$isPublic) {
                $property->setAccessible(false);
            }

            if (!isset(static::$hasMany[$propertyName]['mapper'])) {
                throw new InvalidMapperException();
            }

            /** @var string $mapper */
            $mapper             = static::$hasMany[$propertyName]['mapper'];
            $objsIds            = [];
            $relReflectionClass = null;

            foreach ($values as $key => &$value) {
                if (!\is_object($value)) {
                    // Is scalar => already in database
                    $objsIds[$key] = $value;

                    continue;
                }

                if ($relReflectionClass === null) {
                    $relReflectionClass = new \ReflectionClass($value);
                }

                $primaryKey = $mapper::getObjectId($value, $relReflectionClass);

                // already in db
                if (!empty($primaryKey)) {
                    if ($relations === RelationType::ALL) {
                        $objsIds[$key] = $mapper::delete($value);
                    } else {
                        $objsIds[$key] = $primaryKey;
                    }

                    continue;
                }

                // todo: could be a problem, relation needs to be removed first?!

            }

            self::deleteRelationTable($propertyName, $objsIds, $objId);
        }
    }

    /**
     * Delete owns one
     *
     * The reference is stored in the main model
     *
     * @param string $propertyName Property name to initialize
     * @param mixed  $obj          Object to delete
     *
     * @return mixed
     *
     * @since  1.0.0
     */
    private static function deleteOwnsOne(string $propertyName, $obj)
    {
        if (\is_object($obj)) {
            /** @var string $mapper */
            $mapper = static::$ownsOne[$propertyName]['mapper'];

            // todo: delete owned one object is not recommended since it can be owned by by something else? or does owns one mean that nothing else can have a relation to this one?
            return $mapper::delete($obj);
        }

        return $obj;
    }

    /**
     * Delete owns one
     *
     * The reference is stored in the main model
     *
     * @param string $propertyName Property name to initialize
     * @param mixed  $obj          Object to delete
     *
     * @return mixed
     *
     * @since  1.0.0
     */
    private static function deleteBelongsTo(string $propertyName, $obj)
    {
        if (\is_object($obj)) {
            /** @var string $mapper */
            $mapper = static::$belongsTo[$propertyName]['mapper'];

            return $mapper::delete($obj);
        }

        return $obj;
    }

    /**
     * Delete object in db.
     *
     * @param object           $obj       Model to delete
     * @param mixed            $objId     Model id
     * @param int              $relations Delete all relations as well
     * @param \ReflectionClass $refClass  Reflection class
     *
     * @return void
     *
     * @since  1.0.0
     */
    private static function deleteModel(object $obj, $objId, int $relations = RelationType::REFERENCE, \ReflectionClass $refClass = null) : void
    {
        $query = new Builder(self::$db);
        $query->prefix(self::$db->getPrefix())
            ->delete()
            ->from(static::$table)
            ->where(static::$table . '.' . static::$primaryField, '=', $objId);

        $refClass   = $refClass ?? new \ReflectionClass($obj);
        $properties = $refClass->getProperties();

        if ($relations === RelationType::ALL) {
            foreach ($properties as $property) {
                $propertyName = $property->getName();

                if (isset(static::$hasMany[$propertyName])) {
                    continue;
                }

                if (!($isPublic = $property->isPublic())) {
                    $property->setAccessible(true);
                }

                // todo: the order of deletion could be a problem. maybe looping through ownsOne and belongsTo first is better.
                // todo: support other relation types as well (belongsto, ownsone) = for better control

                foreach (static::$columns as $key => $column) {
                    if ($relations === RelationType::ALL && isset(static::$ownsOne[$propertyName]) && $column['internal'] === $propertyName) {
                        self::deleteOwnsOne($propertyName, $property->getValue($obj));
                        break;
                    } elseif ($relations === RelationType::ALL && isset(static::$belongsTo[$propertyName]) && $column['internal'] === $propertyName) {
                        self::deleteBelongsTo($propertyName, $property->getValue($obj));
                        break;
                    }
                }

                if (!$isPublic) {
                    $property->setAccessible(false);
                }
            }
        }

        self::$db->con->prepare($query->toSql())->execute();
    }

    /**
     * Delete object in db.
     *
     * @param mixed $obj       Object reference (gets filled with insert id)
     * @param int   $relations Create all relations as well
     *
     * @return mixed
     *
     * @since  1.0.0
     */
    public static function delete($obj, int $relations = RelationType::REFERENCE)
    {
        self::extend(__CLASS__);

        if (\is_scalar($obj)) {
            $obj = static::get($obj);
        }

        $refClass = new \ReflectionClass($obj);
        $objId    = self::getObjectId($obj, $refClass);

        if (empty($objId)) {
            return null;
        }

        self::removeInitialized(static::class, $objId);

        if ($relations !== RelationType::NONE) {
            self::deleteHasMany($refClass, $obj, $objId, $relations);
        }

        self::deleteModel($obj, $objId, $relations, $refClass);

        return $objId;
    }

    /**
     * Populate data.
     *
     * @param array $result Result set
     *
     * @return array
     *
     * @since  1.0.0
     */
    public static function populateIterable(array $result) : array
    {
        $row = [];

        foreach ($result as $element) {
            if (isset($element[static::$primaryField])) {
                $row[$element[static::$primaryField]] = self::populate($element);
            } else {
                $row[] = self::populate($element);
            }
        }

        return $row;
    }

    /**
     * Populate data.
     *
     * @param array $result Result set
     *
     * @return array
     *
     * @since  1.0.0
     */
    public static function populateIterableArray(array $result) : array
    {
        $row = [];

        foreach ($result as $element) {
            if (isset($element[static::$primaryField])) {
                $row[$element[static::$primaryField]] = self::populateAbstractArray($element);
            } else {
                $row[] = self::populateAbstractArray($element);
            }
        }

        return $row;
    }

    /**
     * Populate data.
     *
     * @param array $result Result set
     * @param mixed $obj    Object to populate
     *
     * @return mixed
     *
     * @since  1.0.0
     */
    public static function populate(array $result, $obj = null)
    {
        $class = static::class;
        $class = \str_replace('Mapper', '', $class);

        if (empty($result)) {
            $parts     = \explode('\\', $class);
            $name      = $parts[$c = (\count($parts) - 1)];
            $parts[$c] = 'Null' . $name;
            $class     = \implode('\\', $parts);
        }

        if (!isset($obj)) {
            // todo: implement solution for classes with constructor arguments
            // maybe implement a factory pattern for every datamapper model
            $obj = new $class();
        }

        return self::populateAbstract($result, $obj);
    }

    /**
     * Populate data.
     *
     * @param array[] $result Result set
     * @param mixed   $obj    Object to add the relations to
     * @param int     $depth  Relation depth
     *
     * @return void
     *
     * @since  1.0.0
     */
    public static function populateManyToMany(array $result, &$obj, int $depth = 3) : void
    {
        // todo: maybe pass reflectionClass as optional parameter for performance increase
        $refClass = new \ReflectionClass($obj);

        foreach ($result as $member => $values) {
            if (!empty($values) && $refClass->hasProperty($member)) {
                /** @var string $mapper */
                $mapper  = static::$hasMany[$member]['mapper'];
                $refProp = $refClass->getProperty($member);

                if (!($accessible = $refProp->isPublic())) {
                    $refProp->setAccessible(true);
                }

                $objects = $mapper::get($values, RelationType::ALL, null, $depth);
                $refProp->setValue($obj, !\is_array($objects) ? [$objects->getId() => $objects] : $objects);

                if (!$accessible) {
                    $refProp->setAccessible(false);
                }
            }
        }
    }

    /**
     * Populate data.
     *
     * @param array[] $result Result set
     * @param array   $obj    Object to add the relations to
     * @param int     $depth  Relation depth
     *
     * @return void
     *
     * @since  1.0.0
     */
    public static function populateManyToManyArray(array $result, array &$obj, int $depth = 3) : void
    {
        foreach ($result as $member => $values) {
            if (!empty($values)) {
                /** @var string $mapper */
                $mapper = static::$hasMany[$member]['mapper'];

                $objects      = $mapper::getArray($values, RelationType::ALL, $depth);
                $obj[$member] = $objects;
            }
        }
    }

    /**
     * Populate data.
     *
     * @param mixed $obj   Object to add the relations to
     * @param int   $depth Relation depth
     *
     * @return void
     *
     * @todo   accept reflection class as parameter
     *
     * @since  1.0.0
     */
    public static function populateOwnsOne(&$obj, int $depth = 3) : void
    {
        $refClass = new \ReflectionClass($obj);

        foreach (static::$ownsOne as $member => $one) {
            // todo: is that if necessary? performance is suffering for sure!
            if ($refClass->hasProperty($member)) {
                $refProp = $refClass->getProperty($member);

                if (!($accessible = $refProp->isPublic())) {
                    $refProp->setAccessible(true);
                }

                /** @var string $mapper */
                $mapper = static::$ownsOne[$member]['mapper'];
                $id     = $refProp->getValue($obj);

                if (self::isNullObject($id)) {
                    continue;
                }

                $id    = \is_object($id) ? self::getObjectId($id) : $id;
                $value = self::getInitialized($mapper, $id) ?? $mapper::get($id, RelationType::ALL, null, $depth);

                $refProp->setValue($obj, $value);

                if (!$accessible) {
                    $refProp->setAccessible(false);
                }
            }
        }
    }

    /**
     * Populate data.
     *
     * @param array $obj   Object to add the relations to
     * @param int   $depth Relation depth
     *
     * @return void
     *
     * @todo   accept reflection class as parameter
     *
     * @since  1.0.0
     */
    public static function populateOwnsOneArray(array &$obj, int $depth = 3) : void
    {
        foreach (static::$ownsOne as $member => $one) {
            /** @var string $mapper */
            $mapper = static::$ownsOne[$member]['mapper'];
            $id     = $obj[$member];
            $id     = \is_array($id) ? $id[$mapper::$columns[$mapper::$primaryField]['internal']] : $id;

            $obj[$member] = self::getInitializedArray($mapper, $id) ?? $mapper::getArray($id, RelationType::ALL, $depth);
        }
    }

    /**
     * Populate data.
     *
     * @param mixed $obj   Object to add the relations to
     * @param int   $depth Relation depth
     *
     * @return void
     *
     * @todo   accept reflection class as parameter
     *
     * @since  1.0.0
     */
    public static function populateBelongsTo(&$obj, int $depth = 3) : void
    {
        $refClass = new \ReflectionClass($obj);

        foreach (static::$belongsTo as $member => $one) {
            // todo: is that if necessary? performance is suffering for sure!
            if ($refClass->hasProperty($member)) {
                $refProp = $refClass->getProperty($member);

                if (!($accessible = $refProp->isPublic())) {
                    $refProp->setAccessible(true);
                }

                /** @var string $mapper */
                $mapper = static::$belongsTo[$member]['mapper'];
                $id     = $refProp->getValue($obj);

                if (self::isNullObject($id)) {
                    continue;
                }

                $id    = \is_object($id) ? self::getObjectId($id) : $id;
                $value = self::getInitialized($mapper, $id) ?? $mapper::get($id, RelationType::ALL, null, $depth);

                $refProp->setValue($obj, $value);

                if (!$accessible) {
                    $refProp->setAccessible(false);
                }
            }
        }
    }

    /**
     * Populate data.
     *
     * @param array $obj   Object to add the relations to
     * @param int   $depth Relation depth
     *
     * @return void
     *
     * @todo   accept reflection class as parameter
     *
     * @since  1.0.0
     */
    public static function populateBelongsToArray(array &$obj, int $depth = 3) : void
    {
        foreach (static::$belongsTo as $member => $one) {
            /** @var string $mapper */
            $mapper = static::$belongsTo[$member]['mapper'];
            $id     = $obj[$member];
            $id     = \is_array($id) ? $id[$mapper::$columns[$mapper::$primaryField]['internal']] : $id;

            $obj[$member] = self::getInitializedArray($mapper, $id) ?? $mapper::getArray($id, RelationType::ALL, $depth);
        }
    }

    /**
     * Populate data.
     *
     * @param array $result Query result set
     * @param mixed $obj    Object to populate
     *
     * @return mixed
     *
     * @throws \UnexpectedValueException
     *
     * @since  1.0.0
     */
    public static function populateAbstract(array $result, $obj)
    {
        $refClass = new \ReflectionClass($obj);

        foreach ($result as $column => $value) {
            if (!isset(static::$columns[$column]['internal']) /* && $refClass->hasProperty(static::$columns[$column]['internal']) */) {
                continue;
            }

            $hasPath   = false;
            $aValue    = [];
            $arrayPath = '';

            if (\stripos(static::$columns[$column]['internal'], '/') !== false) {
                $hasPath = true;
                $path    = \explode('/', static::$columns[$column]['internal']);
                $refProp = $refClass->getProperty($path[0]);

                if (!($accessible = $refProp->isPublic())) {
                    $refProp->setAccessible(true);
                }

                \array_shift($path);
                $arrayPath = \implode('/', $path);
                $aValue    = $refProp->getValue($obj);
            } else {
                $refProp = $refClass->getProperty(static::$columns[$column]['internal']);

                if (!($accessible = $refProp->isPublic())) {
                    $refProp->setAccessible(true);
                }
            }

            if (\in_array(static::$columns[$column]['type'], ['string', 'int', 'float', 'bool'])) {
                // todo: what is this or condition for? seems to be wrong if obj null then it doesn't work anyways
                if ($value !== null || $refProp->getValue($obj) !== null) {
                    \settype($value, static::$columns[$column]['type']);
                }

                if ($hasPath) {
                    $value = ArrayUtils::setArray($arrayPath, $aValue, $value, '/', true);
                }

                $refProp->setValue($obj, $value);
            } elseif (static::$columns[$column]['type'] === 'DateTime') {
                $value = new \DateTime($value ?? '');
                if ($hasPath) {
                    $value = ArrayUtils::setArray($arrayPath, $aValue, $value, '/', true);
                }

                $refProp->setValue($obj, $value);
            } elseif (static::$columns[$column]['type'] === 'Json') {
                if ($hasPath) {
                    $value = ArrayUtils::setArray($arrayPath, $aValue, $value, '/', true);
                }

                $refProp->setValue($obj, \json_decode($value, true));
            } elseif (static::$columns[$column]['type'] === 'Serializable') {
                $member = $refProp->getValue($obj);
                $member->unserialize($value);
            } else {
                throw new \UnexpectedValueException('Value "' . static::$columns[$column]['type'] . '" is not supported.');
            }

            if (!$accessible) {
                $refProp->setAccessible(false);
            }
        }

        return $obj;
    }

    /**
     * Populate data.
     *
     * @param array $result Query result set
     *
     * @return array
     *
     * @since  1.0.0
     */
    public static function populateAbstractArray(array $result) : array
    {
        $obj = [];
        foreach ($result as $column => $value) {
            if (isset(static::$columns[$column]['internal'])) {
                $path = static::$columns[$column]['internal'];
                if (\stripos($path, '/') !== false) {
                    $path = \explode('/', $path);

                    \array_shift($path);
                    $path = \implode('/', $path);
                }

                if (\in_array(static::$columns[$column]['type'], ['string', 'int', 'float', 'bool'])) {
                    \settype($value, static::$columns[$column]['type']);
                } elseif (static::$columns[$column]['type'] === 'DateTime') {
                    $value = new \DateTime($value ?? '');
                } elseif (static::$columns[$column]['type'] === 'Json') {
                    $value = \json_decode($value, true);
                }

                $obj = ArrayUtils::setArray($path, $obj, $value, '/', true);
            }
        }

        return $obj;
    }

    /**
     * Get object.
     *
     * @param mixed $primaryKey Key
     * @param int   $relations  Load relations
     * @param mixed $fill       Object to fill
     * @param int   $depth      Relation depth
     *
     * @return mixed
     *
     * @todo: implement language
     *
     * @since  1.0.0
     */
    public static function get($primaryKey, int $relations = RelationType::ALL, $fill = null, int $depth = 3)
    {
        if ($depth < 1) {
            return $primaryKey;
        }

        if (!isset(self::$parentMapper)) {
            self::$parentMapper = static::class;
        }

        self::extend(__CLASS__);

        $primaryKey = (array) $primaryKey;
        $fill       = (array) $fill;
        $obj        = [];
        $fCount     = \count($fill);
        $toFill     = null;

        foreach ($primaryKey as $key => $value) {
            if (self::isInitialized(static::class, $value)) {
                $obj[$value] = self::$initObjects[static::class][$value];
                continue;
            }

            if ($fCount > 0) {
                $toFill = \current($fill);
                \next($fill);
            }

            $obj[$value] = self::populate(self::getRaw($value), $toFill);

            if (\method_exists($obj[$value], 'initialize')) {
                $obj[$value]->initialize();
            }

            self::addInitialized(static::class, $value, $obj[$value]);
        }

        self::fillRelations($obj, $relations, --$depth);
        self::clear();

        $countResulsts = \count($obj);

        if ($countResulsts === 0) {
            return self::getNullModelObj();
        } elseif ($countResulsts === 1) {
            return \reset($obj);
        }

        return $obj;
    }

    /**
     * Creates the current null object
     *
     * @return mixed
     *
     * @since  1.0.0
     */
    private static function getNullModelObj()
    {
        $class     = static::class;
        $class     = \str_replace('Mapper', '', $class);
        $parts     = \explode('\\', $class);
        $name      = $parts[$c = (\count($parts) - 1)];
        $parts[$c] = 'Null' . $name;
        $class     = \implode('\\', $parts);

        return new $class();
    }

    /**
     * Get object.
     *
     * @param mixed $primaryKey Key
     * @param int   $relations  Load relations
     * @param int   $depth      Relation depth
     *
     * @return array
     *
     * @since  1.0.0
     */
    public static function getArray($primaryKey, int $relations = RelationType::ALL, int $depth = 3) : array
    {
        if ($depth < 1) {
            return $primaryKey;
        }

        if (!isset(self::$parentMapper)) {
            self::$parentMapper = static::class;
        }

        self::extend(__CLASS__);

        $primaryKey = (array) $primaryKey;
        $obj        = [];

        foreach ($primaryKey as $key => $value) {
            if (self::isInitializedArray(static::class, $value)) {
                $obj[$value] = self::$initArrays[static::class][$value];
                continue;
            }

            $obj[$value] = self::populateAbstractArray(self::getRaw($value));

            self::addInitializedArray(static::class, $value, $obj[$value]);
        }

        self::fillRelationsArray($obj, $relations, --$depth);
        self::clear();

        return \count($obj) === 1 ? \reset($obj) : $obj;
    }

    /**
     * Get object.
     *
     * @param mixed  $refKey    Key
     * @param string $ref       The field that defines the for
     * @param int    $relations Load relations
     * @param mixed  $fill      Object to fill
     * @param int    $depth     Relation depth
     *
     * @return mixed
     *
     * @since  1.0.0
     */
    public static function getFor($refKey, string $ref, int $relations = RelationType::ALL, $fill = null, int $depth = 3)
    {
        if ($depth < 1) {
            return $refKey;
        }

        if (!isset(self::$parentMapper)) {
            self::$parentMapper = static::class;
        }

        self::extend(__CLASS__);

        $refKey = (array) $refKey;
        $obj    = [];

        foreach ($refKey as $key => $value) {
            $toLoad = [];

            if (isset(static::$hasMany[$ref]) && static::$hasMany[$ref]['src'] !== null) {
                $toLoad = self::getHasManyPrimaryKeys($value, $ref);
            } else {
                $toLoad = self::getPrimaryKeysBy($value, self::getColumnByMember($ref));
            }

            $obj[$value] = self::get($toLoad, $relations, $fill, $depth);
        }

        $countResulsts = \count($obj);

        if ($countResulsts === 0) {
            return self::getNullModelObj();
        } elseif ($countResulsts === 1) {
            return \reset($obj);
        }

        return $obj;
    }

    /**
     * Get object.
     *
     * @param mixed  $refKey    Key
     * @param string $ref       The field that defines the for
     * @param int    $relations Load relations
     * @param int    $depth     Relation depth
     *
     * @return mixed
     *
     * @since  1.0.0
     */
    public static function getForArray($refKey, string $ref, int $relations = RelationType::ALL, int $depth = 3)
    {
        if (!isset(self::$parentMapper)) {
            self::$parentMapper = static::class;
        }

        self::extend(__CLASS__);

        $refKey = (array) $refKey;
        $obj    = [];

        foreach ($refKey as $key => $value) {
            $toLoad = [];

            if (isset(static::$hasMany[$ref]) && static::$hasMany[$ref]['src'] !== null) {
                $toLoad = self::getHasManyPrimaryKeys($value, $ref);
            } else {
                $toLoad = self::getPrimaryKeysBy($value, self::getColumnByMember($ref));
            }

            $obj[$value] = self::getArray($toLoad, $relations, $depth);
        }

        return \count($obj) === 1 ? \reset($obj) : $obj;
    }

    /**
     * Get object.
     *
     * @param int    $relations Load relations
     * @param int    $depth     Relation depth
     * @param string $lang      Language
     *
     * @return array
     *
     * @since  1.0.0
     */
    public static function getAll(int $relations = RelationType::ALL, int $depth = 3, string $lang = '') : array
    {
        if ($depth < 1) {
            return [];
        }

        if (!isset(self::$parentMapper)) {
            self::$parentMapper = static::class;
        }

        $obj = self::populateIterable(self::getAllRaw($lang));
        self::fillRelations($obj, $relations, --$depth);
        self::clear();

        return $obj;
    }

    /**
     * Get object.
     *
     * @param int    $relations Load relations
     * @param int    $depth     Relation depth
     * @param string $lang      Language
     *
     * @return array
     *
     * @since  1.0.0
     */
    public static function getAllArray(int $relations = RelationType::ALL, int $depth = 3, string $lang = '') : array
    {
        if ($depth < 1) {
            return [];
        }

        if (!isset(self::$parentMapper)) {
            self::$parentMapper = static::class;
        }

        $obj = self::populateIterableArray(self::getAllRaw($lang));
        self::fillRelationsArray($obj, $relations, --$depth);
        self::clear();

        return $obj;
    }

    /**
     * Find data.
     *
     * @param Builder $query Query
     *
     * @return array
     *
     * @since  1.0.0
     */
    public static function listResults(Builder $query) : array
    {
        $sth = self::$db->con->prepare($query->toSql());
        $sth->execute();

        $result = $sth->fetchAll(\PDO::FETCH_ASSOC);

        if ($result === false) {
            return [];
        }

        return self::populateIterable($result);
    }

    /**
     * Get newest.
     *
     * This will fall back to the insert id if no datetime column is present.
     *
     * @param int     $limit     Newest limit
     * @param Builder $query     Pre-defined query
     * @param int     $relations Load relations
     * @param int     $depth     Relation depth
     * @param string  $lang      Language
     *
     * @return array
     *
     * @since  1.0.0
     */
    public static function getNewest(int $limit = 1, Builder $query = null, int $relations = RelationType::ALL, int $depth = 3, string $lang = '') : array
    {
        if ($depth < 1) {
            return [];
        }

        self::extend(__CLASS__);

        $query = $query ?? new Builder(self::$db);
        $query = self::getQuery($query);
        $query->limit($limit);

        if (!empty(static::$createdAt)) {
            $query->orderBy(static::$table . '.' . static::$columns[static::$createdAt]['name'], 'DESC');
        } else {
            $query->orderBy(static::$table . '.' . static::$columns[static::$primaryField]['name'], 'DESC');
        }

        if (!empty(self::$languageField) && !empty($lang)) {
            $query->where(static::$table . '.' . static::$languageField, '=', $lang, 'AND');
        }

        $sth = self::$db->con->prepare($query->toSql());
        $sth->execute();

        $results = $sth->fetchAll(\PDO::FETCH_ASSOC);
        $obj     = self::populateIterable($results === false ? [] : $results);

        self::fillRelations($obj, $relations, --$depth);
        self::clear();

        return $obj;
    }

    /**
     * Get all by custom query.
     *
     * @param Builder $query     Query
     * @param int     $relations Relations
     * @param int     $depth     Relation depth
     *
     * @return array
     *
     * @since  1.0.0
     */
    public static function getAllByQuery(Builder $query, int $relations = RelationType::ALL, int $depth = 3) : array
    {
        if ($depth < 1) {
            return [];
        }

        $sth = self::$db->con->prepare($query->toSql());
        $sth->execute();

        $results = $sth->fetchAll(\PDO::FETCH_ASSOC);
        $results = $results === false ? [] : $results;

        $obj = self::populateIterable($results);
        self::fillRelations($obj, $relations, --$depth);
        self::clear();

        return $obj;
    }

    /**
     * Get random object
     *
     * @param int $amount    Amount of random models
     * @param int $relations Relations type
     * @param int $depth     Relation depth
     *
     * @return mixed
     *
     * @since  1.0.0
     */
    public static function getRandom(int $amount = 1, int $relations = RelationType::ALL, int $depth = 3)
    {
        if ($depth < 1) {
            return null;
        }

        $query = new Builder(self::$db);
        $query->prefix(self::$db->getPrefix())
            ->random(static::$primaryField)
            ->from(static::$table)
            ->limit($amount);

        $sth = self::$db->con->prepare($query->toSql());
        $sth->execute();

        return self::get($sth->fetchAll(), $relations, null, $depth);
    }

    /**
     * Fill object with relations
     *
     * @param array $obj       Objects to fill
     * @param int   $relations Relations type
     * @param int   $depth     Relation depth
     *
     * @return void
     *
     * @since  1.0.0
     */
    public static function fillRelations(array &$obj, int $relations = RelationType::ALL, int $depth = 3) : void
    {
        if ($depth < 1) {
            return;
        }

        if ($relations === RelationType::NONE) {
            return;
        }

        $hasMany   = !empty(static::$hasMany);
        $ownsOne   = !empty(static::$ownsOne);
        $belongsTo = !empty(static::$belongsTo);

        if (!($hasMany || $ownsOne || $belongsTo)) {
            return;
        }

        foreach ($obj as $key => $value) {
            /* loading relations from relations table and populating them and then adding them to the object */
            if ($hasMany) {
                self::populateManyToMany(self::getHasManyRaw($key, $relations), $obj[$key], $depth);
            }

            if ($ownsOne) {
                self::populateOwnsOne($obj[$key], $depth);
            }

            if ($belongsTo) {
                self::populateBelongsTo($obj[$key], $depth);
            }
        }
    }

    /**
     * Fill object with relations
     *
     * @param array $obj       Objects to fill
     * @param int   $relations Relations type
     * @param int   $depth     Relation depth
     *
     * @return void
     *
     * @since  1.0.0
     */
    public static function fillRelationsArray(array &$obj, int $relations = RelationType::ALL, int $depth = 3) : void
    {
        if ($depth < 1) {
            return;
        }

        if ($relations === RelationType::NONE) {
            return;
        }

        $hasMany   = !empty(static::$hasMany);
        $ownsOne   = !empty(static::$ownsOne);
        $belongsTo = !empty(static::$belongsTo);

        if (!($hasMany || $ownsOne || $belongsTo)) {
            return;
        }

        foreach ($obj as $key => $value) {
            /* loading relations from relations table and populating them and then adding them to the object */
            if ($hasMany) {
                self::populateManyToManyArray(self::getHasManyRaw($key, $relations), $obj[$key], $depth);
            }

            if ($ownsOne) {
                self::populateOwnsOneArray($obj[$key], $depth);
            }

            if ($belongsTo) {
                self::populateBelongsToArray($obj[$key], $depth);
            }
        }
    }

    /**
     * Get object.
     *
     * @param mixed $primaryKey Key
     *
     * @return array
     *
     * @since  1.0.0
     */
    public static function getRaw($primaryKey) : array
    {
        $query = self::getQuery();
        $query->where(static::$table . '.' . static::$primaryField, '=', $primaryKey);

        $sth = self::$db->con->prepare($query->toSql());
        $sth->execute();

        $results = $sth->fetch(\PDO::FETCH_ASSOC);

        return $results === false ? [] : $results;
    }

    /**
     * Get object.
     *
     * @param mixed  $refKey Key
     * @param string $ref    Ref
     *
     * @return array
     *
     * @since  1.0.0
     */
    public static function getPrimaryKeysBy($refKey, string $ref) : array
    {
        $query = new Builder(self::$db);
        $query->prefix(self::$db->getPrefix())
            ->select(static::$table . '.' . static::$primaryField)
            ->from(static::$table)
            ->where(static::$table . '.' . $ref, '=', $refKey);

        $sth = self::$db->con->prepare($query->toSql());
        $sth->execute();

        $result = $sth->fetchAll(\PDO::FETCH_NUM);
        if ($result === false) {
            return [];
        }

        return \array_column($result, 0);
    }

    /**
     * Get object.
     *
     * @param mixed  $refKey Key
     * @param string $ref    Ref
     *
     * @return array
     *
     * @since  1.0.0
     */
    public static function getHasManyPrimaryKeys($refKey, string $ref) : array
    {
        $query = new Builder(self::$db);
        $query->prefix(self::$db->getPrefix())
            ->select(static::$hasMany[$ref]['table'] . '.' . static::$hasMany[$ref]['dst'])
            ->from(static::$hasMany[$ref]['table'])
            ->where(static::$hasMany[$ref]['table'] . '.' . static::$hasMany[$ref]['src'], '=', $refKey);

        $sth = self::$db->con->prepare($query->toSql());
        $sth->execute();

        $result = $sth->fetchAll(\PDO::FETCH_NUM);
        if ($result === false) {
            return [];
        }

        return \array_column($result, 0);
    }

    /**
     * Get all in raw output.
     *
     * @param string $lang Language
     *
     * @return array
     *
     * @since  1.0.0
     */
    public static function getAllRaw(string $lang = '') : array
    {
        $query = self::getQuery();

        if (!empty(self::$languageField) && !empty($lang)) {
            $query->where(static::$table . '.' . static::$languageField, '=', $lang, 'AND');
        }

        $sth = self::$db->con->prepare($query->toSql());
        $sth->execute();

        $results = $sth->fetchAll(\PDO::FETCH_ASSOC);

        return $results === false ? [] : $results;
    }

    /**
     * Get raw by primary key
     *
     * @param mixed $primaryKey Primary key
     * @param int   $relations  Load relations
     *
     * @return array
     *
     * @since  1.0.0
     */
    public static function getHasManyRaw($primaryKey, int $relations = RelationType::ALL) : array
    {
        $result = [];

        foreach (static::$hasMany as $member => $value) {
            $query = new Builder(self::$db);
            $query->prefix(self::$db->getPrefix());

            if ($relations === RelationType::ALL) {
                /** @var string $primaryField */
                $src = $value['src'] ?? $value['mapper']::$primaryField;

                $query->select($value['table'] . '.' . $src)
                    ->from($value['table'])
                    ->where($value['table'] . '.' . $value['dst'], '=', $primaryKey);
            } /*elseif ($relations === RelationType::NEWEST) {
                SELECT c.*, p1.*
                FROM customer c
                JOIN purchase p1 ON (c.id = p1.customer_id)
                LEFT OUTER JOIN purchase p2 ON (c.id = p2.customer_id AND
                    (p1.date < p2.date OR p1.date = p2.date AND p1.id < p2.id))
                WHERE p2.id IS NULL;
                                    $query->select(static::$table . '.' . static::$primaryField, $value['table'] . '.' . $value['src'])
                                          ->from(static::$table)
                                          ->join($value['table'])
                                          ->on(static::$table . '.' . static::$primaryField, '=', $value['table'] . '.' . $value['dst'])
                                          ->leftOuterJoin($value['table'])
                                          ->on(new And('1', new And(new Or('d1', 'd2'), 'id')))
                                          ->where($value['table'] . '.' . $value['dst'], '=', 'NULL');

            }*/

            $sth = self::$db->con->prepare($query->toSql());
            $sth->execute();
            $result[$member] = $sth->fetchAll(\PDO::FETCH_COLUMN);
        }

        return $result;
    }

    /**
     * Get mapper specific builder
     *
     * @param Builder $query Query to fill
     *
     * @return Builder
     *
     * @since  1.0.0
     */
    public static function getQuery(Builder $query = null) : Builder
    {
        $query = $query ?? new Builder(self::$db);
        $query->prefix(self::$db->getPrefix())
            ->select('*')
            ->from(static::$table);

        return $query;
    }

    /**
     * Get created at column
     *
     * @return string
     *
     * @since  1.0.0
     */
    public static function getCreatedAt() : string
    {
        return !empty(static::$createdAt) ? static::$createdAt : static::$primaryField;
    }

    /**
     * Get model based on request object
     *
     * @todo: change to graphql
     *
     * @param RequestAbstract $request Request object
     *
     * @return mixed
     *
     * @since  1.0.0
     */
    public static function getByRequest(RequestAbstract $request)
    {
        if ($request->getData('id') !== null) {
            $result = static::get((int) $request->getData('id'));
        } elseif (($filter = ((string) $request->getData('filter'))) !== null) {
            $filter = \strtolower($filter);

            if ($filter === 'all') {
                $result = static::getAll();
            } elseif ($filter === 'list') {
                $list   = $request->getData('list');
                $result = static::get(\json_decode($list, true));
            } else {
                $limit = (int) ($request->getData('limit') ?? 1);
                $from  = $request->getData('from') === null ? null : new \DateTime((string) $request->getData('from'));
                $to    = $request->getData('to') === null ? null : new \DateTime((string) $request->getData('to'));

                $query = static::getQuery();
                $query->limit($limit);

                if (isset($from, $to) && !empty(static::getCreatedAt())) {
                    $query->where(static::getCreatedAt(), '>=', $from);
                    $query->where(static::getCreatedAt(), '<=', $to);
                }

                $result = static::getAllByQuery($query);
            }
        } else {
            return self::getNullModelObj();
        }

        return $result;
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
     * @since  1.0.0
     */
    private static function addInitialized(string $mapper, $id, object $obj = null) : void
    {
        if (!isset(self::$initObjects[$mapper])) {
            self::$initObjects[$mapper] = [];
        }

        self::$initObjects[$mapper][$id] = $obj;
    }

    /**
     * Add initialized object to local cache
     *
     * @param string $mapper Mapper name
     * @param mixed  $id     Object id
     * @param array  $obj    Model to cache locally
     *
     * @return void
     *
     * @since  1.0.0
     */
    private static function addInitializedArray(string $mapper, $id, array $obj = null) : void
    {
        if (!isset(self::$initArrays[$mapper])) {
            self::$initArrays[$mapper] = [];
        }

        self::$initArrays[$mapper][$id] = $obj;
    }

    /**
     * Check if a object is initialized
     *
     * @param string $mapper Mapper name
     * @param mixed  $id     Object id
     *
     * @return bool
     *
     * @since  1.0.0
     */
    private static function isInitialized(string $mapper, $id) : bool
    {
        return isset(self::$initObjects[$mapper], self::$initObjects[$mapper][$id]);
    }

    /**
     * Check if a object is initialized
     *
     * @param string $mapper Mapper name
     * @param mixed  $id     Object id
     *
     * @return bool
     *
     * @since  1.0.0
     */
    private static function isInitializedArray(string $mapper, $id) : bool
    {
        return isset(self::$initArrays[$mapper], self::$initArrays[$mapper][$id]);
    }

    /**
     * Get initialized object
     *
     * @param string $mapper Mapper name
     * @param mixed  $id     Object id
     *
     * @return mixed
     *
     * @since  1.0.0
     */
    private static function getInitialized(string $mapper, $id)
    {
        return self::$initObjects[$mapper][$id] ?? null;
    }

    /**
     * Get initialized object
     *
     * @param string $mapper Mapper name
     * @param mixed  $id     Object id
     *
     * @return mixed
     *
     * @since  1.0.0
     */
    private static function getInitializedArray(string $mapper, $id)
    {
        return self::$initArrays[$mapper][$id] ?? null;
    }

    /**
     * Remove initialized object
     *
     * @param string $mapper Mapper name
     * @param mixed  $id     Object id
     *
     * @return mixed
     *
     * @since  1.0.0
     */
    private static function removeInitialized(string $mapper, $id)
    {
        if (self::isInitialized($mapper, $id)) {
            unset(self::$initObjects[$mapper][$id]);
        }

        if (self::isInitializedArray($mapper, $id)) {
            unset(self::$initArrays[$mapper][$id]);
        }
    }

    /**
     * Find database column name by member name
     *
     * @param string $name member name
     *
     * @return string
     *
     * @throws \Exception Throws this exception if the member couldn't be found
     *
     * @since  1.0.0
     */
    private static function getColumnByMember(string $name) : string
    {
        foreach (static::$columns as $cName => $column) {
            if ($column['internal'] === $name) {
                return $cName;
            }
        }

        throw new \Exception('Invalid member name');
    }

    /**
     * Test if object is null object
     *
     * @param mixed $obj Object to check
     *
     * @return bool
     *
     * @since  1.0.0
     */
    private static function isNullObject($obj) : bool
    {
        return \is_object($obj) && \strpos(\get_class($obj), '\Null') !== false;
    }
}
