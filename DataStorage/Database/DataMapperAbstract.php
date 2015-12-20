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
     * @var \bool
     * @since 1.0.0
     */
    protected static $overwrite = true;

    /**
     * Primary field name.
     *
     * @var \string
     * @since 1.0.0
     */
    protected static $primaryField = '';

    /**
     * Primary field name.
     *
     * @var \string
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
     * @var \string[]
     * @since 1.0.0
     */
    protected static $hasMany = [];

    /**
     * Relations.
     *
     * @var \string[]
     * @since 1.0.0
     */
    protected static $ownsMany = [];

    /**
     * Relations.
     *
     * @var \string[]
     * @since 1.0.0
     */
    protected static $hasOne = [];

    /**
     * Extending relations.
     *
     * @var \string[]
     * @since 1.0.0
     */
    protected static $extends = [];

    /**
     * Relations.
     *
     * @var \string[]
     * @since 1.0.0
     */
    protected static $ownsOne = [];

    /**
     * Table.
     *
     * @var \string
     * @since 1.0.0
     */
    protected static $table = '';

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
     * Collect values from extension.
     *
     * @param mixed $class Current extended mapper
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    protected function extend($class)
    {
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
     * Update data.
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function update()
    {
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
        $query = new Builder($this->db);
        $query->prefix($this->db->getPrefix());

        return $query->select(...$columns)->from(static::$table);
    }

    /**
     * Create object in db.
     *
     * @param mixed $obj Object reference (gets filled with insert id)
     *
     * @return Builder
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function create(&$obj)
    {
        $query = new Builder($this->db);
        $query->prefix($this->db->getPrefix())
            ->into(static::$table);

        $reflectionClass = new \ReflectionClass(get_class($obj));
        $properties      = $reflectionClass->getProperties();

        foreach ($properties as $property) {
            foreach (static::$columns as $key => $column) {
                if ($column['internal'] === $property->getName()) {
                    $property->setAccessible(true);

                    $value = $property->getValue($obj);

                    if ($column['type'] === 'DateTime') {
                        $value = isset($value) ? $value->format('Y-m-d H:i:s') : null;
                    }

                    $query->insert($column['name'])
                          ->value($value);

                    // todo: do i have to reverse the accessibility or is there no risk involved here?

                    break;
                }
            }
        }

        $this->db->con->prepare($query->toSql())->execute();
        $objId = $this->db->con->lastInsertId();

        $reflectionProperty = $reflectionClass->getProperty('id');

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
     * Populate data.
     *
     * @param array $result Result set
     *
     * @return mixed
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function populate(array $result)
    {
        $class = get_class($this);
        $class = str_replace('Mapper', '', $class);
        $obj   = new $class();

        return $this->populateAbstract($result, $obj);
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
            if ($reflectionClass->hasProperty(static::$columns[$column]['internal'])) {
                $reflectionProperty = $reflectionClass->getProperty(static::$columns[$column]['internal']);

                if (!($accessible = $reflectionProperty->isPublic())) {
                    $reflectionProperty->setAccessible(true);
                }

                if (in_array(static::$columns[$column]['type'], ['string', 'int', 'float'])) {
                    settype($value, static::$columns[$column]['type']);
                    $reflectionProperty->setValue($obj, $value);
                } elseif (static::$columns[$column]['type'] === 'DateTime') {
                    $reflectionProperty->setValue($obj, new \DateTime($value));
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
     * Get newest.
     *
     * This will fall back to the insert id if no datetime column is present.
     *
     * @return mixed
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getNewest()
    {
        if (!isset(static::$createdAt) || !isset(static::$columns[static::$createdAt])) {
            throw new \BadMethodCallException('Method "' . __METHOD__ . '" is not supported.');
        }

        $query = new Builder($this->db);
        $query->prefix($this->db->getPrefix())
              ->select('*')
              ->from(static::$table)
              ->limit(1);

        if (isset(static::$createdAt)) {
            $query->orderBy(static::$table . '.' . static::$columns[static::$createdAt]['name'], 'DESC');
        } else {
            $query->orderBy(static::$table . '.' . static::$columns[static::$primaryField]['name'], 'DESC');
        }

        $sth = $this->db->con->prepare($query->toSql());
        $sth->execute();

        $results = $sth->fetch(\PDO::FETCH_ASSOC);

        return $this->populate(is_bool($results) ? [] : $results);

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
    public function get($primaryKey)
    {
        $obj = $this->populate($this->getRaw($primaryKey));

        if (isset($obj)) {
            return $obj;
        } else {
            $class       = get_class($this);
            $class       = str_replace('Mapper', '', $class);
            $class       = explode('\\', $class);
            $pos         = count($class) - 1;
            $class[$pos] = 'Null' . $class[$pos];
            $class       = implode('\\', $class);

            return $class;
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

        return is_bool($results) ? [] : $results;
    }

    /**
     * Get all by custom query.
     *
     * @param Builder $query Query
     *
     * @return array
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getAllByQuery(Builder $query) : array
    {
        $sth = $this->db->con->prepare($query->toSql());
        $sth->execute();

        $results = $sth->fetchAll(\PDO::FETCH_ASSOC);
        $results = is_bool($results) ? [] : $results;

        return $this->populateIterable($results);
    }

    public function getRelations($primaryKey) : array
    {
        $result = [];

        foreach (static::$hasMany as $member => $value) {
            if (!isset($value['mapper'])) {
                $query = new Builder($this->db);
                $query->prefix($this->db->getPrefix())
                      ->select($value['table'] . '.' . $value['src'])
                      ->from($value['table'])
                      ->where($value['table'] . '.' . $value['dst'], '=', $primaryKey);

                $sth = $this->db->con->prepare($query->toSql());
                $sth->execute();
                $result[$member] = $sth->fetchAll(\PDO::FETCH_ASSOC);
            }
        }

        return $result;
    }

    /**
     * Get object.
     *
     * @return array
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getAll()
    {
        return $this->populateIterable($this->getAllRaw());
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
     * Get primary field.
     *
     * @return \string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getPrimaryField() : \string
    {
        return static::$primaryField;
    }

    /**
     * Get main table.
     *
     * @return \string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getTable() : \string
    {
        return static::$table;
    }

}
