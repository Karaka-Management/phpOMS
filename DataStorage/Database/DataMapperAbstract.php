<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   phpOMS\DataStorage\Database
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\DataStorage\Database;

use phpOMS\DataStorage\Database\Connection\ConnectionAbstract;
use phpOMS\DataStorage\Database\Exception\InvalidMapperException;
use phpOMS\DataStorage\Database\Query\Builder;
use phpOMS\DataStorage\Database\Query\QueryType;
use phpOMS\DataStorage\DataMapperInterface;
use phpOMS\Utils\ArrayUtils;

/**
 * Datamapper for databases.
 *
 * DB, Cache, Session
 *
 * @package phpOMS\DataStorage\Database
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 *
 * @todo Orange-Management/phpOMS#220
 *  Use joins.
 *  The datamapper is not using any joins currently which could significantly improve the performance of the queries.
 *  Joins should be used for:
 *      * composite models (models built from multiple tables)
 *      * owns one
 *      * has one
 *      * belongs to one
 *      * conditionals
 *
 * @todo Orange-Management/phpOMS#222
 *  Implement conditionals.
 *  Conditionals are hardcoded or dynamically passed parameters based on which a model is found.
 *  The best example is the language of a model (e.g. news article), which is stored as foreign key in the model.
 *  With conditionals it's possible to reference other tables and also return elements based on the value in these tables/columns.
 *  Possible solution:
 *      1. join tables/columns which have conditionals
 *      2. perform the select based on the match
 *  ```php
 *  TestMapper::getByConditional(['l11n' => 'en']);
 *  ```
 *
 * @todo Orange-Management/phpOMS#73
 *  Implement extending
 *  Allow a data mapper to extend another data mapper.
 *  This way the object will be inserted first with one data mapper and the remaining fields will be filled by the extended data mapper.
 *  How can wen solve a mapper extending a mapper ... extending a mapper?
 *
 * @todo Orange-Management/phpOMS#102
 *  Solve N+1 problem
 *  Currently the datamapper generates separate queries for objects that have relationships defined.
 *  Most of these relationships could be defined MUCH smarter and reduce the amout of sub-queries e.g. selecting a task and all its comments.
 *  The get method accepts an array of keys but doesn't select them in bulk but one at a time.
 *
 * @todo Orange-Management/phpOMS#122
 *  Split/Refactor.
 *  Child extends parent. Parent creates GetMapper, CreateMapper etc.
 *  Example:
 *  ```User::get(...)```
 *  The get() function (defined in an abstract class) creates internally an instance of GetMapper.
 *  The GetMapper receives all information such as primaryField, columns etc internally from the get().
 *  This transfer of knowledge to the GetMapper could be done in the abstract class as a setup() function.
 *  Now all mappers are split. The overhead is one additional function call and the setup() function.
 *  Alternatively, think about using traits in the beginning.
 *
 * @todo Orange-Management/phpOMS#212
 *  Replace nested models which are represented as scalar/id with NullModel
 *  Currently there is a default limit on dependency nesting when you request a model from the database.
 *  This means a model may have a model as member and that model in return also has some model as member and so on.
 *  In order to prevent very deep nesting either the default nesting level is used to limit the amount of nesting or the user can specify a nesting depth.
 *  Once the lowest nesting level is reached the mapper only stores the id in the member variable and NOT the model.
 *  As a result the member variable can be of type null, int (= primary key of the model), or the model type.
 *  This results in many special cases which a coder may has to consider.
 *  It might make sense to only store null and the model in the member variable.
 *  In order to still restrict the nesting the mapper could create a null model and only populate the id.
 *  This could reduce the complexity for the user and simplify the use cases.
 *  Additionally, it would now be possible to type hint the return value of many getter functions ?NullModelName.
 *  If this gets implemented we also need to adjust some setter functions.
 *  Many setter functions allow to only specify a id of the model.
 *  Either this needs to be prevented and a Null model needs to be provided (all null models must have a __construct(int $id = 0) function which allows to pass the id) or the setter function needs to create the null model based on the id.
 *  Implementing the above mentioned things will take some time but could improve the simplicity and overall code quality by a lot (at least from my personal opinion).
 *
 * @todo Orange-Management/phpOMS#213 & Orange-Management/phpOMS#224
 *  Implement composite models
 *  All references such as ownsOne, hasMany etc. are based on the mappers for these objects. It should be possible to define single columns only.
 *  One example where this could be useful is the Address/Localization model.
 *  In here the country is stored by ID but you probably don't want to load an entire object and only the country name from the country table.
 *
 * @todo Orange-Management/Modules#99
 *  Use binds
 *  Currently databinds are not used. Currently injections are possible.
 *
 * @todo Orange-Management/Modules#179
 *  Replace int models with NullModels
 *  In many cases int is allowed to represent another model if not the whole model is supposed to be loaded.
 *  This means we have to check for null, int and model type.
 *  Instead of using int the NullModel should be used which has a constructor that allows to define the int.
 *  As a result the datamapper has to be rewritten for the select and insert/update.
 *  The select needs to set the null model as value and the insert/update needs to extract the id from the null and ignore all other empty values from the null model which obviously are the default values.
 */
class DataMapperAbstract implements DataMapperInterface
{
    /**
     * Database connection.
     *
     * @var ConnectionAbstract
     * @since 1.0.0
     */
    protected static ConnectionAbstract $db;

    /**
     * Overwriting extended values.
     *
     * @var bool
     * @since 1.0.0
     */
    protected static bool $overwrite = true;

    /**
     * Primary field name.
     *
     * @var string
     * @since 1.0.0
     */
    protected static string $primaryField = '';

    /**
     * Autoincrement primary field.
     *
     * @var bool
     * @since 1.0.0
     */
    protected static bool $autoincrement = true;

    /**
     * Primary field name.
     *
     * @var string
     * @since 1.0.0
     */
    protected static string $createdAt = '';

    /**
     * Language
     *
     * @var string
     * @since 1.0.0
     */
    protected static string $languageField = '';

    /**
     * Columns.
     *
     * @var array<string, array{name:string, type:string, internal:string, autocomplete?:bool, readonly?:bool, writeonly?:bool, annotations?:array}>
     * @since 1.0.0
     */
    protected static array $columns = [];

    /**
     * Conditional.
     *
     * Most often used for localizations
     *
     * @var array<string, array<string, string>>
     * @since 1.0.0
     */
    protected static array $conditionals = [];

    /**
     * Has many relation.
     *
     * @var array<string, array>
     * @since 1.0.0
     */
    protected static array $hasMany = [];

    /**
     * Relations.
     *
     * Relation is defined in current mapper
     *
     * @var array<string, array{mapper:string, self:string, by?:string}>
     * @since 1.0.0
     */
    protected static array $ownsOne = [];

    /**
     * Belongs to.
     *
     * @var array<string, array{mapper:string, self:string}>
     * @since 1.0.0
     */
    protected static array $belongsTo = [];

    /**
     * Table.
     *
     * @var string
     * @since 1.0.0
     */
    protected static string $table = '';

    /**
     * Model to use by the mapper.
     *
     * @var string
     * @since 1.0.0
     */
    protected static string $model = '';

    /**
     * Fields to load.
     *
     * @var array[]
     * @since 1.0.0
     */
    protected static array $fields = [];

    /**
     * Initialized objects for cross reference to reduce initialization costs
     *
     * @var array[]
     * @since 1.0.0
     */
    protected static array $initObjects = [];

    /**
     * Initialized arrays for cross reference to reduce initialization costs
     *
     * @var array[]
     * @since 1.0.0
     */
    protected static array $initArrays = [];

    /**
     * Highest mapper to know when to clear initialized objects
     *
     * @var null|string
     * @since 1.0.0
     */
    protected static ?string $parentMapper = null;

    /**
     * Extended value collection.
     *
     * @var array
     * @since 1.0.0
     */
    protected static array $collection = [
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

    /**
     * Set database connection.
     *
     * @param ConnectionAbstract $con Database connection
     *
     * @return void
     *
     * @since 1.0.0
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
     * @since 1.0.0
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
     * @since 1.0.0
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
     * @since 1.0.0
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
     * @since 1.0.0
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
     * @since 1.0.0
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
     * @since 1.0.0
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
     *
     * @return mixed
     *
     * @since 1.0.0
     */
    public static function create($obj, int $relations = RelationType::ALL)
    {
        self::extend(__CLASS__);

        if (!isset($obj) || self::isNullModel($obj)) {
            return null;
        }

        $refClass = new \ReflectionClass($obj);

        if (!empty($id = self::getObjectId($obj, $refClass)) && static::$autoincrement) {
            $objId = $id;
        } else {
            $objId = self::createModel($obj, $refClass);
            self::setObjectId($refClass, $obj, $objId);
        }

        if ($relations === RelationType::ALL) {
            self::createHasMany($refClass, $obj, $objId);
            self::createConditionals($refClass, $obj, $objId);
        }

        return $objId;
    }

    /**
     * Create conditionals
     *
     * @param \ReflectionClass $refClass Reflection class
     * @param object           $obj      Object to create
     * @param mixed            $objId    Id to set
     *
     * @return void
     *
     * @since 1.0.0
     */
    private static function createConditionals(\ReflectionClass $refClass, object $obj, $objId): void
    {
    }

    /**
     * Create object in db.
     *
     * @param array $obj       Object reference (gets filled with insert id)
     * @param int   $relations Create all relations as well
     *
     * @return mixed
     *
     * @since 1.0.0
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
            self::createConditionalsArray($obj, $objId);
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
     * @since 1.0.0
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
                    $path   = \substr($column['internal'], \stripos($column['internal'], '/') + 1);
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

        try {
            self::$db->con->prepare($query->toSql())->execute();
        } catch (\Throwable $t) {
            \var_dump($t->getMessage());
            \var_dump($query->toSql());
            return -1;
        }

        $objId = self::$db->con->lastInsertId();
        \settype($objId, static::$columns[static::$primaryField]['type']);

        return $objId;
    }

    /**
     * Create conditionals
     *
     * @param array $obj   Object to create
     * @param mixed $objId Id to set
     *
     * @return void
     *
     * @since 1.0.0
     */
    private static function createConditionalsArray(array &$obj, $objId): void
    {
    }

    /**
     * Create base model.
     *
     * @param array $obj Model to create
     *
     * @return mixed
     *
     * @since 1.0.0
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
            if (\stripos($column['internal'], '/') === 0) {
                $path = \ltrim($column['internal'], '/');
            }

            $property = ArrayUtils::getArray($column['internal'], $obj, '/');

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
     * @since 1.0.0
     */
    public static function getObjectId(object $obj, \ReflectionClass $refClass = null)
    {
        $refClass ??= new \ReflectionClass($obj);
        $refProp    = $refClass->getProperty(static::$columns[static::$primaryField]['internal']);

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
     * @since 1.0.0
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
     * @since 1.0.0
     */
    public static function createRelation(string $member, $id1, $id2) : bool
    {
        if (!isset(static::$hasMany[$member]) || !isset(static::$hasMany[$member]['self'])) {
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
     * @since 1.0.0
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

            foreach ($values as $key => $value) {
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
                /** @var string $table */
                /** @var array $columns */
                if (!isset(static::$hasMany[$propertyName]['self'])) {
                    $relProperty = $relReflectionClass->getProperty($mapper::$columns[static::$hasMany[$propertyName]['external']]['internal']);

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
     * @since 1.0.0
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
                    && isset($mapper::$columns[static::$hasMany[$propertyName]['external']])
                ) {
                    $value[$mapper::$columns[static::$hasMany[$propertyName]['external']]['internal']] = $objId;
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
     * @since 1.0.0
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
     * @since 1.0.0
     */
    private static function createOwnsOneArray(string $propertyName, array &$obj)
    {
        $mapper     = static::$ownsOne[$propertyName]['mapper'];
        $primaryKey = $obj[static::$columns[static::$primaryField]['internal']];

        if (empty($primaryKey)) {
            return $mapper::createArray($obj);
        }

        return $primaryKey;
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
     * @since 1.0.0
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
     * @since 1.0.0
     */
    private static function createBelongsToArray(string $propertyName, array $obj)
    {
        /** @var string $mapper */
        $mapper     = static::$belongsTo[$propertyName]['mapper'];
        $primaryKey = $obj[static::$columns[static::$primaryField]['internal']];

        if (empty($primaryKey)) {
            return $mapper::createArray($obj);
        }

        return $primaryKey;
    }

    /**
     * Create relation table entry
     *
     * In case of a many to many relation the relation has to be stored in a relation table
     *
     * @param string $propertyName Property name to initialize
     * @param array  $objsIds      Object ids to insert (can also be the object itself)
     * @param mixed  $objId        Model to reference
     *
     * @return void
     *
     * @since 1.0.0
     */
    private static function createRelationTable(string $propertyName, array $objsIds, $objId) : void
    {
        /** @var string $table */
        if (!empty($objsIds) && isset(static::$hasMany[$propertyName]['self'])) {
            $relQuery = new Builder(self::$db);
            $relQuery->prefix(self::$db->getPrefix())
                ->into(static::$hasMany[$propertyName]['table'])
                ->insert(static::$hasMany[$propertyName]['self'], static::$hasMany[$propertyName]['external']);

            foreach ($objsIds as $key => $src) {
                if (\is_object($src)) {
                    $mapper = \get_class($src) . 'Mapper';
                    $src    = $mapper::getObjectId($src);
                }

                $relQuery->values($src, $objId);
            }

            try {
                self::$db->con->prepare($relQuery->toSql())->execute();
            } catch (\Throwable $e) {
                \var_dump($e->getMessage());
                \var_dump($relQuery->toSql());
            }
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
     * @since 1.0.0
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
            return $value === null ? null : $value->format('Y-m-d H:i:s');
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
     * @since 1.0.0
     */
    private static function updateHasMany(\ReflectionClass $refClass, object $obj, $objId, int $relations = RelationType::ALL, $depth = 1) : void
    {
        $objsIds = [];

        foreach (static::$hasMany as $propertyName => $rel) {
            if ($rel['readonly'] ?? false === true) {
                continue;
            }

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
                    && isset($mapper::$columns[static::$hasMany[$propertyName]['external']])
                ) {
                    $relProperty = $relReflectionClass->getProperty($mapper::$columns[static::$hasMany[$propertyName]['external']]['internal']);

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
     * @since 1.0.0
     */
    private static function updateHasManyArray(array &$obj, $objId, int $relations = RelationType::ALL, $depth = 1) : void
    {
        $objsIds = [];

        foreach (static::$hasMany as $propertyName => $rel) {
            if ($rel['readonly'] ?? false === true) {
                continue;
            }

            $values = $obj[$propertyName];

            if (!isset(static::$hasMany[$propertyName]['mapper'])) {
                throw new InvalidMapperException();
            }

            /** @var string $mapper */
            $mapper                 = static::$hasMany[$propertyName]['mapper'];
            $objsIds[$propertyName] = [];

            foreach ($values as $key => &$value) {
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
                    && isset($mapper::$columns[static::$hasMany[$propertyName]['external']])
                ) {
                    $value[$mapper::$columns[static::$hasMany[$propertyName]['external']]['internal']] = $objId;
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
     * @since 1.0.0
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
     * @since 1.0.0
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
                    ->where(static::$hasMany[$propertyName]['table'] . '.' . static::$hasMany[$propertyName]['self'], '=', $src)
                    ->where(static::$hasMany[$propertyName]['table'] . '.' . static::$hasMany[$propertyName]['external'], '=', $objId, 'and');

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
     * @param mixed  $obj          Object to update
     * @param int    $relations    Create all relations as well
     * @param int    $depth        Depth of relations to update (default = 1 = none)
     *
     * @return mixed
     *
     * @since 1.0.0
     */
    private static function updateOwnsOne(string $propertyName, $obj, int $relations = RelationType::ALL, int $depth = 1)
    {
        if (!\is_object($obj)) {
            return $obj;
        }

        /** @var string $mapper */
        $mapper = static::$ownsOne[$propertyName]['mapper'];

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
     * @since 1.0.0
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
     * @since 1.0.0
     */
    private static function updateBelongsTo(string $propertyName, $obj, int $relations = RelationType::ALL, int $depth = 1)
    {
        if (!\is_object($obj)) {
            return $obj;
        }

        /** @var string $mapper */
        $mapper = static::$belongsTo[$propertyName]['mapper'];

        return $mapper::update($obj, $relations, $depth);
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
     * @since 1.0.0
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
     * @since 1.0.0
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
                || ($column['readonly'] ?? false === true)
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

                /**
                 * @todo Orange-Management/phpOMS#232
                 *  If a model gets updated all it's relations are also updated.
                 *  This should be prevented if the relations didn't change.
                 *  No solution yet.
                 */
                $query->set([static::$table . '.' . $column['name'] => $value]);
            } elseif (isset(static::$belongsTo[$propertyName])) {
                $id    = self::updateBelongsTo($propertyName, $property->getValue($obj), $relations, $depth);
                $value = self::parseValue($column['type'], $id);

                /**
                 * @todo Orange-Management/phpOMS#232
                 *  If a model gets updated all it's relations are also updated.
                 *  This should be prevented if the relations didn't change.
                 *  No solution yet.
                 */
                $query->set([static::$table . '.' . $column['name'] => $value]);
            } elseif ($column['name'] !== static::$primaryField) {
                $tValue = $property->getValue($obj);
                if (\stripos($column['internal'], '/') !== false) {
                    $path   = \substr($column['internal'], \stripos($column['internal'], '/') + 1);
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
     * Update conditional values
     *
     * @param object           $obj       Object to update
     * @param mixed            $objId     Object id
     * @param \ReflectionClass $refClass  Reflection of the object
     * @param int              $relations Relations to update
     * @param int              $depth     Depths to update
     *
     * @return void
     *
     * @since 1.0.0
     */
    private static function updateConditionals(object $obj, $objId, \ReflectionClass $refClass = null, int $relations = RelationType::ALL, int $depth = 1) : void
    {
        foreach (static::$conditionals as $table => $conditional) {
            $query = new Builder(self::$db);
            $query->prefix(self::$db->getPrefix())
                ->update($table)
                ->where($table . '.' . $conditional['external'], '=', $objId);

            foreach ($conditional['columns'] as $key => $column) {
                $propertyName = \stripos($column['internal'], '/') !== false ? \explode('/', $column['internal'])[0] : $column['internal'];

                $refClass = $refClass ?? new \ReflectionClass($obj);
                $property = $refClass->getProperty($propertyName);

                if (!($isPublic = $property->isPublic())) {
                    $property->setAccessible(true);
                }

                if ($column['name'] !== $conditional['external']) {
                    $tValue = $property->getValue($obj);
                    if (\stripos($column['internal'], '/') !== false) {
                        $path   = \substr($column['internal'], \stripos($column['internal'], '/') + 1);
                        $tValue = ArrayUtils::getArray($path, $tValue, '/');
                    }

                    $value = self::parseValue($column['type'], $tValue);

                    $query->set([$table . '.' . $column['name'] => $value]);
                }

                if (!$isPublic) {
                    $property->setAccessible(false);
                }
            }

            self::$db->con->prepare($query->toSql())->execute();
        }
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
     * @since 1.0.0
     */
    private static function updateModelArray(array $obj, $objId, int $relations = RelationType::ALL, int $depth = 1) : void
    {
        $query = new Builder(self::$db);
        $query->prefix(self::$db->getPrefix())
            ->update(static::$table)
            ->where(static::$table . '.' . static::$primaryField, '=', $objId);

        foreach (static::$columns as $key => $column) {
            if (isset(static::$hasMany[$key])
                || ($column['readonly'] ?? false === true)
            ) {
                continue;
            }

            $path = $column['internal'];
            if (\stripos($column['internal'], '/') !== false) {
                $path = \substr($column['internal'], \stripos($column['internal'], '/') + 1);
                //$path = \ltrim($column['internal'], '/');
            }

            $property = ArrayUtils::getArray($column['internal'], $obj, '/');

            if (isset(static::$ownsOne[$path])) {
                $id    = self::updateOwnsOneArray($column['internal'], $property, $relations, $depth);
                $value = self::parseValue($column['type'], $id);

                /**
                 * @todo Orange-Management/phpOMS#232
                 *  If a model gets updated all it's relations are also updated.
                 *  This should be prevented if the relations didn't change.
                 *  No solution yet.
                 */
                $query->set([static::$table . '.' . $column['name'] => $value]);
            } elseif (isset(static::$belongsTo[$path])) {
                $id    = self::updateBelongsToArray($column['internal'], $property, $relations, $depth);
                $value = self::parseValue($column['type'], $id);

                /**
                 * @todo Orange-Management/phpOMS#232
                 *  If a model gets updated all it's relations are also updated.
                 *  This should be prevented if the relations didn't change.
                 *  No solution yet.
                 */
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
     * @param array $obj       Model to update
     * @param mixed $objId     Model id
     * @param int   $relations Create all relations as well
     * @param int   $depth     Depth of relations to update (default = 1 = none)
     *
     * @return void
     *
     * @since 1.0.0
     */
    private static function updateConditionalsArray(array $obj, $objId, int $relations = RelationType::ALL, int $depth = 1) : void
    {
        foreach (static::$conditionals as $table => $conditional) {
            $query = new Builder(self::$db);
            $query->prefix(self::$db->getPrefix())
                ->update($table)
                ->where($table . '.' . $conditional['external'], '=', $objId);

            foreach ($conditional['columns'] as $key => $column) {
                $property = ArrayUtils::getArray($column['internal'], $obj, '/');

                if ($column['name'] !== $conditional['external']) {
                    $value = self::parseValue($column['type'], $property);

                    $query->set([$table . '.' . $column['name'] => $value]);
                }
            }

            self::$db->con->prepare($query->toSql())->execute();
        }
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
     * @since 1.0.0
     */
    public static function update($obj, int $relations = RelationType::ALL, int $depth = 1)
    {
        self::extend(__CLASS__);

        if (!isset($obj) || self::isNullModel($obj)) {
            return null;
        }

        $refClass = new \ReflectionClass($obj);
        $objId    = self::getObjectId($obj, $refClass);

        if ($depth < 1) {
            return $objId;
        }

        self::addInitialized(static::class, $objId, $obj);

        if ($relations === RelationType::ALL) {
            self::updateHasMany($refClass, $obj, $objId, --$depth);
            self::updateConditionals($obj, $objId, $refClass);
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
     * @since 1.0.0
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
            self::updateConditionalsArray($obj, $objId);
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
     * @since 1.0.0
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

                /**
                 * @todo Orange-Management/phpOMS#233
                 *  On delete the relations and relation tables need to be deleted first
                 *  The exception is of course the belongsTo relation.
                 */
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
     * @since 1.0.0
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
     * @since 1.0.0
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
     * Delete conditional values
     *
     * @param mixed $key Key to delete
     *
     * @since 1.0.0
     */
    private static function deleteConditionals($key) : void
    {
        foreach (static::$conditionals as $table => $conditional) {
            $query = new Builder(self::$db);
            $query->prefix(self::$db->getPrefix())
                ->delete()
                ->from($table)
                ->where(static::$conditionals[$table]['external'], '=', $key);

            foreach (static::$conditionals[$table]['filter'] as $column => $filter) {
                if ($filter !== null) {
                    $query->where($column, '=', $filter);
                }
            }

            self::$db->con->prepare($query->toSql())->execute();
        }
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
     * @since 1.0.0
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

                /**
                 * @todo Orange-Management/phpOMS#233
                 *  On delete the relations and relation tables need to be deleted first
                 *  The exception is of course the belongsTo relation.
                 */
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
     * @since 1.0.0
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
            self::deleteConditionals($objId);
        }

        self::deleteModel($obj, $objId, $relations, $refClass);

        return $objId;
    }

    /**
     * @todo Orange-Management/phpOMS#221
     *  Create the delete functionality for arrays (deleteArray, deleteArrayModel).
     */

    /**
     * Populate data.
     *
     * @param array $result Result set
     *
     * @return array
     *
     * @since 1.0.0
     */
    public static function populateIterable(array $result) : array
    {
        $row = [];

        foreach ($result as $element) {
            $toFill = empty($element) ? self::createNullModel() : self::createBaseModel();

            if (isset($element[static::$primaryField])) {
                $row[$element[static::$primaryField]] = self::populateAbstract($element, $toFill, static::$columns);
            } else {
                $row[]  = self::populateAbstract($element, $toFill, static::$columns);
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
     * @since 1.0.0
     */
    public static function populateIterableArray(array $result) : array
    {
        $row = [];

        foreach ($result as $element) {
            if (isset($element[static::$primaryField])) {
                $row[$element[static::$primaryField]] = self::populateAbstractArray($element, [], static::$columns);
            } else {
                $row[] = self::populateAbstractArray($element, [], static::$columns);
            }
        }

        return $row;
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
     * @since 1.0.0
     */
    public static function populateManyToMany(array $result, &$obj, int $depth = 3) : void
    {
        $refClass = new \ReflectionClass($obj);

        foreach ($result as $member => $values) {
            if (!empty($values) && $refClass->hasProperty($member)) {
                /** @var string $mapper */
                $mapper  = static::$hasMany[$member]['mapper'];
                $refProp = $refClass->getProperty($member);

                if (!($accessible = $refProp->isPublic())) {
                    $refProp->setAccessible(true);
                }

                if (!isset(static::$hasMany[$member]['by'])) {
                    $objects = $mapper::get($values, RelationType::ALL, null, $depth);
                } else {
                    $objects = $mapper::getBy($values, static::$hasMany[$member]['by'], RelationType::ALL, null, $depth);
                }

                $refProp->setValue($obj, !\is_array($objects) ? [$mapper::getObjectId($objects) => $objects] : $objects);

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
     * @since 1.0.0
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
     * @param string $member Member name
     * @param mixed  $id     Reference Id for the owned model
     * @param int    $depth  Relation depth
     *
     * @return mixed
     *
     * @todo: in the future we could pass not only the $id ref but all of the data as a join!!! and save an additional select!!!
     *
     * @since 1.0.0
     */
    public static function populateOwnsOne(string $member, $id, int $depth = 3)
    {
        $mapper = static::$ownsOne[$member]['mapper'];

        if (!isset(static::$ownsOne[$member]['by'])) {
            $value = self::getInitialized($mapper, $id) ?? $mapper::get($id, RelationType::ALL, null, $depth);
        } else {
            $value = $mapper::getBy($id, static::$ownsOne[$member]['by'], RelationType::ALL, null, $depth);
        }

        return $value;
    }

    /**
     *
     * @return void
     *
     * @todo   do this in the getRaw() part as a join. check if has conditionals and then join the data an then everything can be done in the getModel function.
     *
     * @since 1.0.0
     */
    public static function getConditionals($key, string $table) : array
    {
        $query = new Builder(self::$db);
        $query->prefix(self::$db->getPrefix())
            ->select(...static::$conditionals[$table]['columns'])
            ->from($table)
            ->where(static::$conditionals[$table]['external'], '=', $key);

        foreach (static::$conditionals[$table]['filter'] as $column => $filter) {
            if ($filter !== null) {
                $query->where($column, '=', $filter);
            }
        }

        $sth = self::$db->con->prepare($query->toSql());
        $sth->execute();

        $results = $sth->fetch(\PDO::FETCH_ASSOC);

        return $results === false ? [] : $results;
    }

    /**
     * Populate data.
     *
     * @param string $member Member name
     * @param mixed  $id     Reference Id for the owned model
     * @param int    $depth  Relation depth
     *
     * @return void
     *
     * @since 1.0.0
     */
    public static function populateOwnsOneArray(string $member, $id, int $depth = 3) : array
    {
        $mapper = static::$ownsOne[$member]['mapper'];

        return self::getInitializedArray($mapper, $id) ?? $mapper::getArray($id, RelationType::ALL, $depth);
    }

    /**
     * Populate data.
     *
     * @param string $member Member name
     * @param mixed  $id     Reference Id for the owned model
     * @param int    $depth  Relation depth
     *
     * @return mixed
     *
     * @todo: in the future we could pass not only the $id ref but all of the data as a join!!! and save an additional select!!!
     *
     * @since 1.0.0
     */
    public static function populateBelongsTo(string $member, $id, int $depth = 3)
    {
        $mapper = static::$belongsTo[$member]['mapper'];

        return self::getInitialized($mapper, $id) ?? $mapper::get($id, RelationType::ALL, null, $depth);
    }

    /**
     * Populate data.
     *
     * @param string $member Member name
     * @param mixed  $id     Reference Id for the owned model
     * @param int    $depth  Relation depth
     *
     * @return void
     *
     * @since 1.0.0
     */
    public static function populateBelongsToArray(string $member, $id, int $depth = 3) : array
    {
        $mapper = static::$belongsTo[$member]['mapper'];

        return self::getInitializedArray($mapper, $id) ?? $mapper::getArray($id, RelationType::ALL, $depth);
    }

    /**
     * Populate data.
     *
     * @param array $result  Query result set
     * @param mixed $obj     Object to populate
     * @param array $columns Mapper columns (@todo: maybe remove because $columns === static::$columns)
     * @param int   $depth   Relation depth
     *
     * @return mixed
     *
     * @throws \UnexpectedValueException
     *
     * @since 1.0.0
     */
    public static function populateAbstract(array $result, $obj, array $columns, int $depth = 3)
    {
        $refClass = new \ReflectionClass($obj);

        foreach ($result as $column => $value) {
            if (!isset($columns[$column]['internal'])) {
                continue;
            }

            $hasPath   = false;
            $aValue    = [];
            $arrayPath = '';

            if (\stripos($columns[$column]['internal'], '/') !== false) {
                $hasPath = true;
                $path    = \explode('/', $columns[$column]['internal']);
                $refProp = $refClass->getProperty($path[0]);

                if (!($accessible = $refProp->isPublic())) {
                    $refProp->setAccessible(true);
                }

                \array_shift($path);
                $arrayPath = \implode('/', $path);
                $aValue    = $refProp->getValue($obj);
            } else {
                $refProp = $refClass->getProperty($columns[$column]['internal']);

                if (!($accessible = $refProp->isPublic())) {
                    $refProp->setAccessible(true);
                }
            }

            if (isset(static::$ownsOne[$columns[$column]['internal']])) {
                $value = self::populateOwnsOne($columns[$column]['internal'], $value, $depth - 1);

                $refProp->setValue($obj, $value);
            } elseif (isset(static::$belongsTo[$columns[$column]['internal']])) {
                $value = self::populateBelongsTo($columns[$column]['internal'], $value, $depth - 1);

                $refProp->setValue($obj, $value);
            } elseif (\in_array($columns[$column]['type'], ['string', 'int', 'float', 'bool'])) {
                if ($value !== null || $refProp->getValue($obj) !== null) {
                    \settype($value, $columns[$column]['type']);
                }

                if ($hasPath) {
                    $value = ArrayUtils::setArray($arrayPath, $aValue, $value, '/', true);
                }

                $refProp->setValue($obj, $value);
            } elseif ($columns[$column]['type'] === 'DateTime') {
                $value = $value === null ? null : new \DateTime($value);
                if ($hasPath) {
                    $value = ArrayUtils::setArray($arrayPath, $aValue, $value, '/', true);
                }

                $refProp->setValue($obj, $value);
            } elseif ($columns[$column]['type'] === 'Json') {
                if ($hasPath) {
                    $value = ArrayUtils::setArray($arrayPath, $aValue, $value, '/', true);
                }

                $refProp->setValue($obj, \json_decode($value, true));
            } elseif ($columns[$column]['type'] === 'Serializable') {
                $member = $refProp->getValue($obj);
                $member->unserialize($value);
            } else {
                throw new \UnexpectedValueException('Value "' . $columns[$column]['type'] . '" is not supported.');
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
     * @param array $result  Query result set
     * @param array $obj     Object to populate
     * @param array $columns Mapper columns (@todo: maybe remove because $columns === static::$columns)
     * @param int   $depth   Relation depth
     *
     * @return array
     *
     * @since 1.0.0
     */
    public static function populateAbstractArray(array $result, array $obj, array $columns, int $depth = 3) : array
    {
        foreach ($result as $column => $value) {
            if (isset($columns[$column]['internal'])) {
                $path = $columns[$column]['internal'];
                if (\stripos($path, '/') !== false) {
                    $path = \explode('/', $path);

                    \array_shift($path);
                    $path = \implode('/', $path);
                }

                if (isset(static::$ownsOne[$columns[$column]['internal']])) {
                    $value = self::populateOwnsOneArray($columns[$column]['internal'], $value, $depth - 1);
                } elseif (isset(static::$belongsTo[$columns[$column]['internal']])) {
                    $value = self::populateBelongsToArray($columns[$column]['internal'], $value, $depth - 1);
                } elseif (\in_array($columns[$column]['type'], ['string', 'int', 'float', 'bool'])) {
                    \settype($value, $columns[$column]['type']);
                } elseif ($columns[$column]['type'] === 'DateTime') {
                    $value = $value === null ? null : new \DateTime($value);
                } elseif ($columns[$column]['type'] === 'Json') {
                    $value = \json_decode($value, true);
                }

                $obj = ArrayUtils::setArray($path, $obj, $value, '/', true);
            }
        }

        return $obj;
    }

    /**
     * Count the number of elements before a pivot element
     *
     * @param mixed       $pivot  Pivet id
     * @param null|string $column Name of the field in the model
     *
     * @return int
     *
     * @since 1.0.0
     */
    public static function countBeforePivot($pivot, string $column = null) : int
    {
        $query = new Builder(self::$db);
        $query->where(static::$table . '.' . ($column !== null ? self::getColumnByMember($column) : static::$primaryField), '<', $pivot);

        return self::count($query);
    }

    /**
     * Count the number of elements after a pivot element
     *
     * @param mixed       $pivot  Pivet id
     * @param null|string $column Name of the field in the model
     *
     * @return int
     *
     * @since 1.0.0
     */
    public static function countAfterPivot($pivot, string $column = null) : int
    {
        $query = new Builder(self::$db);
        $query->where(static::$table . '.' . ($column !== null ? self::getColumnByMember($column) : static::$primaryField), '>', $pivot);

        return self::count($query);
    }

    /**
     * Count the number of elements
     *
     * @param null|Builder $query Builder
     *
     * @return int
     *
     * @since 1.0.0
     */
    public static function count(Builder $query = null) : int
    {
        $query = $query ?? new Builder(self::$db);
        $query->prefix(self::$db->getPrefix())
            ->select('COUNT(*)')
            ->from(static::$table);

        return (int) $query->execute()->fetchColumn();
    }

    /**
     * Get objects for pagination
     *
     * @param mixed  $pivot     Pivot
     * @param string $column    Sort column/pivot column
     * @param int    $limit     Result limit
     * @param string $order     Order of the elements
     * @param int    $relations Load relations
     * @param mixed  $fill      Object to fill
     * @param int    $depth     Relation depth
     *
     * @return mixed
     *
     * @since 1.0.0
     */
    public static function getAfterPivot(
        $pivot,
        string $column = null,
        int $limit = 50,
        string $order = 'ASC',
        int $relations = RelationType::ALL,
        $fill = null, int $depth = 3)
    {
        $query = self::getQuery();
        $query->where(static::$table . '.' . ($column !== null ? self::getColumnByMember($column) : static::$primaryField), '>', $pivot)
            ->orderBy(static::$table . '.' . ($column !== null ? self::getColumnByMember($column) : static::$primaryField), $order)
            ->limit($limit);

        return self::getAllByQuery($query, $relations, $depth);
    }

    /**
     * Get objects for pagination
     *
     * @param mixed  $pivot     Pivot
     * @param string $column    Sort column/pivot column
     * @param int    $limit     Result limit
     * @param string $order     Order of the elements
     * @param int    $relations Load relations
     * @param mixed  $fill      Object to fill
     * @param int    $depth     Relation depth
     *
     * @return mixed
     *
     * @since 1.0.0
     */
    public static function getBeforePivot(
        $pivot,
        string $column = null,
        int $limit = 50,
        string $order = 'ASC',
        int $relations = RelationType::ALL,
        $fill = null, int $depth = 3)
    {
        $query = self::getQuery();
        $query->where(static::$table . '.' . ($column !== null ? self::getColumnByMember($column) : static::$primaryField), '<', $pivot)
            ->orderBy(static::$table . '.' . ($column !== null ? self::getColumnByMember($column) : static::$primaryField), $order)
            ->limit($limit);

        return self::getAllByQuery($query, $relations, $depth);
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
     * @todo Orange-Management/phpOMS#161
     *  Reconsider get*() parameter order
     *  Check if the parameter order of all of the get functions makes sense or if another order would be better.
     *  Especially the fill parameter probably should be swapped with the depth filter.
     *
     * @since 1.0.0
     */
    public static function get($primaryKey, int $relations = RelationType::ALL, $fill = null, int $depth = 3)
    {
        if ($depth < 1) {
            return self::createNullModel($primaryKey);
        }

        if (!isset(self::$parentMapper)) {
            self::$parentMapper = static::class;
        }

        self::extend(__CLASS__);

        $primaryKey = (array) $primaryKey;
        $fill       = (array) $fill;
        $obj        = [];
        $fCount     = empty($fill);
        $toFill     = null;

        foreach ($primaryKey as $key => $value) {
            if (self::isInitialized(static::class, $value)) {
                $obj[$value] = self::$initObjects[static::class][$value];
                continue;
            }

            $dbData = self::getRaw($value);

            if (!$fCount) {
                $toFill = \current($fill);
                \next($fill);
            } else {
                $toFill = empty($dbData) ? self::createNullModel() : self::createBaseModel();
            }

            $obj[$value] = $toFill;
            self::addInitialized(static::class, $value, $obj[$value]);

            $obj[$value] = self::populateAbstract($dbData, $toFill, static::$columns, $depth);

            if (\method_exists($obj[$value], 'initialize')) {
                $obj[$value]->initialize();
            }
        }

        self::fillRelations($obj, $relations, $depth - 1);
        self::clear();

        $countResulsts = \count($obj);

        if ($countResulsts === 0) {
            return self::createNullModel();
        } elseif ($countResulsts === 1) {
            return \reset($obj);
        }

        return $obj;
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
     * @since 1.0.0
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

            $obj[$value] = self::populateAbstractArray(self::getRaw($value), [], static::$columns, $depth);

            self::addInitializedArray(static::class, $value, $obj[$value]);
        }

        self::fillRelationsArray($obj, $relations, $depth - 1);
        self::clear();

        return \count($obj) === 1 ? \reset($obj) : $obj;
    }

    /**
     * Get object.
     *
     * @param mixed  $forKey    Key
     * @param string $for       The field that defines the for
     * @param int    $relations Load relations
     * @param mixed  $fill      Object to fill
     * @param int    $depth     Relation depth
     *
     * @return mixed
     *
     * @since 1.0.0
     */
    public static function getFor($forKey, string $for, int $relations = RelationType::ALL, $fill = null, int $depth = 3)
    {
        if ($depth < 1) {
            // @todo maybe wrong? because for !== this model
            // @todo maybe fill for value. this should be the correct column/member
            return self::createNullModel($forKey);
        }

        if (!isset(self::$parentMapper)) {
            self::$parentMapper = static::class;
        }

        self::extend(__CLASS__);

        $forKey = (array) $forKey;
        $obj    = [];

        foreach ($forKey as $key => $value) {
            $toLoad = isset(static::$hasMany[$for]) && static::$hasMany[$for]['self'] !== null
                ? self::getHasManyPrimaryKeys($value, $for)
                : self::getPrimaryKeysBy($value, self::getColumnByMember($for));

            $obj[$value] = self::get($toLoad, $relations, $fill, $depth);
        }

        $countResulsts = \count($obj);

        if ($countResulsts === 0) {
            return self::createNullModel();
        } elseif ($countResulsts === 1) {
            return \reset($obj);
        }

        return $obj;
    }

    /**
     * Get object.
     *
     * @param mixed  $byKey     Key
     * @param string $by        The field that defines the for
     * @param int    $relations Load relations
     * @param mixed  $fill      Object to fill
     * @param int    $depth     Relation depth
     *
     * @return mixed
     *
     * @since 1.0.0
     */
    public static function getBy($byKey, string $by, int $relations = RelationType::ALL, $fill = null, int $depth = 3)
    {
        if ($depth < 1) {
            // @todo: maybe fill null model with byValue
            return self::createNullModel();
        }

        if (!isset(self::$parentMapper)) {
            self::$parentMapper = static::class;
        }

        self::extend(__CLASS__);

        $byKey = (array) $byKey;
        $obj   = [];

        foreach ($byKey as $key => $value) {
            $toLoad = [];

            if (isset(static::$hasMany[$by]) && static::$hasMany[$by]['self'] !== null) {
                // todo: maybe wrong?!
                $toLoad = self::getHasManyPrimaryKeys($value, $by);
            } elseif (isset(static::$ownsOne[$by])) {
                $toLoad = self::getPrimaryKeysBy($value, self::getColumnByMember($by));
            }

            $obj[$value] = self::get($toLoad, $relations, $fill, $depth);
        }

        $countResulsts = \count($obj);

        if ($countResulsts === 0) {
            return self::createNullModel();
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
     * @since 1.0.0
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

            if (isset(static::$hasMany[$ref]) && static::$hasMany[$ref]['self'] !== null) {
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
     * @since 1.0.0
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
     * @since 1.0.0
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
     * @since 1.0.0
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
     * @since 1.0.0
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
     * @since 1.0.0
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
     * @since 1.0.0
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
     * @since 1.0.0
     */
    public static function fillRelations(array &$obj, int $relations = RelationType::ALL, int $depth = 3) : void
    {
        if ($depth < 1) {
            return;
        }

        if ($relations === RelationType::NONE) {
            return;
        }

        $hasMany         = !empty(static::$hasMany);
        $hasConditionals = !empty(static::$conditionals);

        if (!($hasMany || $hasConditionals)) {
            return;
        }

        foreach ($obj as $key => $value) {
            /* loading relations from relations table and populating them and then adding them to the object */
            if ($hasMany) {
                self::populateManyToMany(self::getHasManyRaw($key, $relations), $obj[$key], $depth);
            }

            if ($hasConditionals) {
                foreach (static::$conditionals as $table => $conditional) {
                    $obj[$key] = self::populateAbstract(self::getConditionals($key, $table), $obj[$key], $conditional['columns']);
                }
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
     * @since 1.0.0
     */
    public static function fillRelationsArray(array &$obj, int $relations = RelationType::ALL, int $depth = 3) : void
    {
        if ($depth < 1) {
            return;
        }

        if ($relations === RelationType::NONE) {
            return;
        }

        $hasMany         = !empty(static::$hasMany);
        $hasConditionals = !empty(static::$conditionals);

        if (!($hasMany || $hasConditionals)) {
            return;
        }

        if ($hasConditionals) {
            self::populateConditionalsArray();
        }

        foreach ($obj as $key => $value) {
            /* loading relations from relations table and populating them and then adding them to the object */
            if ($hasMany) {
                self::populateManyToManyArray(self::getHasManyRaw($key, $relations), $obj[$key], $depth);
            }

            if ($hasConditionals) {
                foreach (static::$conditionals as $table => $conditional) {
                    $obj[$key] = self::populateAbstractArray(self::getConditionals($key, $table), $obj[$key], $conditional['columns']);
                }
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
     * @since 1.0.0
     */
    public static function getRaw($primaryKey) : array
    {
        if ($primaryKey === null) {
            return [];
        }

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
     * @since 1.0.0
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
     * @since 1.0.0
     */
    public static function getHasManyPrimaryKeys($refKey, string $ref) : array
    {
        $query = new Builder(self::$db);
        $query->prefix(self::$db->getPrefix())
            ->select(static::$hasMany[$ref]['table'] . '.' . static::$hasMany[$ref]['external'])
            ->from(static::$hasMany[$ref]['table'])
            ->where(static::$hasMany[$ref]['table'] . '.' . static::$hasMany[$ref]['self'], '=', $refKey);

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
     * @since 1.0.0
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
     * @since 1.0.0
     */
    public static function getHasManyRaw($primaryKey, int $relations = RelationType::ALL) : array
    {
        $result = [];

        foreach (static::$hasMany as $member => $value) {
            if ($value['writeonly'] ?? false === true) {
                continue;
            }

            $query = new Builder(self::$db);
            $query->prefix(self::$db->getPrefix());

            if ($relations === RelationType::ALL) {
                /** @var string $primaryField */
                $src = $value['self'] ?? $value['mapper']::$primaryField;

                $query->select($value['table'] . '.' . $src)
                    ->from($value['table'])
                    ->where($value['table'] . '.' . $value['external'], '=', $primaryKey);
            } /*elseif ($relations === RelationType::NEWEST) {
                SELECT c.*, p1.*
                FROM customer c
                JOIN purchase p1 ON (c.id = p1.customer_id)
                LEFT OUTER JOIN purchase p2 ON (c.id = p2.customer_id AND
                    (p1.date < p2.date OR p1.date = p2.date AND p1.id < p2.id))
                WHERE p2.id IS NULL;
                                    $query->select(static::$table . '.' . static::$primaryField, $value['table'] . '.' . $value['self'])
                                          ->from(static::$table)
                                          ->join($value['table'])
                                          ->on(static::$table . '.' . static::$primaryField, '=', $value['table'] . '.' . $value['external'])
                                          ->leftOuterJoin($value['table'])
                                          ->on(new And('1', new And(new Or('d1', 'd2'), 'id')))
                                          ->where($value['table'] . '.' . $value['external'], '=', 'NULL');

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
     * @since 1.0.0
     */
    public static function getQuery(Builder $query = null) : Builder
    {
        $columns = [];
        foreach (static::$columns as $key => $values) {
            if ($values['writeonly'] ?? false === false) {
                $columns[] = $key;
            }
        }

        $query = $query ?? new Builder(self::$db);
        $query->prefix(self::$db->getPrefix())
            ->select(...$columns)
            ->from(static::$table);

        return $query;
    }

    /**
     * Get created at column
     *
     * @return string
     *
     * @since 1.0.0
     */
    public static function getCreatedAt() : string
    {
        return !empty(static::$createdAt) ? static::$createdAt : static::$primaryField;
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
     * @since 1.0.0
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
     * @since 1.0.0
     */
    private static function isInitialized(string $mapper, $id) : bool
    {
        return !empty($id) && isset(self::$initObjects[$mapper], self::$initObjects[$mapper][$id]);
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
    private static function isInitializedArray(string $mapper, $id) : bool
    {
        return !empty($id) && isset(self::$initArrays[$mapper], self::$initArrays[$mapper][$id]);
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
     * @since 1.0.0
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
     * @since 1.0.0
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
     * @since 1.0.0
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
     * @since 1.0.0
     */
    private static function isNullModel($obj) : bool
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
    private static function createNullModel($id = null)
    {
        $class     = static::class;
        $class     = empty(static::$model) ? \substr($class, 0, -6) : static::$model;
        $parts     = \explode('\\', $class);
        $name      = $parts[$c = (\count($parts) - 1)];
        $parts[$c] = 'Null' . $name;
        $class     = \implode('\\', $parts);

        $obj = new $class();

        if ($id !== null) {
            $refClass = new \ReflectionClass($obj);
            self::setObjectId($refClass, $obj, $id);
        }

        return $obj;
    }

    /**
     * Create the empty base model
     *
     * @return mixed
     *
     * @since 1.0.0
     */
    private static function createBaseModel()
    {
        $class = static::class;
        $class = empty(static::$model) ? \substr($class, 0, -6) : static::$model;

        /**
         * @todo Orange-Management/phpOMS#67
         *  Since some models require special initialization a model factory should be implemented.
         *  This could be a simple initialize() function in the mapper where the default initialize() is the current defined empty initialization in the DataMapperAbstract.
         */
        return new $class();
    }
}
