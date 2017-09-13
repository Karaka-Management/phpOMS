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
class UpdateMapperAbstract extends DataMapperBaseAbstract
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
     * Update has many
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
    private static function updateHasMany(\ReflectionClass $reflectionClass, $obj, $objId) /* : void */
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

                // create if not existing
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

            self::updateRelationTable($propertyName, $objsIds, $objId);
        }
    }

    /**
     * Update relation table entry
     *
     * Deletes old entries and creates new ones
     *
     * @param string $propertyName Property name to initialize
     * @param array  $objsIds      Object ids to insert
     * @param mixed  $objId        Model to reference
     *
     * @return mixed
     *
     * @throws \Exception
     *
     * @since  1.0.0
     */
    private static function updateRelationTable(string $propertyName, array $objsIds, $objId)
    {
        /** @var string $table */
        if (
            !empty($objsIds)
            && static::$hasMany[$propertyName]['table'] !== static::$table
            && static::$hasMany[$propertyName]['table'] !== static::$hasMany[$propertyName]['mapper']::$table
        ) {
            $many = self::getHasManyRaw($objId);

            foreach(static::$hasMany as $member => $value) {
                // todo: definately an error here. needs testing
                throw new \Exception();
                $removes = array_diff_key($many[$member], $objsIds[$member]);
                $adds = array_diff_key($objsIds[$member], $many[$member]);

                if(!empty($removes)) {
                    self::deleteRelationTable($propertyName, $removes, $objId);
                }

                if(!empty($adds)) {
                    self::createRelationTable($propertyName, $adds, $objId);
                }
            }
        }
    }

    /**
     * Update owns one
     *
     * The reference is stored in the main model
     *
     * @param string $propertyName Property name to initialize
     * @param Object $obj          Object to update
     *
     * @return mixed
     *
     * @since  1.0.0
     */
    private static function updateOwnsOne(string $propertyName, $obj)
    {
        if (is_object($obj)) {
            /** @var DataMapperAbstract $mapper */
            $mapper = static::$ownsOne[$propertyName]['mapper'];

            // todo: delete owned one object is not recommended since it can be owned by by something else? or does owns one mean that nothing else can have a relation to this one?

            return $mapper::update($obj);
        }

        return $obj;
    }

    /**
     * Update owns one
     *
     * The reference is stored in the main model
     *
     * @param string $propertyName Property name to initialize
     * @param Object $obj          Object to update
     *
     * @return mixed
     *
     * @since  1.0.0
     */
    private static function updateBelongsTo(string $propertyName, $obj)
    {
        if (is_object($obj)) {
            /** @var DataMapperAbstract $mapper */
            $mapper = static::$belongsTo[$propertyName]['mapper'];

            return $mapper::update($obj);
        }

        return $obj;
    }

    /**
     * Update object in db.
     *
     * @param Object           $obj             Model to update
     * @param mixed           $objId             Model id
     * @param \ReflectionClass $reflectionClass Reflection class
     *
     * @return mixed
     *
     * @since  1.0.0
     */
    private static function updateModel($obj, $objId, \ReflectionClass $reflectionClass = null) /* : void */
    {
        $query = new Builder(self::$db);
        $query->prefix(self::$db->getPrefix())
            ->into(static::$table)
            ->where(static::$primaryField, '=', $objId);

        $properties = $reflectionClass->getProperties();

        foreach ($properties as $property) {
            $propertyName = $property->getName();

            if (isset(static::$hasMany[$propertyName]) || isset(static::$hasOne[$propertyName])) {
                continue;
            }

            if (!($isPublic = $property->isPublic())) {
                $property->setAccessible(true);
            }

            // todo: the order of updating could be a problem. maybe looping through ownsOne and belongsTo first is better.

            foreach (static::$columns as $key => $column) {
                if (isset(static::$ownsOne[$propertyName]) && $column['internal'] === $propertyName) {
                    $id = self::updateOwnsOne($propertyName, $property->getValue($obj));
                    $value = self::parseValue($column['type'], $id);

                    // todo: should not be done if the id didn't change. but for now don't know if id changed
                    $query->update($column['name'])->value($value, $column['type']);
                    break;
                } elseif (isset(static::$belongsTo[$propertyName]) && $column['internal'] === $propertyName) {
                    $id    = self::updateBelongsTo($propertyName, $property->getValue($obj));
                    $value = self::parseValue($column['type'], $id);

                    // todo: should not be done if the id didn't change. but for now don't know if id changed
                    $query->update($column['name'])->value($value, $column['type']);
                    break;
                } elseif ($column['internal'] === $propertyName && $column['type'] !== static::$primaryField) {
                     $value = self::parseValue($column['type'], $property->getValue($obj));

                    $query->update($column['name'])->value($value, $column['type']);
                    break;
                }
            }

            if (!($isPublic)) {
                $property->setAccessible(false);
            }
        }

        self::$db->con->prepare($query->toSql())->execute();
    }

    /**
     * Update object in db.
     *
     * @param mixed $obj Object reference (gets filled with insert id)
     * @param int   $relations Create all relations as well
     *
     * @return int
     *
     * @since  1.0.0
     */
    public static function update($obj, int $relations = RelationType::ALL) : int
    {
        self::extend(__CLASS__);
        $reflectionClass = new \ReflectionClass(get_class($obj));
        $objId = self::getObjectId($obj, $reflectionClass);
        $update = true;

        if(empty($objId)) {
            $update = false;
            self::create($obj, $relations);
        }

        if ($relations === RelationType::ALL) {
            self::updateHasMany($reflectionClass, $obj, $objId);
        }
        
        if($update) {
            self::updateModel($obj, $objId, $reflectionClass);
        }

        return $objId;
    }
}
