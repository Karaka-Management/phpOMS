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
namespace phpOMS\DataStorage\Database;

use phpOMS\DataStorage\Database\Connection\ConnectionAbstract;
use phpOMS\DataStorage\Database\Query\Builder;
use phpOMS\DataStorage\DataMapperInterface;
use phpOMS\Message\RequestAbstract;

/**
 * Datamapper for databases.
 *
 * DB, Cache, Session
 *
 * @category   Framework
 * @package    phpOMS\DataStorage\Database
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
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
     * Columns.
     *
     * @var array<string, array>
     * @since 1.0.0
     */
    protected static $columns = [];

    /**
     * Relations.
     *
     * Relation is defined in a relation table
     *
     * @var string[]
     * @since 1.0.0
     */
    protected static $hasMany = [];

    /**
     * Relations.
     *
     * Relation is defined in the model
     *
     * @var string[]
     * @since 1.0.0
     */
    protected static $hasOne = [];

    /**
     * Relations.
     *
     * Relation is defined in current mapper
     *
     * @var string[]
     * @since 1.0.0
     */
    protected static $ownsOne = [];

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
        'hasOne'       => [],
        'ownsOne'      => [],
        'table'        => [],
    ];

    /**
     * Constructor.
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    private function __construct()
    {
    }

    /**
     * Clone.
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    private function __clone()
    {
    }

    /**
     * Set database connection.
     *
     * @param ConnectionAbstract $con Database connection
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function setConnection(ConnectionAbstract $con)
    {
        self::$db = $con;
    }

    /**
     * Get primary field.
     *
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
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
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
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
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    private static function extend($class)
    {
        /* todo: have to implement this in the queries, so far not used */
        self::$collection['primaryField'][] = $class::$primaryField;
        self::$collection['createdAt'][]    = $class::$createdAt;
        self::$collection['columns'][]      = $class::$columns;
        self::$collection['hasMany'][]      = $class::$hasMany;
        self::$collection['hasOne'][]       = $class::$hasOne;
        self::$collection['ownsOne'][]      = $class::$ownsOne;
        self::$collection['table'][]        = $class::$table;

        if (($parent = get_parent_class($class)) !== false && !$class::$overwrite) {
            self::extend($parent);
        }
    }

    /**
     * Save data.
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function save()
    {
    }

    /**
     * Delete data.
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function delete($obj) : int
    {

        return 0;
    }

    /**
     * Load.
     *
     * @param array $objects Objects to load
     *
     * @return null
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function with(...$objects)
    {
        // todo: how to handle with of parent objects/extends/relations

        self::$fields = $objects;

        return __CLASS__;
    }

    /**
     * Resets all loaded mapper variables.
     *
     * This is used after one action is performed otherwise other models would use wrong settings.
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function clear()
    {
        self::$overwrite    = true;
        self::$primaryField = '';
        self::$createdAt    = '';
        self::$columns      = [];
        self::$hasMany      = [];
        self::$hasOne       = [];
        self::$ownsOne      = [];
        self::$table        = '';
        self::$fields       = [];
        self::$collection   = [
            'primaryField' => [],
            'createdAt'    => [],
            'columns'      => [],
            'hasOne'       => [],
            'ownsMany'     => [],
            'ownsOne'      => [],
            'table'        => [],
        ];
    }

    /**
     * Find data.
     *
     * @param array $columns Columns
     *
     * @return Builder
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function find(...$columns) : Builder
    {
        self::extend(__CLASS__);

        if (count($columns) === 0) {
            $columns = [static::$table . '.*'];
        }

        $query = new Builder(self::$db);
        $query->prefix(self::$db->getPrefix());

        return $query->select(...$columns)->from(static::$table);
    }

    /**
     * Create object in db.
     *
     * @param mixed $obj       Object reference (gets filled with insert id)
     * @param int   $relations Create all relations as well
     *
     * @return mixed
     *
     * @throws
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function create($obj, int $relations = RelationType::ALL)
    {
        self::extend(__CLASS__);

        $reflectionClass = new \ReflectionClass(get_class($obj));
        $objId           = self::createModel($reflectionClass, $obj);

        if ($relations === RelationType::ALL) {
            self::createHasMany($reflectionClass, $obj, $objId);
        }

        self::setObjectId($reflectionClass, $obj, $objId);

        return $objId;
    }

    /**
     * Create base model.
     *
     * @param \ReflectionClass $reflectionClass Reflection class
     * @param Object           $obj             Model to create
     *
     * @return mixed
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    private static function createModel(\ReflectionClass $reflectionClass, $obj)
    {
        $query = new Builder(self::$db);
        $query->prefix(self::$db->getPrefix())->into(static::$table);

        $properties = $reflectionClass->getProperties();

        foreach ($properties as $property) {
            $propertyName = $property->getName();

            if (isset(static::$hasMany[$propertyName]) || isset(static::$hasOne[$propertyName])) {
                continue;
            }

            if (!($isPublic = $property->isPublic())) {
                $property->setAccessible(true);
            }

            foreach (static::$columns as $key => $column) {
                if (isset(static::$ownsOne[$propertyName])) {
                    self::createOwnsOne($propertyName, $property, $obj);
                } elseif ($column['internal'] === $propertyName) {
                    $value = self::parseValue($column['type'], $property->getValue($obj));

                    $query->insert($column['name'])->value($value, $column['type']);
                    break;
                }
            }

            if (!($isPublic)) {
                $property->setAccessible(false);
            }
        }

        self::$db->con->prepare($query->toSql())->execute();

        return self::$db->con->lastInsertId();
    }

    /**
     * Set id to model
     *
     * @param \ReflectionClass $reflectionClass Reflection class
     * @param Object           $obj             Object to create
     * @param mixed            $objId           Id to set
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    private static function setObjectId(\ReflectionClass $reflectionClass, $obj, $objId)
    {
        $reflectionProperty = $reflectionClass->getProperty(static::$columns[static::$primaryField]['internal']);

        if (!($isPublic = $reflectionProperty->isPublic())) {
            $reflectionProperty->setAccessible(true);
        }

        settype($objId, static::$columns[static::$primaryField]['type']);
        $reflectionProperty->setValue($obj, $objId);

        if (!$isPublic) {
            $reflectionProperty->setAccessible(false);
        }
    }

    /**
     * Create has many
     *
     * @param \ReflectionClass $reflectionClass Reflection class
     * @param Object           $obj             Object to create
     * @param mixed            $objId           Id to set
     *
     * @return void
     *
     * @throws
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    private static function createHasMany(\ReflectionClass $reflectionClass, $obj, $objId)
    {
        foreach (static::$hasMany as $propertyName => $rel) {
            $property = $reflectionClass->getProperty($propertyName);

            if (!($isPublic = $property->isPublic())) {
                $property->setAccessible(true);
            }

            $values = $property->getValue($obj);

            if (!($isPublic)) {
                $property->setAccessible(false);
            }

            if (!is_object($temp = reset($values))) {
                throw new \Exception('Unexpected value for relational data mapping.');
            }

            if (!isset(static::$hasMany[$propertyName]['mapper'])) {
                throw new \Exception('No mapper set for relation object.');
            }

            $mapper             = static::$hasMany[$propertyName]['mapper'];
            $objsIds            = [];
            $relReflectionClass = new \ReflectionClass(get_class($temp));

            foreach ($values as $key => &$value) {
                /** @noinspection PhpUndefinedVariableInspection */
                $relProperty = $relReflectionClass->getProperty($mapper::$columns[$mapper::$primaryField]['internal']);

                if (!($isPublic = $relProperty->isPublic())) {
                    $relProperty->setAccessible(true);
                }

                $primaryKey = $relProperty->getValue($value);

                if (!($isPublic)) {
                    $relProperty->setAccessible(false);
                }

                // already in db
                if (!empty($primaryKey)) {
                    continue;
                }

                // Setting relation value (id) for relation (since the relation is not stored in an extra relation table)
                /** @var string $table */
                if (static::$hasMany[$propertyName]['table'] === static::$hasMany[$propertyName]['mapper']::$table) {
                    $relProperty = $relReflectionClass->getProperty($mapper::$columns[static::$hasMany[$propertyName]['dst']]['internal']);

                    if (!$isPublic) {
                        $relProperty->setAccessible(true);
                    }

                    $relProperty->setValue($value, $objId);

                    if (!($isPublic)) {
                        $relProperty->setAccessible(false);
                    }
                }

                $objsIds[$key] = $mapper::create($value);
            }

            self::createRelationTable($propertyName, $objsIds, $objId);
        }
    }

    private static function createHasOne()
    {
        foreach (static::$hasOne as $propertyName => $rel) {

        }
    }

    /**
     * Create owns one
     *
     * The reference is stored in the main model
     *
     * @param string              $propertyName Property name to initialize
     * @param \ReflectionProperty $property     Reflection property
     * @param Object              $obj          Object to create
     *
     * @return mixed
     *
     * @throws
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    private static function createOwnsOne(string $propertyName, \ReflectionProperty $property, $obj)
    {
        if (is_object($relObj = $property->getValue($obj))) {
            $mapper             = static::$ownsOne[$propertyName]['mapper'];
            $relReflectionClass = new \ReflectionClass(get_class($relObj));
            /** @var array $columns */
            $relProperty = $relReflectionClass->getProperty($mapper::$columns[$mapper::$primaryField]['internal']);
            $relProperty->setAccessible(true);
            $primaryKey = $relProperty->getValue($relObj);
            $relProperty->setAccessible(false);

            if (empty($primaryKey)) {
                $primaryKey = $mapper::create($property->getValue($obj));
            }

            return $primaryKey;
        }

        return 0;
    }

    /**
     * Create relation table entry
     *
     * In cas of a many to many relation the relation has to be stored in a relation table
     *
     * @param string $propertyName Property name to initialize
     * @param array  $objsIds      Object ids to insert
     * @param mixed  $objId        Model to reference
     *
     * @return mixed
     *
     * @throws
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    private static function createRelationTable(string $propertyName, array $objsIds, $objId)
    {
        /** @var string $table */
        if (static::$hasMany[$propertyName]['table'] !== static::$table && static::$hasMany[$propertyName]['table'] !== static::$hasMany[$propertyName]['mapper']::$table) {
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
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    private static function parseValue(string $type, $value)
    {
        if ($type === 'DateTime') {
            return isset($value) ? $value->format('Y-m-d H:i:s') : null;
        } elseif ($type === 'json') {
            return isset($value) ? json_encode($value) : '';
        } elseif ($type === 'Serializable') {
            return $value->serialize();
        } elseif ($type === 'jsonSerializable') {
            return $value->jsonSerializable();
        } elseif (is_object($value)) {
            return $value->getId();
        }

        return $value;
    }

    /**
     * Create object in db.
     *
     * @param mixed $obj Object reference (gets filled with insert id)
     *
     * @return int
     *
     * @throws
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function update($obj) : int
    {
        // todo: relations handling (needs to be done first)... updating, deleting or inserting are possible

        self::extend(__CLASS__);

        $query = new Builder(self::$db);
        $query->prefix(self::$db->getPrefix())
            ->into(static::$table);

        $reflectionClass = new \ReflectionClass(get_class($obj));
        $properties      = $reflectionClass->getProperties();

        foreach ($properties as $property) {
            if (!($isPublic = $property->isPublic())) {
                $property->setAccessible(true);
            }

            if (isset(static::$hasMany[($propertyName = $property->getName())])) {
                continue;
            }

            /* is not a has many property */
            foreach (static::$columns as $key => $column) {
                if ($column['internal'] === $propertyName) {
                    $value = $property->getValue($obj);

                    if ($column['type'] === 'DateTime') {
                        $value = isset($value) ? $value->format('Y-m-d H:i:s') : null;
                    }

                    $query->update($column['name'])
                        ->value($value);
                    break;
                }
            }

            if (!$isPublic) {
                $property->setAccessible(false);
            }
        }

        self::$db->con->prepare($query->toSql())->execute();

        return 0;
    }

    /**
     * Populate data.
     *
     * @param array $result Result set
     *
     * @return array
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
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
     * @param mixed $obj    Object to populate
     *
     * @return mixed
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function populate(array $result, $obj = null)
    {
        $class = static::class;
        $class = str_replace('Mapper', '', $class);

        if (count($result) === 0) {
            $parts     = explode('\\', $class);
            $name      = $parts[$c = (count($parts) - 1)];
            $parts[$c] = 'Null' . $name;
            $class     = implode('\\', $parts);
        }

        if (!isset($obj)) {
            $obj = new $class();
        }

        return self::populateAbstract($result, $obj);
    }

    /**
     * Populate data.
     *
     * @param array[] $result Result set
     * @param mixed   $obj    Object to add the relations to
     *
     * @return mixed
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function populateManyToMany(array $result, &$obj)
    {
        $reflectionClass = new \ReflectionClass(get_class($obj));

        foreach ($result as $member => $values) {
            if ($reflectionClass->hasProperty($member)) {
                $mapper             = static::$hasMany[$member]['mapper'];
                $reflectionProperty = $reflectionClass->getProperty($member);

                if (!($accessible = $reflectionProperty->isPublic())) {
                    $reflectionProperty->setAccessible(true);
                }

                // relation table vs relation defined in same table as object e.g. comments
                if ($values !== false) {
                    $objects = $mapper::get($values);
                    $reflectionProperty->setValue($obj, $objects);
                } else {
                    // todo: replace getId() with lookup by primaryKey and the assoziated member variable and get value
                    $query = $mapper::find()->where(static::$hasMany[$member]['table'] . '.' . static::$hasMany[$member]['dst'], '=', $obj->getId());
                    $sth   = self::$db->con->prepare($query->toSql());
                    $sth->execute();

                    $results = $sth->fetchAll(\PDO::FETCH_ASSOC);
                    $objects = $mapper::populateIterable($results);
                    $reflectionProperty->setValue($obj, $objects);
                }

                if (!$accessible) {
                    $reflectionProperty->setAccessible(false);
                }
            }
        }
    }

    /**
     * Populate data.
     *
     * @param mixed $obj Object to add the relations to
     *
     * @return mixed
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function populateOneToOne(&$obj)
    {
        $reflectionClass = new \ReflectionClass(get_class($obj));

        foreach (static::$hasOne as $member => $one) {
            // todo: is that if necessary? performance is suffering for sure!
            if ($reflectionClass->hasProperty($member)) {
                $reflectionProperty = $reflectionClass->getProperty($member);

                if (!($accessible = $reflectionProperty->isPublic())) {
                    $reflectionProperty->setAccessible(true);
                }

                /** @var DataMapperAbstract $mapper */
                $mapper = static::$hasOne[$member]['mapper'];

                $value = $mapper::get($reflectionProperty->getValue($obj));
                $reflectionProperty->setValue($obj, $value);

                if (!$accessible) {
                    $reflectionProperty->setAccessible(false);
                }
            }
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
     * @throws \Exception
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function populateAbstract(array $result, $obj)
    {
        $reflectionClass = new \ReflectionClass(get_class($obj));

        foreach ($result as $column => $value) {
            if (isset(static::$columns[$column]['internal']) && $reflectionClass->hasProperty(static::$columns[$column]['internal'])) {
                $reflectionProperty = $reflectionClass->getProperty(static::$columns[$column]['internal']);

                if (!($accessible = $reflectionProperty->isPublic())) {
                    $reflectionProperty->setAccessible(true);
                }

                if (in_array(static::$columns[$column]['type'], ['string', 'int', 'float', 'bool'])) {
                    settype($value, static::$columns[$column]['type']);
                    $reflectionProperty->setValue($obj, $value);
                } elseif (static::$columns[$column]['type'] === 'DateTime') {
                    $reflectionProperty->setValue($obj, new \DateTime($value));
                } elseif (static::$columns[$column]['type'] === 'Json') {
                    $reflectionProperty->setValue($obj, json_decode($value, true));
                } elseif (static::$columns[$column]['type'] === 'Serializable') {
                    $member = $reflectionProperty->getValue($obj);
                    $member->unserialize($value);
                } else {
                    throw new \UnexpectedValueException('Value "' . static::$columns[$column]['type'] . '" is not supported.');
                }

                if (!$accessible) {
                    $reflectionProperty->setAccessible(false);
                }
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
     *
     * @return mixed
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function get($primaryKey, int $relations = RelationType::ALL, $fill = null)
    {
        self::extend(__CLASS__);

        $primaryKey = (array) $primaryKey;
        $fill       = (array) $fill;
        $obj        = [];
        $fCount     = count($fill);
        $toFill     = null;

        foreach ($primaryKey as $key => $value) {
            if ($fCount > 0) {
                $toFill = current($fill);
                next($fill);
            }

            $obj[$value] = self::populate(self::getRaw($value), $toFill);
        }

        self::fillRelations($obj, $relations);

        self::clear();

        return count($obj) === 1 ? reset($obj) : $obj;
    }

    /**
     * Get object.
     *
     * @param int $relations Load relations
     *
     * @return array
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function getAll(int $relations = RelationType::ALL)
    {
        $obj = self::populateIterable(self::getAllRaw());
        self::fillRelations($obj, $relations);
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
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function listResults(Builder $query)
    {
        $sth = self::$db->con->prepare($query->toSql());
        $sth->execute();

        return self::populateIterable($sth->fetchAll(\PDO::FETCH_ASSOC));
    }

    /**
     * Get newest.
     *
     * This will fall back to the insert id if no datetime column is present.
     *
     * @param int     $limit     Newest limit
     * @param Builder $query     Pre-defined query
     * @param int     $relations Load relations
     *
     * @return mixed
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function getNewest(int $limit = 1, Builder $query = null, int $relations = RelationType::ALL)
    {
        self::extend(__CLASS__);

        $query = $query ?? new Builder(self::$db);
        $query = self::getQuery($query);
        $query->limit($limit); /* todo: limit is not working, setting this to 2 doesn't have any effect!!! */

        if (!empty(static::$createdAt)) {
            $query->orderBy(static::$table . '.' . static::$columns[static::$createdAt]['name'], 'DESC');
        } else {
            $query->orderBy(static::$table . '.' . static::$columns[static::$primaryField]['name'], 'DESC');
        }

        $sth = self::$db->con->prepare($query->toSql());
        $sth->execute();

        $results = $sth->fetchAll(\PDO::FETCH_ASSOC);
        $obj     = self::populateIterable(is_bool($results) ? [] : $results);

        self::fillRelations($obj, $relations);
        self::clear();

        return $obj;

    }

    /**
     * Get all by custom query.
     *
     * @param Builder $query     Query
     * @param int     $relations Relations
     *
     * @return array
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function getAllByQuery(Builder $query, int $relations = RelationType::ALL) : array
    {
        $query = self::getQuery($query);
        $sth   = self::$db->con->prepare($query->toSql());
        $sth->execute();

        $results = $sth->fetchAll(\PDO::FETCH_ASSOC);
        $results = is_bool($results) ? [] : $results;

        $obj = self::populateIterable($results);
        self::fillRelations($obj, $relations);
        self::clear();

        return $obj;
    }

    /**
     * Get random object
     *
     * @param int $amount    Amount of random models
     * @param int $relations Relations type
     *
     * @return mixed
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function getRandom(int $amount = 1, int $relations = RelationType::ALL)
    {
        $query = new Builder(self::$db);
        $query->prefix(self::$db->getPrefix())
            ->random(static::$primaryKey)
            ->from(static::$table)
            ->limit($amount);

        $sth = self::$db->con->prepare($query->toSql());
        $sth->execute();

        return self::get($sth->fetchAll(), $relations);
    }

    /**
     * Fill object with relations
     *
     * @param mixed $obj       Objects to fill
     * @param int   $relations Relations type
     *
     * @return array
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function fillRelations(array &$obj, int $relations = RelationType::ALL)
    {
        $hasMany = count(static::$hasMany) > 0;
        $hasOne  = count(static::$hasOne) > 0;
        $ownsOne = count(static::$ownsOne) > 0;

        if ($relations !== RelationType::NONE && ($hasMany || $hasOne)) {
            foreach ($obj as $key => $value) {
                /* loading relations from relations table and populating them and then adding them to the object */
                if ($relations !== RelationType::NONE) {
                    if ($hasMany) {
                        self::populateManyToMany(self::getHasManyRaw($key, $relations), $obj[$key]);
                    }

                    if ($hasOne) {
                        self::populateOneToOne($obj[$key]);
                    }
                }
            }
        }
    }

    /**
     * Get object.
     *
     * @param mixed $primaryKey Key
     *
     * @return mixed
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function getRaw($primaryKey) : array
    {
        $query = self::getQuery();
        $query->where(static::$table . '.' . static::$primaryField, '=', $primaryKey);

        $sth = self::$db->con->prepare($query->toSql());
        $sth->execute();

        $results = $sth->fetch(\PDO::FETCH_ASSOC);

        return is_bool($results) ? [] : $results;
    }

    /**
     * Get all in raw output.
     *
     * @return array
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function getAllRaw() : array
    {
        $query = self::getQuery();
        $sth   = self::$db->con->prepare($query->toSql());
        $sth->execute();

        $results = $sth->fetchAll(\PDO::FETCH_ASSOC);

        return is_bool($results) ? [] : $results;
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
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function getHasManyRaw($primaryKey, int $relations = RelationType::ALL) : array
    {
        $result = [];

        foreach (static::$hasMany as $member => $value) {
            if (!isset($value['relationmapper'])) {
                $query = new Builder(self::$db);
                $query->prefix(self::$db->getPrefix());

                if ($relations === RelationType::ALL) {
                    $query->select($value['table'] . '.' . $value['src'])
                        ->from($value['table'])
                        ->where($value['table'] . '.' . $value['dst'], '=', $primaryKey);
                } elseif ($relations === RelationType::NEWEST) {

                    /*
                    SELECT c.*, p1.*
                    FROM customer c
                    JOIN purchase p1 ON (c.id = p1.customer_id)
                    LEFT OUTER JOIN purchase p2 ON (c.id = p2.customer_id AND 
                        (p1.date < p2.date OR p1.date = p2.date AND p1.id < p2.id))
                    WHERE p2.id IS NULL;
                    */
                    /*
                                        $query->select(static::$table . '.' . static::$primaryField, $value['table'] . '.' . $value['src'])
                                              ->from(static::$table)
                                              ->join($value['table'])
                                              ->on(static::$table . '.' . static::$primaryField, '=', $value['table'] . '.' . $value['dst'])
                                              ->leftOuterJoin($value['table'])
                                              ->on(new And('1', new And(new Or('d1', 'd2'), 'id')))
                                              ->where($value['table'] . '.' . $value['dst'], '=', 'NULL');
                                              */
                }

                $sth = self::$db->con->prepare($query->toSql());
                $sth->execute();
                $result[$member] = $sth->fetchAll(\PDO::FETCH_COLUMN);
            } else {
                $result[$member] = false;
            }
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
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
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
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function getCreatedAt() : string
    {
        return static::$createdAt;
    }

    /**
     * Get model based on request object
     *
     * @param RequestAbstract $request Request object
     *
     * @return mixed
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function getByRequest(RequestAbstract $request)
    {
        if (!is_null($request->getData('id'))) {
            $result = static::get($request->getData('id'))->__toString();
        } elseif (!is_null($filter = $request->getData('filter'))) {
            $filter = strtolower($filter);

            if ($filter === 'all') {
                $result = static::getAll();
            } elseif ($filter === 'list') {
                $list   = $request->getData('list');
                $result = static::get(json_decode($list, true));
            } else {
                $limit = $request->getData('limit') ?? 1;
                $from  = !is_null($request->getData('from')) ? new \DateTime($request->getData('from')) : null;
                $to    = !is_null($request->getData('to')) ? new \DateTime($request->getData('to')) : null;

                $query = static::getQuery();
                $query->limit($limit);

                if (isset($from) && isset($to) && !empty(static::getCreatedAt())) {
                    $query->where(static::getCreatedAt(), '>=', $from);
                    $query->where(static::getCreatedAt(), '<=', $to);
                }

                $result = static::getAllByQuery($query);
            }
        } else {
            $class     = static::class;
            $class     = str_replace('Mapper', '', $class);
            $parts     = explode('\\', $class);
            $name      = $parts[$c = (count($parts) - 1)];
            $parts[$c] = 'Null' . $name;
            $class     = implode('\\', $parts);
            $result    = new $class();
        }

        return $result;
    }

}
