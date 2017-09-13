<?php
/**
 * Orange Management
 *
 * PHP Version 7.1
 *
 * @category   TBD
 * @package    TBD
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://orange-management.com
 */
declare(strict_types=1);

namespace phpOMS\DataStorage\Database;

use phpOMS\DataStorage\Database\Query\Builder;
use phpOMS\DataStorage\DataMapperBaseAbstract;
use phpOMS\Message\RequestAbstract;
use phpOMS\DataStorage\Database\Exception\InvalidMapperException;

/**
 * Datamapper for databases.
 *
 * DB, Cache, Session
 *
 * @category   Framework
 * @package    phpOMS\DataStorage\Database
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
class CreateMapperAbstract extends DataMapperBaseAbstract
{
    /**
     * Constructor.
     *
     * @since  1.0.0
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
     */
    private function __clone()
    {
    }

    /**
     * Create object in db.
     *
     * @param mixed $obj       Object reference (gets filled with insert id)
     * @param int   $relations Create all relations as well
     *
     * @return mixed
     *
     * @since  1.0.0
     */
    public static function create($obj, int $relations = RelationType::ALL)
    {
        self::extend(__CLASS__);

        if($obj === null ||
            (is_object($obj) && strpos($className = get_class($obj), '\Null') !== false)
        ) {
            return null;
        }

        $reflectionClass = new \ReflectionClass($className);
        $objId           = self::createModel($obj, $reflectionClass);
        self::setObjectId($reflectionClass, $obj, $objId);

        if ($relations === RelationType::ALL) {
            self::createHasMany($reflectionClass, $obj, $objId);
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

        $objId           = self::createModelArray($obj);
        settype($objId, static::$columns[static::$primaryField]['type']);
        $obj[static::$columns[static::$primaryField]['internal']] = $objId;

        if ($relations === RelationType::ALL) {
            self::createHasManyArray($obj, $objId);
        }

        return $objId;
    }

    /**
     * Create base model.
     *
     * @param Object           $obj             Model to create
     * @param \ReflectionClass $reflectionClass Reflection class
     *
     * @return mixed
     *
     * @since  1.0.0
     */
    private static function createModel($obj, \ReflectionClass $reflectionClass)
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
                if (isset(static::$ownsOne[$propertyName]) && $column['internal'] === $propertyName) {
                    $id    = self::createOwnsOne($propertyName, $property->getValue($obj));
                    $value = self::parseValue($column['type'], $id);

                    $query->insert($column['name'])->value($value, $column['type']);
                    break;
                } elseif (isset(static::$belongsTo[$propertyName]) && $column['internal'] === $propertyName) {
                    $id    = self::createBelongsTo($propertyName, $property->getValue($obj));
                    $value = self::parseValue($column['type'], $id);

                    $query->insert($column['name'])->value($value, $column['type']);
                    break;
                } elseif ($column['internal'] === $propertyName && $column['type'] !== static::$primaryField) {
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
     * Create base model.
     *
     * @param Object           $obj             Model to create
     *
     * @return mixed
     *
     * @since  1.0.0
     */
    private static function createModelArray($obj)
    {
        $query = new Builder(self::$db);
        $query->prefix(self::$db->getPrefix())->into(static::$table);

        foreach ($obj as $propertyName => &$property) {
            if (isset(static::$hasMany[$propertyName]) || isset(static::$hasOne[$propertyName])) {
                continue;
            }

            foreach (static::$columns as $key => $column) {
                if (isset(static::$ownsOne[$propertyName]) && $column['internal'] === $propertyName) {
                    $id    = self::createOwnsOneArray($propertyName, $property);
                    $value = self::parseValue($column['type'], $id);

                    $query->insert($column['name'])->value($value, $column['type']);
                    break;
                } elseif (isset(static::$belongsTo[$propertyName]) && $column['internal'] === $propertyName) {
                    $id    = self::createBelongsToArray($propertyName, $property);
                    $value = self::parseValue($column['type'], $id);

                    $query->insert($column['name'])->value($value, $column['type']);
                    break;
                } elseif ($column['internal'] === $propertyName && $column['type'] !== static::$primaryField) {
                    $value = self::parseValue($column['type'], $property);

                    $query->insert($column['name'])->value($value, $column['type']);
                    break;
                }
            }
        }

        self::$db->con->prepare($query->toSql())->execute();

        return self::$db->con->lastInsertId();
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
     * @throws InvalidMapperException
     *
     * @since  1.0.0
     */
    private static function createHasMany(\ReflectionClass $reflectionClass, $obj, $objId) /* : void */
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

            if (!isset(static::$hasMany[$propertyName]['mapper'])) {
                throw new InvalidMapperException();
            }

            /** @var DataMapperAbstract $mapper */
            $mapper             = static::$hasMany[$propertyName]['mapper'];
            $objsIds            = [];
            $relReflectionClass = null;

            foreach ($values as $key => &$value) {
                if (!is_object($value)) {
                    // Is scalar => already in database
                    $objsIds[$key] = $value;

                    continue;
                }

                if (!isset($relReflectionClass)) {
                    $relReflectionClass = new \ReflectionClass(get_class($value));
                }

                $primaryKey = $mapper::getObjectId($value, $relReflectionClass);

                // already in db
                if (!empty($primaryKey)) {
                    $objsIds[$key] = $value;

                    continue;
                }

                // Setting relation value (id) for relation (since the relation is not stored in an extra relation table)
                /** @var string $table */
                /** @var array $columns */
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

    /**
     * Create has many
     *
     * @param array           $obj             Object to create
     * @param mixed            $objId           Id to set
     *
     * @return void
     *
     * @throws InvalidMapperException
     *
     * @since  1.0.0
     */
    private static function createHasManyArray(array &$obj, $objId) /* : void */
    {
        foreach (static::$hasMany as $propertyName => $rel) {
            $values = $obj[$propertyName];

            if (!isset(static::$hasMany[$propertyName]['mapper'])) {
                throw new InvalidMapperException();
            }

            /** @var DataMapperAbstract $mapper */
            $mapper             = static::$hasMany[$propertyName]['mapper'];
            $objsIds            = [];

            foreach ($values as $key => &$value) {
                if (!is_object($value)) {
                    // Is scalar => already in database
                    $objsIds[$key] = $value;

                    continue;
                }

                $primaryKey = $obj[static::$columns[static::$primaryField]['internal']];

                // already in db
                if (!empty($primaryKey)) {
                    $objsIds[$key] = $value;

                    continue;
                }

                // Setting relation value (id) for relation (since the relation is not stored in an extra relation table)
                /** @var string $table */
                /** @var array $columns */
                if (static::$hasMany[$propertyName]['table'] === static::$hasMany[$propertyName]['mapper']::$table) {
                    $value[$mapper::$columns[static::$hasMany[$propertyName]['dst']]['internal']] = $objId;
                }

                $objsIds[$key] = $mapper::createArray($value);
            }

            self::createRelationTable($propertyName, $objsIds, $objId);
        }
    }

    private static function createHasOne(\ReflectionClass $reflectionClass, $obj)
    {
        throw new \Exception();

        foreach (static::$hasOne as $propertyName => $rel) {

        }
    }

    /**
     * Create owns one
     *
     * The reference is stored in the main model
     *
     * @param string $propertyName Property name to initialize
     * @param Object $obj          Object to create
     *
     * @return mixed
     *
     * @since  1.0.0
     */
    private static function createOwnsOne(string $propertyName, $obj)
    {
        if (is_object($obj)) {
            $mapper             = static::$ownsOne[$propertyName]['mapper'];
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
     * @param array $obj          Object to create
     *
     * @return mixed
     *
     * @since  1.0.0
     */
    private static function createOwnsOneArray(string $propertyName, array &$obj)
    {
        if (is_array($obj)) {
            $mapper             = static::$ownsOne[$propertyName]['mapper'];
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
     * @param Object $obj          Object to create
     *
     * @return mixed
     *
     * @since  1.0.0
     */
    private static function createBelongsTo(string $propertyName, $obj)
    {
        if (is_object($obj)) {
            /** @var DataMapperAbstract $mapper */
            $mapper             = static::$belongsTo[$propertyName]['mapper'];
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
     * @param Object $obj          Object to create
     *
     * @return mixed
     *
     * @since  1.0.0
     */
    private static function createBelongsToArray(string $propertyName, array $obj)
    {
        if (is_array($obj)) {
            /** @var DataMapperAbstract $mapper */
            $mapper             = static::$belongsTo[$propertyName]['mapper'];
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
     * @return mixed
     *
     * @since  1.0.0
     */
    private static function createRelationTable(string $propertyName, array $objsIds, $objId)
    {
        /** @var string $table */
        if (
            !empty($objsIds)
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
}
