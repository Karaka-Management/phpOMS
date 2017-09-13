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
class DeleteMapperAbstract extends DataMapperBaseAbstract
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
     * Delete has many
     *
     * @param \ReflectionClass $reflectionClass Reflection class
     * @param Object           $obj             Object to create
     * @param mixed            $objId           Id to set
     * @param int   $relations Delete all relations as well
     *
     * @return void
     *
     * @throws InvalidMapperException
     *
     * @since  1.0.0
     */
    private static function deleteHasMany(\ReflectionClass $reflectionClass, $obj, $objId, int $relations) /* : void */
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
                    if($relations === RelationType::ALL) {
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
     * @param Object $obj          Object to delete
     *
     * @return mixed
     *
     * @since  1.0.0
     */
    private static function deleteOwnsOne(string $propertyName, $obj)
    {
        if (is_object($obj)) {
            /** @var DataMapperAbstract $mapper */
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
     * @param Object $obj          Object to delete
     *
     * @return mixed
     *
     * @since  1.0.0
     */
    private static function deleteBelongsTo(string $propertyName, $obj)
    {
        if (is_object($obj)) {
            /** @var DataMapperAbstract $mapper */
            $mapper = static::$belongsTo[$propertyName]['mapper'];

            return $mapper::delete($obj);
        }

        return $obj;
    }

    /**
     * Delete object in db.
     *
     * @param Object           $obj             Model to delete
     * @param mixed           $objId             Model id
     * @param int   $relations Delete all relations as well
     * @param \ReflectionClass $reflectionClass Reflection class
     *
     * @return mixed
     *
     * @since  1.0.0
     */
    private static function deleteModel($obj, $objId, int $relations = RelationType::REFERENCE, \ReflectionClass $reflectionClass = null) /* : void */
    {
        $query = new Builder(self::$db);
        $query->prefix(self::$db->getPrefix())
            ->delete()
            ->into(static::$table)
            ->where(static::$primaryField, '=', $objId);

        $properties = $reflectionClass->getProperties();

        if($relations === RelationType::ALL) {
            foreach ($properties as $property) {
                $propertyName = $property->getName();

                if (isset(static::$hasMany[$propertyName]) || isset(static::$hasOne[$propertyName])) {
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

                if (!($isPublic)) {
                    $property->setAccessible(false);
                }
            }
        }

        self::$db->con->prepare($query->toSql())->execute();
    }

    /**
     * Delete object in db.
     *
     * @param mixed $obj Object reference (gets filled with insert id)
     * @param int   $relations Create all relations as well
     *
     * @return mixed
     *
     * @since  1.0.0
     */
    public static function delete($obj, int $relations = RelationType::REFERENCE)
    {
        self::extend(__CLASS__);
        $reflectionClass = new \ReflectionClass(get_class($obj));
        $objId = self::getObjectId($obj, $reflectionClass);

        if(empty($objId)) {
            return null;
        }

        if ($relations !== RelationType::NONE) {
            self::deleteHasMany($reflectionClass, $obj, $objId, $relations);
        }
        
        self::deleteModel($obj, $objId, $relations, $reflectionClass);

        return $objId;
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
        if (
            !empty($objsIds)
            && static::$hasMany[$propertyName]['table'] !== static::$table
            && static::$hasMany[$propertyName]['table'] !== static::$hasMany[$propertyName]['mapper']::$table
        ) {
            foreach ($objsIds as $key => $src) {
                $relQuery = new Builder(self::$db);
                $relQuery->prefix(self::$db->getPrefix())
                    ->into(static::$hasMany[$propertyName]['table'])
                    ->delete();

                $relQuery->where(static::$hasMany[$propertyName]['src'], '=', $src)
                    ->where(static::$hasMany[$propertyName]['dst'], '=', $objId, 'and');

                self::$db->con->prepare($relQuery->toSql())->execute();
            }
        }
    }

}
