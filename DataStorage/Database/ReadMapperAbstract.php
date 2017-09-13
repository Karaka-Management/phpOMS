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
class ReadMapperAbstract extends DataMapperBaseAbstract
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

        foreach(static::$columns as $col) {
            if(isset($col['autocomplete']) && $col['autocomplete']) {
                $query->where(static::$table . '.' . $col['name'], 'LIKE', '%' . $search . '%', 'OR');
            }
        }
        
        return static::getAllByQuery($query);
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
     * @return void
     *
     * @since  1.0.0
     */
    public static function populateManyToMany(array $result, &$obj) /* : void */
    {
        // todo: maybe pass reflectionClass as optional parameter for performance increase
        $reflectionClass = new \ReflectionClass(get_class($obj));

        foreach ($result as $member => $values) {
            if (!empty($values) && $reflectionClass->hasProperty($member)) {
                /** @var DataMapperAbstract $mapper */
                $mapper             = static::$hasMany[$member]['mapper'];
                $reflectionProperty = $reflectionClass->getProperty($member);

                $values = array_diff($values, array_keys(self::$initObjects[$mapper] ?? []));
                if(empty($values)) {
                    continue;
                }

                if (!($accessible = $reflectionProperty->isPublic())) {
                    $reflectionProperty->setAccessible(true);
                }

                $objects = $mapper::get($values);
                $reflectionProperty->setValue($obj, !is_array($objects) ? [$objects] : $objects);

                if (!$accessible) {
                    $reflectionProperty->setAccessible(false);
                }
            }
        }
    }

    /**
     * Populate data.
     *
     * @param array[] $result Result set
     * @param array   $obj    Object to add the relations to
     *
     * @return void
     *
     * @since  1.0.0
     */
    public static function populateManyToManyArray(array $result, array &$obj) /* : void */
    {
        foreach ($result as $member => $values) {
            if (!empty($values)) {
                /** @var DataMapperAbstract $mapper */
                $mapper             = static::$hasMany[$member]['mapper'];
                $values = array_diff($values, array_keys(self::$initObjects[$mapper] ?? []));
                if(empty($values)) {
                    continue;
                }

                $objects = $mapper::getArray($values);
                $obj[$member] = $objects;
            }
        }
    }

    /**
     * Populate data.
     *
     * @param mixed $obj Object to add the relations to
     *
     * @return void
     *
     * @todo   accept reflection class as parameter
     *
     * @since  1.0.0
     */
    public static function populateHasOne(&$obj) /* : void */
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

                if(self::isInitialized($mapper, $reflectionProperty->getValue($obj))) {
                    $value = self::$initObjects[$mapper][$id];
                } else {
                    $value = $mapper::get($reflectionProperty->getValue($obj));
                }
               
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
     * @param array $obj Object to add the relations to
     *
     * @return void
     *
     * @todo   accept reflection class as parameter
     *
     * @since  1.0.0
     */
    public static function populateHasOneArray(array &$obj) /* : void */
    {
        foreach (static::$hasOne as $member => $one) {
            /** @var DataMapperAbstract $mapper */
            $mapper = static::$hasOne[$member]['mapper'];

            if(self::isInitialized($mapper, $obj['member'])) {
                $value = self::$initObjects[$mapper][$id];
            } else {
                $value = $mapper::getArray($obj[$member]);
            }

            $obj[$member] = $value;
        }
    }

    /**
     * Populate data.
     *
     * @param mixed $obj Object to add the relations to
     *
     * @return void
     *
     * @todo   accept reflection class as parameter
     *
     * @since  1.0.0
     */
    public static function populateOwnsOne(&$obj) /* : void */
    {
        $reflectionClass = new \ReflectionClass(get_class($obj));

        foreach (static::$ownsOne as $member => $one) {
            // todo: is that if necessary? performance is suffering for sure!
            if ($reflectionClass->hasProperty($member)) {
                $reflectionProperty = $reflectionClass->getProperty($member);

                if (!($accessible = $reflectionProperty->isPublic())) {
                    $reflectionProperty->setAccessible(true);
                }

                /** @var DataMapperAbstract $mapper */
                $mapper = static::$ownsOne[$member]['mapper'];

                if(self::isInitialized($mapper, $reflectionProperty->getValue($obj))) {
                    $value = self::$initObjects[$mapper][$id];
                } else {
                    $value = $mapper::get($reflectionProperty->getValue($obj));
                }

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
     * @param array $obj Object to add the relations to
     *
     * @return void
     *
     * @todo   accept reflection class as parameter
     *
     * @since  1.0.0
     */
    public static function populateOwnsOneArray(array &$obj) /* : void */
    {
        foreach (static::$ownsOne as $member => $one) {
            /** @var DataMapperAbstract $mapper */
            $mapper = static::$ownsOne[$member]['mapper'];

            if(self::isInitialized($mapper, $obj[$member])) {
                $value = self::$initObjects[$mapper][$id];
            } else {
                $value = $mapper::getArray($obj[$member]);
            }

            $obj[$member] = $value;
        }
    }

    /**
     * Populate data.
     *
     * @param mixed $obj Object to add the relations to
     *
     * @return void
     *
     * @todo   accept reflection class as parameter
     *
     * @since  1.0.0
     */
    public static function populateBelongsTo(&$obj) /* : void */
    {
        $reflectionClass = new \ReflectionClass(get_class($obj));

        foreach (static::$belongsTo as $member => $one) {
            // todo: is that if necessary? performance is suffering for sure!
            if ($reflectionClass->hasProperty($member)) {
                $reflectionProperty = $reflectionClass->getProperty($member);

                if (!($accessible = $reflectionProperty->isPublic())) {
                    $reflectionProperty->setAccessible(true);
                }

                /** @var DataMapperAbstract $mapper */
                $mapper = static::$belongsTo[$member]['mapper'];

                if(self::isInitialized($mapper, $reflectionProperty->getValue($obj))) {
                    $value = self::$initObjects[$mapper][$id];
                } else {
                    $value = $mapper::get($reflectionProperty->getValue($obj));
                }
                
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
     * @param array $obj Object to add the relations to
     *
     * @return void
     *
     * @todo   accept reflection class as parameter
     *
     * @since  1.0.0
     */
    public static function populateBelongsToArray(array &$obj) /* : void */
    {
        foreach (static::$belongsTo as $member => $one) {
            /** @var DataMapperAbstract $mapper */
            $mapper = static::$belongsTo[$member]['mapper'];

            if(self::isInitialized($mapper, $obj[$member])) {
                $value = self::$initObjects[$mapper][$id];
            } else {
                $value = $mapper::get($obj[$member]);
            }
            
            $obj[$member] = $value;
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
                    $reflectionProperty->setValue($obj, new \DateTime($value ?? ''));
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
     * Populate data.
     *
     * @param array $result Query result set
     *
     * @return array
     *
     * @throws \UnexpectedValueException
     *
     * @since  1.0.0
     */
    public static function populateAbstractArray(array $result) : array
    {
        $obj = [];

        foreach ($result as $column => $value) {
            if (isset(static::$columns[$column]['internal'])) {
                if (in_array(static::$columns[$column]['type'], ['string', 'int', 'float', 'bool'])) {
                    settype($value, static::$columns[$column]['type']);
                    $obj[static::$columns[$column]['internal']] = $value;
                } elseif (static::$columns[$column]['type'] === 'DateTime') {
                    $obj[static::$columns[$column]['internal']] = new \DateTime($value ?? '');
                } elseif (static::$columns[$column]['type'] === 'Json') {
                    $obj[static::$columns[$column]['internal']] = json_decode($value, true);
                } elseif (static::$columns[$column]['type'] === 'Serializable') {
                    $obj[static::$columns[$column]['internal']] = $value;
                } else {
                    throw new \UnexpectedValueException('Value "' . static::$columns[$column]['type'] . '" is not supported.');
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
     */
    public static function get($primaryKey, int $relations = RelationType::ALL, $fill = null)
    {
        if(!isset(self::$parentMapper)) {
            self::setUpParentMapper();
        }

        self::extend(__CLASS__);

        $primaryKey = (array) $primaryKey;
        $fill       = (array) $fill;
        $obj        = [];
        $fCount     = count($fill);
        $toFill     = null;

        foreach ($primaryKey as $key => $value) {
            if(self::isInitialized(static::class, $value)) {
                continue;
            }

            if ($fCount > 0) {
                $toFill = current($fill);
                next($fill);
            }

            $obj[$value] = self::populate(self::getRaw($value), $toFill);

            if(method_exists($obj[$value], 'initialize')) {
                $obj[$value]->initialize();
            }

            self::addInitialized(static::class, $value);
        }

        self::fillRelations($obj, $relations);
        self::clear();

        return count($obj) === 1 ? reset($obj) : $obj;
    }

    /**
     * Get object.
     *
     * @param mixed $primaryKey Key
     * @param int   $relations  Load relations
     *
     * @return mixed
     *
     * @since  1.0.0
     */
    public static function getArray($primaryKey, int $relations = RelationType::ALL) : array
    {
        if(!isset(self::$parentMapper)) {
            self::setUpParentMapper();
        }

        self::extend(__CLASS__);

        $primaryKey = (array) $primaryKey;
        $fill       = (array) $fill;
        $obj        = [];

        foreach ($primaryKey as $key => $value) {
            if(self::isInitialized(static::class, $value)) {
                continue;
            }

            $obj[$value] = self::populateAbstractArray(self::getRaw($value));

            self::addInitialized(static::class, $value);
        }

        self::fillRelationsArray($obj, $relations);
        self::clear();

        return count($obj) === 1 ? reset($obj) : $obj;
    }

    /**
     * Get object.
     *
     * @param mixed $refKey Key
     * @param string $ref  The field that defines the for
     * @param int   $relations  Load relations
     * @param mixed $fill       Object to fill
     *
     * @return mixed
     *
     * @since  1.0.0
     */
    public static function getFor($refKey, string $ref, int $relations = RelationType::ALL, $fill = null)
    {
        if(!isset(self::$parentMapper)) {
            self::setUpParentMapper();
        }

        self::extend(__CLASS__);

        $refKey = (array) $refKey;
        $obj        = [];

        foreach ($refKey as $key => $value) {
            // todo: this only works for belongsTo not for many-to-many relations. Implement many-to-many
            $obj[$value] = self::get(self::getPrimaryKeyBy($value, self::getColumnByMember($ref)), $relations, $fill);
        }
        return count($obj) === 1 ? reset($obj) : $obj;
    }

    /**
     * Get object.
     *
     * @param mixed $refKey Key
     * @param string $ref  The field that defines the for
     * @param int   $relations  Load relations
     * @param mixed $fill       Object to fill
     *
     * @return mixed
     *
     * @since  1.0.0
     */
    public static function getForArray($refKey, string $ref, int $relations = RelationType::ALL, $fill = null)
    {
        if(!isset(self::$parentMapper)) {
            self::setUpParentMapper();
        }

        self::extend(__CLASS__);

        $refKey = (array) $refKey;
        $obj    = [];

        foreach ($refKey as $key => $value) {
            // todo: this only works for belongsTo not for many-to-many relations. Implement many-to-many
            $obj[$value] = self::getArray(self::getPrimaryKeyBy($value, self::getColumnByMember($ref)), $relations, $fill);
        }
        return count($obj) === 1 ? reset($obj) : $obj;
    }

    /**
     * Get object.
     *
     * @param int $relations Load relations
     * @param string $lang Language
     *
     * @return array
     *
     * @since  1.0.0
     */
    public static function getAll(int $relations = RelationType::ALL, string $lang = '') : array
    {
        if(!isset(self::$parentMapper)) {
            self::setUpParentMapper();
        }

        $obj = self::populateIterable(self::getAllRaw($lang));
        self::fillRelations($obj, $relations);
        self::clear();

        return $obj;
    }

    /**
     * Get object.
     *
     * @param int $relations Load relations
     * @param string $lang Language
     *
     * @return array
     *
     * @since  1.0.0
     */
    public static function getAllArray(int $relations = RelationType::ALL, string $lang = '') : array
    {
        if(!isset(self::$parentMapper)) {
            self::setUpParentMapper();
        }

        $obj = self::populateIterableArray(self::getAllRaw($lang));
        self::fillRelationsArray($obj, $relations);
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
     * @param string $lang Language
     *
     * @return mixed
     *
     * @since  1.0.0
     */
    public static function getNewest(int $limit = 1, Builder $query = null, int $relations = RelationType::ALL, string $lang = '') : array
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

        if (!empty(self::$language_field) && !empty($lang)) {
            $query->where(static::$table . '.' . static::$language_field, '=', $lang, 'AND');
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
     */
    public static function getAllByQuery(Builder $query, int $relations = RelationType::ALL) : array
    {
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
     */
    public static function getRandom(int $amount = 1, int $relations = RelationType::ALL)
    {
        $query = new Builder(self::$db);
        $query->prefix(self::$db->getPrefix())
            ->random(static::$primaryField)
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
     * @return void
     *
     * @since  1.0.0
     */
    public static function fillRelations(array &$obj, int $relations = RelationType::ALL) /* : void */
    {
        $hasMany = !empty(static::$hasMany);
        $hasOne  = !empty(static::$hasOne);
        $ownsOne = !empty(static::$ownsOne);
        $belongsTo = !empty(static::$belongsTo);

        if ($relations !== RelationType::NONE && ($hasMany || $hasOne || $ownsOne || $belongsTo)) {
            foreach ($obj as $key => $value) {
                /* loading relations from relations table and populating them and then adding them to the object */
                if ($hasMany) {
                    self::populateManyToMany(self::getHasManyRaw($key, $relations), $obj[$key]);
                }

                if ($hasOne) {
                    self::populateHasOne($obj[$key]);
                }

                if ($ownsOne) {
                    self::populateOwnsOne($obj[$key]);
                }

                if ($belongsTo) {
                    self::populateBelongsTo($obj[$key]);
                }
            }
        }
    }

    /**
     * Fill object with relations
     *
     * @param mixed $obj       Objects to fill
     * @param int   $relations Relations type
     *
     * @return void
     *
     * @since  1.0.0
     */
    public static function fillRelationsArray(array &$obj, int $relations = RelationType::ALL) /* : void */
    {
        $hasMany = !empty(static::$hasMany);
        $hasOne  = !empty(static::$hasOne);
        $ownsOne = !empty(static::$ownsOne);
        $belongsTo = !empty(static::$belongsTo);

        if ($relations !== RelationType::NONE && ($hasMany || $hasOne || $ownsOne || $belongsTo)) {
            foreach ($obj as $key => $value) {
                /* loading relations from relations table and populating them and then adding them to the object */
                if ($hasMany) {
                    self::populateManyToManyArray(self::getHasManyRaw($key, $relations), $obj[$key]);
                }

                if ($hasOne) {
                    self::populateHasOneArray($obj[$key]);
                }

                if ($ownsOne) {
                    self::populateOwnsOneArray($obj[$key]);
                }

                if ($belongsTo) {
                    self::populateBelongsToArray($obj[$key]);
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
     * Get object.
     *
     * @param mixed $refKey Key
     * @param string $ref Ref
     *
     * @return mixed
     *
     * @since  1.0.0
     */
    public static function getPrimaryKeyBy($refKey, string $ref) : array
    {
        $query = self::getQuery();
        $query->select(static::$primaryField)
            ->from(static::$table)
            ->where(static::$table . '.' . $ref, '=', $refKey);

        $sth = self::$db->con->prepare($query->toSql());
        $sth->execute();

        $results = array_column($sth->fetchAll(\PDO::FETCH_NUM) ?? [], 0);

        return $results;
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

        if (!empty(self::$language_field) && !empty($lang)) {
            $query->where(static::$table . '.' . static::$language_field, '=', $lang, 'AND');
        }

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
        if (!is_null($request->getData('id'))) {
            $result = static::get($request->getData('id'));
        } elseif (!is_null($filter = $request->getData('filter'))) {
            $filter = strtolower($filter);

            if ($filter === 'all') {
                $result = static::getAll();
            } elseif ($filter === 'list') {
                $list   = $request->getData('list');
                $result = static::get(json_decode($list, true));
            } else {
                $limit = (int) ($request->getData('limit') ?? 1);
                $from  = !is_null($request->getData('from')) ? new \DateTime($request->getData('from')) : null;
                $to    = !is_null($request->getData('to')) ? new \DateTime($request->getData('to')) : null;

                $query = static::getQuery();
                $query->limit($limit);

                if (isset($from, $to) && !empty(static::getCreatedAt())) {
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

    /**
     * Add initialized object to local cache
     *
     * @param string $mapper Mapper name
     * @param mixed $id Object id
     * @param object $obj Model to cache locally
     *
     * @return void
     *
     * @since  1.0.0
     */
    private static function addInitialized(string $mapper, $id, $obj = null) /* : void */
    {
        if(!isset(self::$initObjects[$mapper])) {
            self::$initObjects[$mapper] = [];
        }

        self::$initObjects[$mapper][$id] = $obj;
    }

    /**
     * Check if a object is initialized
     *
     * @param string $mapper Mapper name
     * @param mixed $id Object id
     *
     * @return bool
     *
     * @since  1.0.0
     */
    private static function isInitialized($mapper, $id) : bool
    {
        return isset(self::$initObjects[$mapper]) && isset(self::$initObjects[$mapper][$id]);
    }
}
