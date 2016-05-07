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
abstract class DataMapperAbstract implements DataMapperInterface
{

    /**
     * Database connection.
     *
     * @var \phpOMS\DataStorage\Database\Connection\ConnectionAbstract
     * @since 1.0.0
     */
    protected $db = null;

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
     * @var string[]
     * @since 1.0.0
     */
    protected static $hasMany = [];

    /**
     * Relations.
     *
     * @var string[]
     * @since 1.0.0
     */
    protected static $ownsMany = [];

    /**
     * Relations.
     *
     * @var string[]
     * @since 1.0.0
     */
    protected static $hasOne = [];

    /**
     * Extending other mappers.
     *
     * @var string[]
     * @since 1.0.0
     */
    protected static $isExtending = [];

    /**
     * Extending relations.
     *
     * @var string[]
     * @since 1.0.0
     */
    protected static $extends = [];

    /**
     * Relations.
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
    protected $fields = [];

    /**
     * Extended value collection.
     *
     * @var array
     * @since 1.0.0
     */
    protected $collection = [
        'primaryField' => [],
        'createdAt'    => [],
        'columns'      => [],
        'hasMany'      => [],
        'hasOne'       => [],
        'ownsMany'     => [],
        'ownsOne'      => [],
        'table'        => [],
    ];

    /**
     * Constructor.
     *
     * @param ConnectionAbstract $con Database connection
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function __construct(ConnectionAbstract $con)
    {
        $this->db = $con;
        $this->extend($this);
    }

    /**
     * Get primary field.
     *
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getPrimaryField() : string
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
    public function getTable() : string
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
    private function extend($class)
    {
        /* todo: have to implement this in the queries, so far not used */
        $this->collection['primaryField'][] = $class::$primaryField;
        $this->collection['createdAt'][]    = $class::$createdAt;
        $this->collection['columns'][]      = $class::$columns;
        $this->collection['hasMany'][]      = $class::$hasMany;
        $this->collection['hasOne'][]       = $class::$hasOne;
        $this->collection['ownsMany'][]     = $class::$ownsMany;
        $this->collection['ownsOne'][]      = $class::$ownsOne;
        $this->collection['table'][]        = $class::$table;

        if (($parent = get_parent_class($class)) !== false && !$class::$overwrite) {
            $this->extend($parent);
        }
    }

    /**
     * Save data.
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function save()
    {
    }

    /**
     * Delete data.
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function delete()
    {
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
    public function with(...$objects)
    {
        // todo: how to handle with of parent objects/extends/relations

        $this->fields = $objects;

        return $this;
    }

    public function clear()
    {
        $this->fields = [];
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
    public function find(...$columns) : Builder
    {
        if (count($columns) === 0) {
            $columns = [static::$table . '.*'];
        }

        $query = new Builder($this->db);
        $query->prefix($this->db->getPrefix());

        return $query->select(...$columns)->from(static::$table);
    }

    /**
     * Create object in db.
     *
     * @param mixed $obj       Object reference (gets filled with insert id)
     * @param bool  $relations Create all relations as well
     *
     * @return mixed
     *
     * @throws
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function create($obj, bool $relations = true)
    {
        $query = new Builder($this->db);
        $query->prefix($this->db->getPrefix())
            ->into(static::$table);

        $reflectionClass = new \ReflectionClass(get_class($obj));
        $properties      = $reflectionClass->getProperties();
        $extendedIds     = [];

        /* Create extended */
        foreach (static::$isExtending as $member => $rel) {
            /** @var DataMapperAbstract $mapper */
            $mapper               = new $rel['mapper']($this->db);
            $extendedIds[$member] = $mapper->create($obj, $relations);
        }

        foreach ($properties as $property) {
            $property->setAccessible(true);

            if (isset(static::$hasMany[($pname = $property->getName())])) {
                continue;
            } else {
                /* is not a has many property */
                foreach (static::$columns as $key => $column) {
                    /* Insert hasOne first */
                    if (isset(static::$hasOne[$pname]) && is_object($relObj = $property->getValue($obj))) {
                        /* only insert if not already inserted */
                        /** @var DataMapperAbstract $mapper */
                        $mapper = static::$hasOne[$pname]['mapper'];
                        $mapper = new $mapper($this->db);

                        $relReflectionClass = new \ReflectionClass(get_class($relObj));
                        /** @var array $columns */
                        $relProperty = $relReflectionClass->getProperty($mapper::$columns[$mapper::$primaryField]['internal']);
                        $relProperty->setAccessible(true);
                        $primaryKey = $relProperty->getValue($relObj);
                        $relProperty->setAccessible(false);

                        if (empty($primaryKey)) {
                            $primaryKey = $mapper->create($property->getValue($obj));
                        }

                        //$property->setValue($obj, $primaryKey);
                    }

                    if ($column['internal'] === $pname) {
                        if (isset($extendedIds[$pname])) {
                            /* Set extended id */
                            $value = $extendedIds[$pname];
                            $property->setValue($obj, $value);
                        } else {
                            $value = $property->getValue($obj);
                        }

                        if ($column['type'] === 'DateTime') {
                            $value = isset($value) ? $value->format('Y-m-d H:i:s') : null;
                        } elseif ($column['type'] === 'Json') {
                            $value = isset($value) ? json_encode($value) : '';
                        } elseif ($column['type'] === 'Serializable') {
                            $value = $value->serialize();
                        } elseif (is_object($value)) {
                            $value = $value->getId();
                        }

                        $query->insert($column['name'])
                            ->value($value, $column['type']);
                        break;
                    }
                }
            }

            // todo: do i have to reverse the accessibility or is there no risk involved here?
        }

        $this->db->con->prepare($query->toSql())->execute();
        $objId = $this->db->con->lastInsertId();

        // handle relations
        if ($relations) {
            foreach (static::$hasMany as $member => $rel) {
                /* is a has many property */
                $property = $reflectionClass->getProperty($member); // throws ReflectionException

                if (!($isPublic = $property->isPublic())) {
                    $property->setAccessible(true);
                }

                $values = $property->getValue($obj);
                $temp   = reset($values);
                $pname  = $property->getName(); // todo: isn't this just member? and not necessary?

                if (!($isPublic)) {
                    $property->setAccessible(false);
                }

                if (is_object($temp)) {
                    // todo: only create if object doesn't exists... get primaryKey field, then get member name based on this
                    // now check if id is null or set.
                    $mapper  = static::$hasMany[$pname]['mapper'];
                    $mapper  = new $mapper($this->db);
                    $objsIds = [];

                    if (isset(static::$hasMany[$pname]['mapper']) && static::$hasMany[$pname]['mapper'] === static::$hasMany[$pname]['relationmapper']) {
                        $relReflectionClass = new \ReflectionClass(get_class($temp));
                    } else {
                        // todo: init other $relReflectionClass?!
                        throw new \Exception('This should never happen, I guess?!.');
                    }

                    foreach ($values as $key => &$value) {
                        // Skip if already in db/has key
                        /** @noinspection PhpUndefinedVariableInspection */
                        $relProperty = $relReflectionClass->getProperty($mapper::$columns[$mapper::$primaryField]['internal']);
                        $relProperty->setAccessible(true);
                        $primaryKey = $relProperty->getValue($value);
                        $relProperty->setAccessible(false);

                        if (!empty($primaryKey)) {
                            continue;
                        }

                        // Setting relation value for relation (since the relation is not stored in an extra relation table)
                        if (isset(static::$hasMany[$pname]['mapper']) && static::$hasMany[$pname]['mapper'] === static::$hasMany[$pname]['relationmapper']) {
                            $relProperty = $relReflectionClass->getProperty($mapper::$columns[static::$hasMany[$pname]['dst']]['internal']);
                            $relProperty->setAccessible(true);
                            $relProperty->setValue($value, $objId);
                            $relProperty->setAccessible(false);
                        }

                        $objsIds[$key] = $mapper->create($value);
                    }
                } elseif (is_scalar($temp)) {
                    $objsIds = $values;
                } else {
                    throw new \Exception('Unexpected value for relational data mapping.');
                }

                if (isset(static::$hasMany[$pname]['mapper']) && static::$hasMany[$pname]['mapper'] !== static::$hasMany[$pname]['relationmapper']) {
                    /* is many->many */
                    $relQuery = new Builder($this->db);
                    $relQuery->prefix($this->db->getPrefix())
                        ->into(static::$hasMany[$pname]['table'])
                        ->insert(static::$hasMany[$pname]['src'], static::$hasMany[$pname]['dst']);

                    foreach ($objsIds as $key => $src) {
                        $relQuery->values($src, $objId);
                    }

                    $this->db->con->prepare($relQuery->toSql())->execute();
                }
            }
        }

        $reflectionProperty = $reflectionClass->getProperty(static::$columns[static::$primaryField]['internal']);

        // todo: can't i just set it accessible anyways and not set it to private afterwards?
        if (!($accessible = $reflectionProperty->isPublic())) {
            $reflectionProperty->setAccessible(true);
        }

        settype($objId, static::$columns[static::$primaryField]['type']);
        $reflectionProperty->setValue($obj, $objId);

        if (!$accessible) {
            $reflectionProperty->setAccessible(false);
        }

        return $objId;
    }

    /**
     * Create object in db.
     *
     * @param mixed $obj Object reference (gets filled with insert id)
     *
     * @return void
     *
     * @throws
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function update($obj)
    {
        // todo: relations handling (needs to be done first)... updating, deleting or inserting are possible

        $query = new Builder($this->db);
        $query->prefix($this->db->getPrefix())
            ->into(static::$table);

        $reflectionClass = new \ReflectionClass(get_class($obj));
        $properties      = $reflectionClass->getProperties();

        foreach ($properties as $property) {
            $property->setAccessible(true);

            if (isset(static::$hasMany[($pname = $property->getName())])) {
                continue;
            } else {
                /* is not a has many property */
                foreach (static::$columns as $key => $column) {
                    if ($column['internal'] === $pname) {
                        $value = $property->getValue($obj);

                        if ($column['type'] === 'DateTime') {
                            $value = isset($value) ? $value->format('Y-m-d H:i:s') : null;
                        }

                        $query->update($column['name'])
                            ->value($value);
                        break;
                    }
                }
            }

            // todo: do i have to reverse the accessibility or is there no risk involved here?
        }

        $this->db->con->prepare($query->toSql())->execute();
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
    public function populateIterable(array $result) : array
    {
        $row = [];

        foreach ($result as $element) {
            if (isset($element[static::$primaryField])) {
                $row[$element[static::$primaryField]] = $this->populate($element);
            } else {
                $row[] = $this->populate($element);
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
    public function populate(array $result, $obj = null)
    {
        $class = get_class($this);
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

        return $this->populateAbstract($result, $obj);
    }

    /**
     * Populate data.
     *
     * Is overwriting the hasOne id stored in the member variable by the object.
     * todo: hasMany needs to be implemented somehow?!?!
     *
     * @param     $obj       Object to add the relations to
     * @param int $relations Relations type
     *
     * @return mixed
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function populateExtending($obj, int $relations = RelationType::ALL)
    {
        $reflectionClass = new \ReflectionClass(get_class($obj));

        foreach (static::$isExtending as $member => $rel) {
            $reflectionProperty = $reflectionClass->getProperty($member);

            /** @var DataMapperAbstract $mapper */
            $mapper = new $rel['mapper']($this->db);
            $mapper->get($reflectionProperty->getValue($obj), $relations, $obj);
        }
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
    public function populateManyToMany(array $result, &$obj)
    {
        $reflectionClass = new \ReflectionClass(get_class($obj));

        foreach ($result as $member => $values) {
            if ($reflectionClass->hasProperty($member)) {
                $mapper = static::$hasMany[$member]['mapper'];
                /** @var DataMapperAbstract $mapper */
                $mapper             = new $mapper($this->db);
                $reflectionProperty = $reflectionClass->getProperty($member);

                if (!($accessible = $reflectionProperty->isPublic())) {
                    $reflectionProperty->setAccessible(true);
                }

                // relation table vs relation defined in same table as object e.g. comments
                if ($values !== false) {
                    $objects = $mapper->get($values);
                    $reflectionProperty->setValue($obj, $objects);
                } else {
                    // todo: replace getId() with lookup by primaryKey and the assoziated member variable and get value
                    $query = $mapper->find()->where(static::$hasMany[$member]['table'] . '.' . static::$hasMany[$member]['dst'], '=', $obj->getId());
                    $sth   = $this->db->con->prepare($query->toSql());
                    $sth->execute();

                    $results = $sth->fetchAll(\PDO::FETCH_ASSOC);
                    $objects = $mapper->populateIterable($results);
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
    public function populateOneToOne(&$obj)
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
                $mapper = new $mapper($this->db);

                $value = $mapper->get($reflectionProperty->getValue($obj));
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
    public function populateAbstract(array $result, $obj)
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
    public function get($primaryKey, int $relations = RelationType::ALL, $fill = null)
    {
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

            $obj[$value] = $this->populate($this->getRaw($value), $toFill);
        }

        $this->fillRelations($obj, $relations);

        $this->clear();

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
    public function getAll(int $relations = RelationType::ALL)
    {
        $obj = $this->populateIterable($this->getAllRaw());
        $this->fillRelations($obj, $relations);
        $this->clear();

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
    public function listResults(Builder $query)
    {
        $sth = $this->db->con->prepare($query->toSql());
        $sth->execute();

        return $this->populateIterable($sth->fetchAll(\PDO::FETCH_ASSOC));
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
    public function getNewest(int $limit = 1, Builder $query = null, int $relations = RelationType::ALL)
    {
        $query = $query ?? new Builder($this->db);
        $query->prefix($this->db->getPrefix())
            ->select('*')
            ->from(static::$table)
            ->limit($limit); /* todo: limit is not working, setting this to 2 doesn't have any effect!!! */

        if (!empty(static::$createdAt)) {
            $query->orderBy(static::$table . '.' . static::$columns[static::$createdAt]['name'], 'DESC');
        } else {
            $query->orderBy(static::$table . '.' . static::$columns[static::$primaryField]['name'], 'DESC');
        }

        $sth = $this->db->con->prepare($query->toSql());
        $sth->execute();

        $results = $sth->fetchAll(\PDO::FETCH_ASSOC);
        $obj     = $this->populateIterable(is_bool($results) ? [] : $results);

        $this->fillRelations($obj, $relations);
        $this->clear();

        return $obj;

    }

    /**
     * Get all by custom query.
     *
     * @param Builder $query     Query
     * @param bool    $relations Relations
     *
     * @return array
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getAllByQuery(Builder $query, bool $relations = true) : array
    {
        $sth = $this->db->con->prepare($query->toSql());
        $sth->execute();

        $results = $sth->fetchAll(\PDO::FETCH_ASSOC);
        $results = is_bool($results) ? [] : $results;

        $obj = $this->populateIterable($results);
        $this->fillRelations($obj, $relations);
        $this->clear();

        return $obj;
    }

    /**
     * Get random object
     *
     * @param int $relations Relations type
     *
     * @return array
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getRandom(int $relations = RelationType::ALL)
    {
        // todo: implement
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
    public function fillRelations(array &$obj, int $relations = RelationType::ALL)
    {
        $hasMany     = count(static::$hasMany) > 0;
        $hasOne      = count(static::$hasOne) > 0;
        $isExtending = count(static::$isExtending) > 0;

        if (($relations !== RelationType::NONE && ($hasMany || $hasOne)) || $isExtending) {
            foreach ($obj as $key => $value) {
                if ($isExtending) {
                    $this->populateExtending($obj[$key], $relations);
                }

                /* loading relations from relations table and populating them and then adding them to the object */
                if ($relations !== RelationType::NONE) {
                    if ($hasMany) {
                        $this->populateManyToMany($this->getManyRaw($key, $relations), $obj[$key]);
                    }

                    if ($hasOne) {
                        $this->populateOneToOne($obj[$key]);
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
    public function getRaw($primaryKey) : array
    {
        $query = new Builder($this->db);
        $query->prefix($this->db->getPrefix())
            ->select('*')
            ->from(static::$table)
            ->where(static::$table . '.' . static::$primaryField, '=', $primaryKey);

        $sth = $this->db->con->prepare($query->toSql());
        $sth->execute();

        $results = $sth->fetch(\PDO::FETCH_ASSOC);

        // todo: implement getRawRelations() ?!!!!!

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
    public function getAllRaw() : array
    {
        $query = new Builder($this->db);
        $query->prefix($this->db->getPrefix())
            ->select('*')
            ->from(static::$table);

        $sth = $this->db->con->prepare($query->toSql());
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
    public function getManyRaw($primaryKey, int $relations = RelationType::ALL) : array
    {
        $result = [];

        foreach (static::$hasMany as $member => $value) {
            if ($value['mapper'] !== $value['relationmapper']) {
                $query = new Builder($this->db);
                $query->prefix($this->db->getPrefix());

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

                $sth = $this->db->con->prepare($query->toSql());
                $sth->execute();
                $result[$member] = $sth->fetchAll(\PDO::FETCH_COLUMN);
            } else {
                $result[$member] = false;
            }
        }

        return $result;
    }

}
