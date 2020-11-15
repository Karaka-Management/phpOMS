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
use phpOMS\DataStorage\Database\Query\Where;
use phpOMS\DataStorage\DataMapperInterface;
use phpOMS\Utils\ArrayUtils;
use phpOMS\Utils\TestUtils;

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
 * @todo Orange-Management/Modules#99
 *  Use binds
 *  Currently databinds are not used. Currently injections are possible.
 *
 * @todo Orange-Management/phpOMS#241
 *  [DataMapper] Consider global conditionals
 *  In some cases conditionals in the mapper are typed again and again
 *      e.g. language conditional for localization purposes
 *  This is very annoying and maybe could be defined once in a `$conditionalsGlobal = [];` array.
 *  This array then populates the `$conditionals` array in the mapper.
 *  Overwriting the global conditionals could be possible by defining a conditional as `null`.
 *
 * @todo Orange-Management/phpOMS#242
 *  [DataMapper] Conditional queries bugs/problems
 *  Corrupted conditional relations are not shown and therefor cannot be fixed by the user e.g.
 *      * Tag is created
 *      * No l11n is created
 *  -> The tags without l11n are not shown in the list and therefor the user doesn't know about them and cannot fix them.
 *  If the defined conditional doesn't exist (e.g. language) the element is not shown at all.
 *  This can be a problem if the user wants the conditional as preferred result
 *  but also accepts alternatives if nothing exists for this conditional but for other conditionals. E.g.
 *      * News article doesn't exist in the defined l11n
 *      * However if the article exists in english language it should at least show in that language.
 *
 * @todo Orange-Management/phpOMS#???
 *  Use more column/field names instead of model variable names
 *  Consider to replace the model member variable name in the `column` definition of hasMany etc. definitions with the actual column name. This could be faster.
 *  This could make it faster since we don't need to do a reverse look up.
 *  Maybe this will require us to do a different lookup however which costs a similar amount of time?
 *
 * @todo Orange-Management/phpOMS#???
 *  Concise usage of runtime evaluations vs hard-coded definitions
 *  Most of the time we are using Mapper::class etc. but there are still places where we use 'table' => 'table_name' instead of Mapper::$table.
 *  The Mapper::$table approach is probably better for future code changes but makes it probably also slower.
 *  We really need to decide to follow one path and implement this everywhere.
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
     * @var array<string, array{value:mixed, models:null|string[]}>
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
     * @var array<string, array{mapper:string, external:string, by?:string, column?:string, conditional?:bool}>
     * @since 1.0.0
     */
    protected static array $ownsOne = [];

    /**
     * Belongs to.
     *
     * @var array<string, array{mapper:string, external:string}>
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
     * Parent column.
     *
     * @var string
     * @since 1.0.0
     */
    protected static string $parent = '';

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
     * Relation type for nesting/joins
     *
     * @var int
     * @since 1.0.0
     */
    protected static int $relations = RelationType::ALL;

    /**
     * Datetime format of the database datetime
     *
     * This is only for the datetime stored in the database not the generated query.
     * For the query check the datetime in Grammar:$datetimeFormat
     *
     * @var string
     * @since 1.0.0
     */
    protected static string $datetimeFormat = 'Y-m-d H:i:s';

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
        /**
         * @todo Orange-Management/phpOMS#??? [p:low] [t:optimization] [d:expert]
         *  Implement with()
         *  Only load fields or models which are specified in with
         */
        self::$fields = $objects;
    }

    /**
     * Create a conditional value
     *
     * @param string   $id         Id of the conditional
     * @param mixed    $value      Value of the conditional
     * @param string[] $models     Models to apply the conditional on
     * @param string   $comparison Comparison operator
     *
     * @return static
     *
     * @since 1.0.0
     */
    public static function withConditional(string $id, $value, array $models = [], string $comparison = '=') /** @todo: return : static */
    {
        self::$conditionals[$id] = [
            'value'      => $value,
            'models'     => empty($models) ? null : $models,
            'comparison' => $comparison,
        ];

        return static::class;
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
        // clear parent and objects
        if (static::class === self::$parentMapper) {
            self::$parentMapper = null;
            self::$conditionals = [];
            self::$relations    = RelationType::ALL;
        }
    }

    /**
     * Find data.
     *
     * @param string  $search      Search for
     * @param int     $searchDepth Depth of the search
     * @param Builder $query       Query
     *
     * @return array
     *
     * @since 1.0.0
     */
    public static function find(string $search, int $searchDepth = 2, Builder $query = null) : array
    {
        $query = self::findQuery($search, $searchDepth, $query);

        return static::getAllByQuery($query, RelationType::ALL, $searchDepth);
    }

    /**
     * Find data query.
     *
     * @param string  $search      Search for
     * @param int     $searchDepth Depth of the search
     * @param Builder $query       Query
     *
     * @return Builder
     *
     * @since 1.0.0
     */
    public static function findQuery(string $search, int $searchDepth = 2, Builder $query = null) : Builder
    {
        $query ??= static::getQuery(null, [], RelationType::ALL, $searchDepth);

        $where1 = new Where(self::$db);
        $where2 = new Where(self::$db);

        $modelName = self::getModelName();

        $hasConditionals = false;
        foreach (self::$conditionals as $condKey => $condValue) {
            if (($column = self::getColumnByMember($condKey)) === null
                || ($condValue['models'] !== null && !\in_array($modelName, $condValue['models']))
            ) {
                continue;
            }

            $where1->andWhere(static::$table . '_' . $searchDepth . '.' . $column, $condValue['comparison'], $condValue['value']);
            $hasConditionals = true;
        }

        $hasAutocompletes = false;
        foreach (static::$columns as $col) {
            if (isset($col['autocomplete']) && $col['autocomplete']) {
                $where2->where(static::$table . '_' . $searchDepth . '.' . $col['name'], 'LIKE', '%' . $search . '%', 'OR');
                $hasAutocompletes = true;
            }
        }

        if ($hasConditionals) {
            $query->andWhere($where1);
        }

        if ($hasAutocompletes) {
            $query->orWhere($where2);
        }

        if ($searchDepth > 2) {
            foreach (static::$ownsOne as $one) {
                $one['mapper']::findQuery($search, $searchDepth - 1, $query);
            }

            foreach (static::$belongsTo as $belongs) {
                $belongs['mapper']::findQuery($search, $searchDepth - 1, $query);
            }

            foreach (static::$hasMany as $many) {
                $many['mapper']::findQuery($search, $searchDepth - 1, $query);
            }
        }

        return $query;
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
        if (!isset($obj)) {
            return null;
        }

        self::$relations = $relations;

        $refClass = new \ReflectionClass($obj);

        if (self::isNullModel($obj)) {
            $objId = self::getObjectId($obj, $refClass);

            return $objId === 0 ? null : $objId;
        }

        if (!empty($id = self::getObjectId($obj, $refClass)) && static::$autoincrement) {
            $objId = $id;
        } else {
            $objId = self::createModel($obj, $refClass);
            self::setObjectId($refClass, $obj, $objId);
        }

        if ($relations === RelationType::ALL) {
            self::createHasMany($refClass, $obj, $objId);
        }

        self::clear();

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
     * @since 1.0.0
     */
    public static function createArray(array &$obj, int $relations = RelationType::ALL)
    {
        self::$relations = $relations;

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

        self::clear();

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
        $query->into(static::$table);

        foreach (static::$columns as $column) {
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
        $query->into(static::$table);

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
        if (!isset(static::$hasMany[$member]) || !isset(static::$hasMany[$member]['external'])) {
            return false;
        }

        self::createRelationTable($member, \is_array($id2) ? $id2 : [$id2], $id1);
        self::removeInitialized(static::class, $id1);

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
            if (!isset(static::$hasMany[$propertyName]['mapper'])) {
                throw new InvalidMapperException();
            }

            $property = $refClass->getProperty($propertyName);

            if (!($isPublic = $property->isPublic())) {
                $property->setAccessible(true);
            }

            $values = $property->getValue($obj);

            /** @var self $mapper */
            $mapper = static::$hasMany[$propertyName]['mapper'];

            if (\is_object($values)) {
                // conditionals
                $relReflectionClass = new \ReflectionClass($values);
                $relProperty        = $relReflectionClass->getProperty($mapper::$columns[static::$hasMany[$propertyName]['self']]['internal']);

                if (!$isPublic) {
                    $relProperty->setAccessible(true);
                }

                $relProperty->setValue($values, $objId);

                if (!$isPublic) {
                    $relProperty->setAccessible(false);
                }

                $mapper::create($values);
                continue;
            }

            if (!\is_array($values)) {
                // conditionals
                continue;
            }

            if (!$isPublic) {
                $property->setAccessible(false);
            }

            $objsIds            = [];
            $relReflectionClass = !empty($values) ? new \ReflectionClass(\reset($values)) : null;

            foreach ($values as $key => $value) {
                if (!\is_object($value)) {
                    // Is scalar => already in database
                    $objsIds[$key] = $value;

                    continue;
                }

                /** @var \ReflectionClass $relReflectionClass */
                $primaryKey = $mapper::getObjectId($value, $relReflectionClass);

                // already in db
                if (!empty($primaryKey)) {
                    $objsIds[$key] = $value;

                    continue;
                }

                // Setting relation value (id) for relation (since the relation is not stored in an extra relation table)
                /** @var string $table */
                /** @var array $columns */
                if (!isset(static::$hasMany[$propertyName]['external'])) {
                    $relProperty = $relReflectionClass->getProperty($mapper::$columns[static::$hasMany[$propertyName]['self']]['internal']);

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
            if (!isset(static::$hasMany[$propertyName]['mapper'])) {
                throw new InvalidMapperException();
            }

            $values = $obj[$propertyName] ?? null;

            /** @var self $mapper */
            $mapper = static::$hasMany[$propertyName]['mapper'];

            if (\is_object($values)) {
                // conditionals
                TestUtils::setMember($values, $mapper::$columns[static::$hasMany[$propertyName]['self']]['internal'], $objId);

                $mapper::createArray($values);
                continue;
            }

            if (!\is_array($values)) {
                continue;
            }

            /** @var self $mapper */
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
                    && isset($mapper::$columns[static::$hasMany[$propertyName]['self']])
                ) {
                    $value[$mapper::$columns[static::$hasMany[$propertyName]['self']]['internal']] = $objId;
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
        if (!\is_object($obj)) {
            return $obj;
        }

        /** @var self $mapper */
        $mapper     = static::$ownsOne[$propertyName]['mapper'];
        $primaryKey = $mapper::getObjectId($obj);

        if (empty($primaryKey)) {
            return $mapper::create($obj);
        }

        return $primaryKey;
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
        /** @var self $mapper */
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
        if (!\is_object($obj)) {
            return $obj;
        }

        /** @var self $mapper */
        $mapper     = static::$belongsTo[$propertyName]['mapper'];
        $primaryKey = $mapper::getObjectId($obj);

        if (empty($primaryKey)) {
            return $mapper::create($obj);
        }

        return $primaryKey;
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
        /** @var self $mapper */
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
        if (empty($objsIds) || !isset(static::$hasMany[$propertyName]['external'])) {
            return;
        }

        $relQuery = new Builder(self::$db);
        $relQuery->into(static::$hasMany[$propertyName]['table'])
            ->insert(static::$hasMany[$propertyName]['external'], static::$hasMany[$propertyName]['self']);

        foreach ($objsIds as $src) {
            if (\is_object($src)) {
                $mapper = (\stripos($mapper = \get_class($src), '\Null') !== false
                    ? \str_replace('\Null', '\\', $mapper)
                    : $mapper)
                    . 'Mapper';

                $src = $mapper::getObjectId($src);
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
        } elseif ($type === 'DateTime' || $type === 'DateTimeImmutable') {
            return $value === null ? null : $value->format(self::$datetimeFormat);
        } elseif ($type === 'Json' || $value instanceof \JsonSerializable) {
            return (string) \json_encode($value);
        } elseif ($type === 'Serializable') {
            return $value->serialize();
        } elseif (\is_object($value) && \method_exists($value, 'getId')) {
            return $value->getId();
        }

        return $value;
    }

    /**
     * Update has many
     *
     * @param \ReflectionClass $refClass Reflection class
     * @param object           $obj      Object to create
     * @param mixed            $objId    Id to set
     * @param int              $depth    Depth of relations to update (default = 1 = none)
     *
     * @return void
     *
     * @throws InvalidMapperException Throws this exception if the mapper in the has many relation is invalid
     *
     * @since 1.0.0
     */
    private static function updateHasMany(\ReflectionClass $refClass, object $obj, $objId, $depth = 1) : void
    {
        $objsIds = [];

        foreach (static::$hasMany as $propertyName => $rel) {
            if ($rel['readonly'] ?? false === true) {
                continue;
            }

            if (!isset(static::$hasMany[$propertyName]['mapper'])) {
                throw new InvalidMapperException();
            }

            $property = $refClass->getProperty($propertyName);

            if (!($isPublic = $property->isPublic())) {
                $property->setAccessible(true);
            }

            $values = $property->getValue($obj);

            if (!\is_array($values)) {
                // conditionals
                continue;
            }

            if (!$isPublic) {
                $property->setAccessible(false);
            }

            /** @var self $mapper */
            $mapper                 = static::$hasMany[$propertyName]['mapper'];
            $relReflectionClass     = !empty($values) ? new \ReflectionClass(\reset($values)) : null;
            $objsIds[$propertyName] = [];

            foreach ($values as $key => &$value) {
                if (!\is_object($value)) {
                    // Is scalar => already in database
                    $objsIds[$propertyName][$key] = $value;

                    continue;
                }

                /** @var \ReflectionClass @relReflectionClass */
                $primaryKey = $mapper::getObjectId($value, $relReflectionClass);

                // already in db
                if (!empty($primaryKey)) {
                    $mapper::update($value, self::$relations, $depth);

                    $objsIds[$propertyName][$key] = $value;

                    continue;
                }

                // create if not existing
                /** @var string $table */
                /** @var array $columns */
                if (static::$hasMany[$propertyName]['table'] === static::$hasMany[$propertyName]['mapper']::$table
                    && isset($mapper::$columns[static::$hasMany[$propertyName]['self']])
                ) {
                    $relProperty = $relReflectionClass->getProperty($mapper::$columns[static::$hasMany[$propertyName]['self']]['internal']);

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
     * @param array $obj   Object to create
     * @param mixed $objId Id to set
     * @param int   $depth Depth of relations to update (default = 1 = none)
     *
     * @return void
     *
     * @throws InvalidMapperException Throws this exception if the mapper in the has many relation is invalid
     *
     * @since 1.0.0
     */
    private static function updateHasManyArray(array &$obj, $objId, $depth = 1) : void
    {
        $objsIds = [];

        foreach (static::$hasMany as $propertyName => $rel) {
            if ($rel['readonly'] ?? false === true) {
                continue;
            }

            if (!isset(static::$hasMany[$propertyName]['mapper'])) {
                throw new InvalidMapperException();
            }

            $values = $obj[$propertyName] ?? null;

            if (!\is_array($values)) {
                // conditionals
                continue;
            }

            /** @var self $mapper */
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
                    $mapper::updateArray($value, self::$relations, $depth);

                    $objsIds[$propertyName][$key] = $value;

                    continue;
                }

                // create if not existing
                /** @var string $table */
                /** @var array $columns */
                if (static::$hasMany[$propertyName]['table'] === static::$hasMany[$propertyName]['mapper']::$table
                    && isset($mapper::$columns[static::$hasMany[$propertyName]['self']])
                ) {
                    $value[$mapper::$columns[static::$hasMany[$propertyName]['self']]['internal']] = $objId;
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
     * @return void
     *
     * @since 1.0.0
     */
    private static function deleteRelationTable(string $propertyName, array $objsIds, $objId) : void
    {
        /** @var string $table */
        if (empty($objsIds)
            || static::$hasMany[$propertyName]['table'] === static::$table
            || static::$hasMany[$propertyName]['table'] === static::$hasMany[$propertyName]['mapper']::$table
        ) {
            return;
        }

        foreach ($objsIds as $key => $src) {
            $relQuery = new Builder(self::$db);
            $relQuery->delete()
                ->from(static::$hasMany[$propertyName]['table'])
                ->where(static::$hasMany[$propertyName]['table'] . '.' . static::$hasMany[$propertyName]['external'], '=', $src)
                ->where(static::$hasMany[$propertyName]['table'] . '.' . static::$hasMany[$propertyName]['self'], '=', $objId, 'and');

            self::$db->con->prepare($relQuery->toSql())->execute();
        }
    }

    /**
     * Update owns one
     *
     * The reference is stored in the main model
     *
     * @param string $propertyName Property name to initialize
     * @param mixed  $obj          Object to update
     * @param int    $depth        Depth of relations to update (default = 1 = none)
     *
     * @return mixed
     *
     * @since 1.0.0
     */
    private static function updateOwnsOne(string $propertyName, $obj, int $depth = 1)
    {
        if (!\is_object($obj)) {
            return $obj;
        }

        /** @var self $mapper */
        $mapper = static::$ownsOne[$propertyName]['mapper'];

        return $mapper::update($obj, self::$relations, $depth);
    }

    /**
     * Update owns one
     *
     * The reference is stored in the main model
     *
     * @param string $propertyName Property name to initialize
     * @param array  $obj          Object to update
     * @param int    $depth        Depth of relations to update (default = 1 = none)
     *
     * @return mixed
     *
     * @since 1.0.0
     */
    private static function updateOwnsOneArray(string $propertyName, array $obj, int $depth = 1)
    {
        /** @var self $mapper */
        $mapper = static::$ownsOne[$propertyName]['mapper'];

        return $mapper::updateArray($obj, self::$relations, $depth);
    }

    /**
     * Update owns one
     *
     * The reference is stored in the main model
     *
     * @param string $propertyName Property name to initialize
     * @param mixed  $obj          Object to update
     * @param int    $depth        Depth of relations to update (default = 1 = none)
     *
     * @return mixed
     *
     * @since 1.0.0
     */
    private static function updateBelongsTo(string $propertyName, $obj, int $depth = 1)
    {
        if (!\is_object($obj)) {
            return $obj;
        }

        /** @var self $mapper */
        $mapper = static::$belongsTo[$propertyName]['mapper'];

        return $mapper::update($obj, self::$relations, $depth);
    }

    /**
     * Update owns one
     *
     * The reference is stored in the main model
     *
     * @param string $propertyName Property name to initialize
     * @param mixed  $obj          Object to update
     * @param int    $depth        Depth of relations to update (default = 1 = none)
     *
     * @return mixed
     *
     * @since 1.0.0
     */
    private static function updateBelongsToArray(string $propertyName, $obj, int $depth = 1)
    {
        if (!\is_array($obj)) {
            return $obj;
        }

        /** @var self $mapper */
        $mapper = static::$belongsTo[$propertyName]['mapper'];

        return $mapper::updateArray($obj, self::$relations, $depth);
    }

    /**
     * Update object in db.
     *
     * @param object           $obj      Model to update
     * @param mixed            $objId    Model id
     * @param \ReflectionClass $refClass Reflection class
     * @param int              $depth    Depth of relations to update (default = 1 = none)
     *
     * @return void
     *
     * @since 1.0.0
     */
    private static function updateModel(object $obj, $objId, \ReflectionClass $refClass = null, int $depth = 1) : void
    {
        $query = new Builder(self::$db);
        $query->update(static::$table)
            ->where(static::$table . '.' . static::$primaryField, '=', $objId);

        foreach (static::$columns as $column) {
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
                $id    = self::updateOwnsOne($propertyName, $property->getValue($obj), $depth);
                $value = self::parseValue($column['type'], $id);

                /**
                 * @todo Orange-Management/phpOMS#232
                 *  If a model gets updated all it's relations are also updated.
                 *  This should be prevented if the relations didn't change.
                 *  No solution yet.
                 */
                $query->set([static::$table . '.' . $column['name'] => $value]);
            } elseif (isset(static::$belongsTo[$propertyName])) {
                $id    = self::updateBelongsTo($propertyName, $property->getValue($obj), $depth);
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
     * Update object in db.
     *
     * @param array $obj   Model to update
     * @param mixed $objId Model id
     * @param int   $depth Depth of relations to update (default = 1 = none)
     *
     * @return void
     *
     * @since 1.0.0
     */
    private static function updateModelArray(array $obj, $objId, int $depth = 1) : void
    {
        $query = new Builder(self::$db);
        $query->update(static::$table)
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
                $id    = self::updateOwnsOneArray($column['internal'], $property, $depth);
                $value = self::parseValue($column['type'], $id);

                /**
                 * @todo Orange-Management/phpOMS#232
                 *  If a model gets updated all it's relations are also updated.
                 *  This should be prevented if the relations didn't change.
                 *  No solution yet.
                 */
                $query->set([static::$table . '.' . $column['name'] => $value]);
            } elseif (isset(static::$belongsTo[$path])) {
                $id    = self::updateBelongsToArray($column['internal'], $property, $depth);
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
     * @param mixed $obj       Object reference (gets filled with insert id)
     * @param int   $relations Create all relations as well
     * @param int   $depth     Depth of relations to update (default = 1 = none)
     *
     * @return mixed
     *
     * @since 1.0.0
     */
    public static function update($obj, int $relations = RelationType::ALL, int $depth = 3)
    {
        if (!isset($obj)) {
            return null;
        }

        self::$relations = $relations;

        $refClass = new \ReflectionClass($obj);
        $objId    = self::getObjectId($obj, $refClass);

        if ($depth < 1 || self::isNullModel($obj)) {
            return $objId === 0 ? null : $objId;
        }

        self::addInitialized(static::class, $objId, $obj, $depth);
        --$depth;

        if ($relations === RelationType::ALL) {
            self::updateHasMany($refClass, $obj, $objId, $depth);
        }

        if (empty($objId)) {
            return self::create($obj, self::$relations);
        }

        self::updateModel($obj, $objId, $refClass, $depth);

        self::clear();

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
        if (empty($obj)) {
            return null;
        }

        self::$relations = $relations;

        $objId  = $obj[static::$columns[static::$primaryField]['internal']];
        $update = true;

        if ($depth < 1) {
            return $objId;
        }

        self::addInitializedArray(static::class, $objId, $obj, $depth);
        --$depth;

        if (empty($objId)) {
            $update = false;
            self::createArray($obj, self::$relations);
        }

        if ($relations === RelationType::ALL) {
            self::updateHasManyArray($obj, $objId, $depth);
        }

        if ($update) {
            self::updateModelArray($obj, $objId, $depth);
        }

        self::clear();

        return $objId;
    }

    /**
     * Delete has many
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
    private static function deleteHasMany(\ReflectionClass $refClass, object $obj, $objId) : void
    {
        foreach (static::$hasMany as $propertyName => $rel) {
            if (!isset(static::$hasMany[$propertyName]['mapper'])) {
                throw new InvalidMapperException();
            }

            $property = $refClass->getProperty($propertyName);

            if (!($isPublic = $property->isPublic())) {
                $property->setAccessible(true);
            }

            $values = $property->getValue($obj);

            if (!\is_array($values)) {
                // conditionals
                continue;
            }

            if (!$isPublic) {
                $property->setAccessible(false);
            }

            /** @var self $mapper */
            $mapper             = static::$hasMany[$propertyName]['mapper'];
            $objsIds            = [];
            $relReflectionClass = !empty($values) ? new \ReflectionClass(\reset($values)) : null;

            foreach ($values as $key => &$value) {
                if (!\is_object($value)) {
                    // Is scalar => already in database
                    $objsIds[$key] = $value;

                    continue;
                }

                $primaryKey = $mapper::getObjectId($value, $relReflectionClass);

                // already in db
                if (!empty($primaryKey)) {
                    $objsIds[$key] = self::$relations === RelationType::ALL ? $mapper::delete($value) : $primaryKey;

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
        if (!\is_object($obj)) {
            return $obj;
        }

        /** @var self $mapper */
        $mapper = static::$ownsOne[$propertyName]['mapper'];

        /**
         * @todo Orange-Management/phpOMS#??? [p:low] [t:question] [d:expert]
         *  Deleting a owned one object is not recommended since it can be owned by something else?
         *  Or does owns one mean that nothing else can have a relation to this model?
         */

        return $mapper::delete($obj);
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
        if (!\is_object($obj)) {
            return $obj;
        }

        /** @var self $mapper */
        $mapper = static::$belongsTo[$propertyName]['mapper'];

        return $mapper::delete($obj);
    }

    /**
     * Delete object in db.
     *
     * @param object           $obj      Model to delete
     * @param mixed            $objId    Model id
     * @param \ReflectionClass $refClass Reflection class
     *
     * @return void
     *
     * @since 1.0.0
     */
    private static function deleteModel(object $obj, $objId, \ReflectionClass $refClass = null) : void
    {
        $query = new Builder(self::$db);
        $query->delete()
            ->from(static::$table)
            ->where(static::$table . '.' . static::$primaryField, '=', $objId);

        $refClass   = $refClass ?? new \ReflectionClass($obj);
        $properties = $refClass->getProperties();

        if (self::$relations === RelationType::ALL) {
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
                    if (self::$relations === RelationType::ALL && isset(static::$ownsOne[$propertyName]) && $column['internal'] === $propertyName) {
                        self::deleteOwnsOne($propertyName, $property->getValue($obj));
                        break;
                    } elseif (self::$relations === RelationType::ALL && isset(static::$belongsTo[$propertyName]) && $column['internal'] === $propertyName) {
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
        // @todo: only do this if RelationType !== NONE
        if (\is_scalar($obj)) {
            $obj = static::get($obj);
        }

        self::$relations = $relations;

        $refClass = new \ReflectionClass($obj);
        $objId    = self::getObjectId($obj, $refClass);

        if (empty($objId)) {
            return null;
        }

        self::removeInitialized(static::class, $objId);

        if ($relations !== RelationType::NONE) {
            self::deleteHasMany($refClass, $obj, $objId);
        }

        self::deleteModel($obj, $objId, $refClass);
        self::clear();

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
     * @param int   $depth  Relation depth
     *
     * @return array
     *
     * @since 1.0.0
     */
    public static function populateIterable(array $result, int $depth = 3) : array
    {
        $obj = [];

        foreach ($result as $element) {
            if (isset($element[static::$primaryField . '_' . $depth])
                && self::isInitialized(static::class, $element[static::$primaryField . '_' . $depth], $depth)
            ) {
                $obj[$element[static::$primaryField . '_' . $depth]] = self::$initObjects[static::class][$element[static::$primaryField . '_' . $depth]['obj']];

                continue;
            }

            $toFill = self::createBaseModel();

            if (!isset($element[static::$primaryField . '_' . $depth])) {
                throw new \Exception();
            }

            $obj[$element[static::$primaryField . '_' . $depth]] = self::populateAbstract($element, $toFill, $depth);
            self::addInitialized(static::class, $element[static::$primaryField . '_' . $depth], $obj[$element[static::$primaryField . '_' . $depth]], $depth);
        }

        return $obj;
    }

    /**
     * Populate data.
     *
     * @param array $result Result set
     * @param int   $depth  Relation depth
     *
     * @return array
     *
     * @since 1.0.0
     */
    public static function populateIterableArray(array $result, int $depth = 3) : array
    {
        $obj = [];

        foreach ($result as $element) {
            if (isset($element[static::$primaryField])
                && self::isInitializedArray(static::class, $element[static::$primaryField], $depth)
            ) {
                $obj[$element[static::$primaryField]] = self::$initArrays[static::class][$element[static::$primaryField]]['obj'];

                continue;
            }

            if (!isset($element[static::$primaryField])) {
                throw new \Exception();
            }

            $obj[$element[static::$primaryField]] = self::populateAbstractArray($element, [], $depth);
            self::addInitializedArray(static::class, $element[static::$primaryField], $obj[$element[static::$primaryField]], $depth);
        }

        return $obj;
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
            if (empty($values)
                || !$refClass->hasProperty($member)
                || isset(static::$hasMany[$member]['column']) // handled in getQuery()
            ) {
                continue;
            }

            /** @var self $mapper */
            $mapper  = static::$hasMany[$member]['mapper'];
            $refProp = $refClass->getProperty($member);

            if (!($accessible = $refProp->isPublic())) {
                $refProp->setAccessible(true);
            }

            $objects = !isset(static::$hasMany[$member]['by'])
                ? $mapper::get($values, RelationType::ALL, $depth)
                : $mapper::get($values, RelationType::ALL, $depth, static::$hasMany[$member]['by']);

            $refProp->setValue($obj, !\is_array($objects) && !isset(static::$hasMany[$member]['conditional']) ? [$mapper::getObjectId($objects) => $objects] : $objects);

            if (!$accessible) {
                $refProp->setAccessible(false);
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
            if (empty($values)
                || isset(static::$hasMany[$member]['conditional']) // handled in getQuery()
            ) {
                continue;
            }

            /** @var self $mapper */
            $mapper = static::$hasMany[$member]['mapper'];

            $objects      = $mapper::getArray($values, RelationType::ALL, $depth);
            $obj[$member] = $objects;
        }
    }

    /**
     * Populate data.
     *
     * @param string $member  Member name
     * @param array  $result  Result data
     * @param int    $depth   Relation depth
     * @param mixed  $default Default value
     *
     * @return mixed
     *
     * @todo: in the future we could pass not only the $id ref but all of the data as a join!!! and save an additional select!!!
     * @todo: parent and child elements however must be loaded because they are not loaded
     *
     * @since 1.0.0
     */
    public static function populateOwnsOne(string $member, array $result, int $depth = 3, $default = null)
    {
        /** @var self $mapper */
        $mapper = static::$ownsOne[$member]['mapper'];

        if ($depth < 1) {
            if (\array_key_exists(static::$ownsOne[$member]['external'] . '_' . ($depth + 1), $result)) {
                return isset(static::$ownsOne[$member]['column'])
                    ? $result[static::$ownsOne[$member]['external'] . '_' . ($depth + 1)]
                    : $mapper::createNullModel($result[static::$ownsOne[$member]['external'] . '_' . ($depth + 1)]);
            } else {
                return $default;
            }
        }

        if (isset(static::$ownsOne[$member]['column'])) {
            return $result[$mapper::getColumnByMember(static::$ownsOne[$member]['column']) . '_' . $depth];
        }

        if (!isset($result[$mapper::$primaryField . '_' . $depth])) {
            return $mapper::createNullModel();
        }

        $obj = $mapper::getInitialized($mapper, $result[$mapper::$primaryField . '_' . $depth], $depth);

        return $obj ?? $mapper::populateAbstract($result, $mapper::createBaseModel(), $depth);
    }

    /**
     * Populate data.
     *
     * @param string $member  Member name
     * @param array  $result  Result data
     * @param int    $depth   Relation depth
     * @param mixed  $default Default value
     *
     * @return array
     *
     * @since 1.0.0
     */
    public static function populateOwnsOneArray(string $member, array $result, int $depth = 3, $default = null) : array
    {
        /** @var self $mapper */
        $mapper = static::$ownsOne[$member]['mapper'];

        if ($depth < 1) {
            if (\array_key_exists(static::$ownsOne[$member]['external'] . '_' . ($depth + 1), $result)) {
                return $result[static::$ownsOne[$member]['external'] . '_' . ($depth + 1)];
            } else {
                return $default;
            }
        }

        if (isset(static::$ownsOne[$member]['column'])) {
            return $result[$mapper::getColumnByMember(static::$ownsOne[$member]['column']) . '_' . $depth];
        }

        if (!isset($result[$mapper::$primaryField . '_' . $depth])) {
            return [];
        }

        $obj = $mapper::getInitializedArray($mapper, $result[$mapper::$primaryField . '_' . $depth], $depth);

        return $obj ?? $mapper::populateAbstractArray($result, [], $depth);
    }

    /**
     * Populate data.
     *
     * @param string $member  Member name
     * @param array  $result  Result data
     * @param int    $depth   Relation depth
     * @param mixed  $default Default value
     *
     * @return mixed
     *
     * @todo: in the future we could pass not only the $id ref but all of the data as a join!!! and save an additional select!!!
     *
     * @since 1.0.0
     */
    public static function populateBelongsTo(string $member, array $result, int $depth = 3, $default = null)
    {
        /** @var self $mapper */
        $mapper = static::$belongsTo[$member]['mapper'];

        if ($depth < 1) {
            if (\array_key_exists(static::$belongsTo[$member]['external'] . '_' . ($depth + 1), $result)) {
                return isset(static::$belongsTo[$member]['column'])
                    ? $result[static::$belongsTo[$member]['external'] . '_' . ($depth + 1)]
                    : $mapper::createNullModel($result[static::$belongsTo[$member]['external'] . '_' . ($depth + 1)]);
            } else {
                return $default;
            }
        }

        if (isset(static::$belongsTo[$member]['column'])) {
            return $result[$mapper::getColumnByMember(static::$belongsTo[$member]['column']) . '_' . $depth];
        }

        if (!isset($result[$mapper::$primaryField . '_' . $depth])) {
            return $mapper::createNullModel();
        }

        $obj = $mapper::getInitialized($mapper, $result[$mapper::$primaryField . '_' . $depth], $depth);

        return $obj ?? $mapper::populateAbstract($result, $mapper::createBaseModel(), $depth);
    }

    /**
     * Populate data.
     *
     * @param string $member  Member name
     * @param array  $result  Result data
     * @param int    $depth   Relation depth
     * @param mixed  $default Default value
     *
     * @return array
     *
     * @since 1.0.0
     */
    public static function populateBelongsToArray(string $member, array $result, int $depth = 3, $default = null) : array
    {
        /** @var self $mapper */
        $mapper = static::$belongsTo[$member]['mapper'];

        if ($depth < 1) {
            if (\array_key_exists(static::$belongsTo[$member]['external'] . '_' . ($depth + 1), $result)) {
                return $result[static::$belongsTo[$member]['external'] . '_' . ($depth + 1)];
            } else {
                return $default;
            }
        }

        if (isset(static::$belongsTo[$member]['column'])) {
            return $result[$mapper::getColumnByMember(static::$belongsTo[$member]['column']) . '_' . $depth];
        }

        if (!isset($result[$mapper::$primaryField . '_' . $depth])) {
            return [];
        }

        $obj = $mapper::getInitializedArray($mapper, $result[$mapper::$primaryField . '_' . $depth], $depth);

        return $obj ?? $mapper::populateAbstractArray($result, [], $depth);
    }

    /**
     * Populate data.
     *
     * @param array $result Query result set
     * @param mixed $obj    Object to populate
     * @param int   $depth  Relation depth
     *
     * @return mixed
     *
     * @throws \UnexpectedValueException
     *
     * @since 1.0.0
     */
    public static function populateAbstract(array $result, $obj, int $depth = 3)
    {
        $refClass = new \ReflectionClass($obj);

        foreach (static::$columns as $column => $def) {
            $alias = $column . '_' . $depth;

            if (!\array_key_exists($alias, $result)) {
                continue;
            }

            $value = $result[$alias];

            $hasPath   = false;
            $aValue    = [];
            $arrayPath = '';

            if (\stripos($def['internal'], '/') !== false) {
                $hasPath = true;
                $path    = \explode('/', $def['internal']);
                $refProp = $refClass->getProperty($path[0]);

                if (!($accessible = $refProp->isPublic())) {
                    $refProp->setAccessible(true);
                }

                \array_shift($path);
                $arrayPath = \implode('/', $path);
                $aValue    = $refProp->getValue($obj);
            } else {
                $refProp = $refClass->getProperty($def['internal']);

                if (!($accessible = $refProp->isPublic())) {
                    $refProp->setAccessible(true);
                }
            }

            if (isset(static::$ownsOne[$def['internal']])) {
                $default = null;
                if ($depth - 1 < 1 && $refProp->isInitialized($obj)) {
                    $default = $refProp->getValue($obj);
                }

                $value = self::populateOwnsOne($def['internal'], $result, $depth - 1, $default);

                if (\is_object($value) && isset(static::$ownsOne[$def['internal']]['mapper'])) {
                    static::$ownsOne[$def['internal']]['mapper']::fillRelations($value, self::$relations, $depth - 1);
                }

                $refProp->setValue($obj, $value);
            } elseif (isset(static::$belongsTo[$def['internal']])) {
                $default = null;
                if ($depth - 1 < 1 && $refProp->isInitialized($obj)) {
                    $default = $refProp->getValue($obj);
                }

                $value = self::populateBelongsTo($def['internal'], $result, $depth - 1, $default);

                if (\is_object($value) && isset(static::$belongsTo[$def['internal']]['mapper'])) {
                    static::$belongsTo[$def['internal']]['mapper']::fillRelations($value, self::$relations, $depth - 1);
                }

                $refProp->setValue($obj, $value);
            } elseif (\in_array($def['type'], ['string', 'int', 'float', 'bool'])) {
                if ($value !== null || $refProp->getValue($obj) !== null) {
                    \settype($value, $def['type']);
                }

                if ($hasPath) {
                    $value = ArrayUtils::setArray($arrayPath, $aValue, $value, '/', true);
                }

                $refProp->setValue($obj, $value);
            } elseif ($def['type'] === 'DateTime') {
                $value = $value === null ? null : new \DateTime($value);
                if ($hasPath) {
                    $value = ArrayUtils::setArray($arrayPath, $aValue, $value, '/', true);
                }

                $refProp->setValue($obj, $value);
            } elseif ($def['type'] === 'DateTimeImmutable') {
                $value = $value === null ? null : new \DateTimeImmutable($value);
                if ($hasPath) {
                    $value = ArrayUtils::setArray($arrayPath, $aValue, $value, '/', true);
                }

                $refProp->setValue($obj, $value);
            } elseif ($def['type'] === 'Json') {
                if ($hasPath) {
                    $value = ArrayUtils::setArray($arrayPath, $aValue, $value, '/', true);
                }

                $refProp->setValue($obj, \json_decode($value, true));
            } elseif ($def['type'] === 'Serializable') {
                $member = $refProp->getValue($obj);
                $member->unserialize($value);
            }

            if (!$accessible) {
                $refProp->setAccessible(false);
            }
        }

        foreach (static::$hasMany as $member => $def) {
            $column = $def['mapper']::getColumnByMember($def['column'] ?? $member);
            $alias  = $column . '_' . ($depth - 1);

            if (!\array_key_exists($alias, $result) || !isset($def['column'])) {
                continue;
            }

            $value     = $result[$alias];
            $hasPath   = false;
            $aValue    = null;
            $arrayPath = '/';

            if (\stripos($member, '/') !== false) {
                $hasPath = true;
                $path    = \explode('/', $member);
                $refProp = $refClass->getProperty($path[0]);

                if (!($accessible = $refProp->isPublic())) {
                    $refProp->setAccessible(true);
                }

                \array_shift($path);
                $arrayPath = \implode('/', $path);
                $aValue    = $refProp->getValue($obj);
            } else {
                $refProp = $refClass->getProperty($member);

                if (!($accessible = $refProp->isPublic())) {
                    $refProp->setAccessible(true);
                }
            }

            if (\in_array($def['mapper']::$columns[$column]['type'], ['string', 'int', 'float', 'bool'])) {
                if ($value !== null || $refProp->getValue($obj) !== null) {
                    \settype($value, $def['mapper']::$columns[$column]['type']);
                }

                if ($hasPath) {
                    $value = ArrayUtils::setArray($arrayPath, $aValue, $value, '/', true);
                }

                $refProp->setValue($obj, $value);
            } elseif ($def['mapper']::$columns[$column]['type'] === 'DateTime') {
                $value = $value === null ? null : new \DateTime($value);
                if ($hasPath) {
                    $value = ArrayUtils::setArray($arrayPath, $aValue, $value, '/', true);
                }

                $refProp->setValue($obj, $value);
            } elseif ($def['mapper']::$columns[$column]['type'] === 'DateTimeImmutable') {
                $value = $value === null ? null : new \DateTimeImmutable($value);
                if ($hasPath) {
                    $value = ArrayUtils::setArray($arrayPath, $aValue, $value, '/', true);
                }

                $refProp->setValue($obj, $value);
            } elseif ($def['mapper']::$columns[$column]['type'] === 'Json') {
                if ($hasPath) {
                    $value = ArrayUtils::setArray($arrayPath, $aValue, $value, '/', true);
                }

                $refProp->setValue($obj, \json_decode($value, true));
            } elseif ($def['mapper']::$columns[$column]['type'] === 'Serializable') {
                $member = $refProp->getValue($obj);
                $member->unserialize($value);
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
     * @param array $obj    Object to populate
     * @param int   $depth  Relation depth
     *
     * @return array
     *
     * @since 1.0.0
     */
    public static function populateAbstractArray(array $result, array $obj = [], int $depth = 3) : array
    {
        foreach (static::$columns as $column => $def) {
            $alias = $column . '_' . $depth;

            if (!\array_key_exists($alias, $result)) {
                continue;
            }

            $value = $result[$alias];

            $path = static::$columns[$column]['internal'];
            if (\stripos($path, '/') !== false) {
                $path = \explode('/', $path);

                \array_shift($path);
                $path = \implode('/', $path);
            }

            if (isset(static::$ownsOne[$def['internal']])) {
                $value = self::populateOwnsOneArray(static::$columns[$column]['internal'], $result, $depth - 1);

                static::$ownsOne[$def['internal']]['mapper']::fillRelationsArray($value, self::$relations, $depth - 1);
            } elseif (isset(static::$belongsTo[$def['internal']])) {
                $value = self::populateBelongsToArray(static::$columns[$column]['internal'], $result, $depth - 1);

                static::$belongsTo[$def['internal']]['mapper']::fillRelationsArray($value, self::$relations, $depth - 1);
            } elseif (\in_array(static::$columns[$column]['type'], ['string', 'int', 'float', 'bool'])) {
                \settype($value, static::$columns[$column]['type']);
            } elseif (static::$columns[$column]['type'] === 'DateTime') {
                $value = $value === null ? null : new \DateTime($value);
            } elseif (static::$columns[$column]['type'] === 'DateTimeImmutable') {
                $value = $value === null ? null : new \DateTimeImmutable($value);
            } elseif (static::$columns[$column]['type'] === 'Json') {
                $value = \json_decode($value, true);
            }

            $obj = ArrayUtils::setArray($path, $obj, $value, '/', true);
        }

        foreach (static::$hasMany as $member => $def) {
            $column = $def['mapper']::getColumnByMember($member);
            $alias  = $column . '_' . ($depth - 1);

            if (!\array_key_exists($alias, $result) || !isset($def['column'])) {
                continue;
            }

            $value = $result[$alias];

            $path = $member;
            if (\in_array($def['mapper']::$columns[$column]['type'], ['string', 'int', 'float', 'bool'])) {
                \settype($value, $def['mapper']::$columns[$column]['type']);
            } elseif ($def['mapper']::$columns[$column]['type'] === 'DateTime') {
                $value = $value === null ? null : new \DateTime($value);
            } elseif ($def['mapper']::$columns[$column]['type'] === 'DateTimeImmutable') {
                $value = $value === null ? null : new \DateTimeImmutable($value);
            } elseif ($def['mapper']::$columns[$column]['type'] === 'Json') {
                $value = \json_decode($value, true);
            }

            $obj = ArrayUtils::setArray($path, $obj, $value, '/', true);
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
        $query ??= new Builder(self::$db);
        $query->select('COUNT(*)')
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
     * @param int    $depth     Relation depth
     *
     * @return array
     *
     * @since 1.0.0
     */
    public static function getAfterPivot(
        $pivot,
        string $column = null,
        int $limit = 50,
        string $order = 'ASC',
        int $relations = RelationType::ALL,
        int $depth = 3
    ) : array
    {
        $query = self::getQuery();
        $query->where(static::$table . '_' . $depth . '.' . ($column !== null ? self::getColumnByMember($column) : static::$primaryField), '>', $pivot)
            ->orderBy(static::$table . '_' . $depth . '.' . ($column !== null ? self::getColumnByMember($column) : static::$primaryField), $order)
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
     * @param int    $depth     Relation depth
     *
     * @return array
     *
     * @since 1.0.0
     *
     * @todo Orange-Management/phpOMS#? [p:medium] [d:medium] [t:bug]
     *  If the pivot element doesn't exist the result set is empty.
     *  It should just return the closes elements "before" the pivot element.
     */
    public static function getBeforePivot(
        $pivot,
        string $column = null,
        int $limit = 50,
        string $order = 'ASC',
        int $relations = RelationType::ALL,
        int $depth = 3
    ) : array
    {
        $query = self::getQuery();
        $query->where(static::$table . '_' . $depth . '.' . ($column !== null ? self::getColumnByMember($column) : static::$primaryField), '<', $pivot)
            ->orderBy(static::$table . '_' . $depth . '.' . ($column !== null ? self::getColumnByMember($column) : static::$primaryField), $order)
            ->limit($limit);

        return self::getAllByQuery($query, $relations, $depth);
    }

    /**
     * Get object.
     *
     * @param mixed   $primaryKey Key
     * @param int     $relations  Load relations
     * @param int     $depth      Relation depth
     * @param string  $ref        Ref (for getBy and getFor)
     * @param Builder $query      Query
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
    public static function get($primaryKey, int $relations = RelationType::ALL, int $depth = 3, string $ref = null, Builder $query = null)
    {
        if ($depth < 1) {
            return self::createNullModel($primaryKey);
        }

        if (!isset(self::$parentMapper)) {
            self::$parentMapper = static::class;
        }

        self::$relations = $relations;

        $keys = (array) $primaryKey;
        $obj  = [];

        foreach ($keys as $key => $value) {
            if (!self::isInitialized(static::class, $value, $depth) || $ref !== null) {
                continue;
            }

            $obj[$value] = self::$initObjects[static::class][$value]['obj'];
            unset($keys[$key]);
        }

        if (!empty($keys) || $primaryKey === null) {
            $dbData = self::getRaw($keys, self::$relations, $depth, $ref, $query);
            foreach ($dbData as $row) {
                $value       = $row[static::$primaryField . '_' . $depth];
                $obj[$value] = self::createBaseModel();
                self::addInitialized(static::class, $value, $obj[$value], $depth);

                $obj[$value] = self::populateAbstract($row, $obj[$value], $depth);
                self::fillRelations($obj[$value], self::$relations, $depth - 1);
            }
        }

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
     * @param mixed  $primaryKey Key
     * @param int    $relations  Load relations
     * @param int    $depth      Relation depth
     * @param string $ref        Ref (for getBy and getFor)
     *
     * @return array
     *
     * @since 1.0.0
     */
    public static function getArray($primaryKey, int $relations = RelationType::ALL, int $depth = 3, string $ref = null) : array
    {
        if ($depth < 1) {
            return $primaryKey;
        }

        if (!isset(self::$parentMapper)) {
            self::$parentMapper = static::class;
        }

        $primaryKey = (array) $primaryKey;
        $obj        = [];

        self::$relations = $relations;

        foreach ($primaryKey as $key => $value) {
            if (!self::isInitializedArray(static::class, $value, $depth) || $ref !== null) {
                continue;
            }

            $obj[$value] = self::$initArrays[static::class][$value]['obj'];
            unset($primaryKey[$key]);
        }

        $dbData = self::getRaw($primaryKey, $relations, $depth, $ref);
        if (empty($dbData)) {
            $countResulsts = \count($obj);

            if ($countResulsts === 0) {
                return [];
            } elseif ($countResulsts === 1) {
                return \reset($obj);
            }

            return $obj;
        }

        foreach ($dbData as $row) {
            $value       = $row[static::$primaryField . '_' . $depth];
            $obj[$value] = self::populateAbstractArray($row, [], $depth);

            self::addInitializedArray(static::class, $value, $obj[$value], $depth);
            self::fillRelationsArray($obj[$value], self::$relations, $depth - 1);
        }

        self::clear();

        return \count($obj) === 1 ? \reset($obj) : $obj;
    }

    /**
     * Get object.
     *
     * @param mixed  $forKey    Key
     * @param string $for       The field that defines the for
     * @param int    $relations Load relations
     * @param int    $depth     Relation depth
     *
     * @return mixed
     *
     * @since 1.0.0
     * @todo by and for look the same, this cannot be correct.
     */
    public static function getFor($forKey, string $for, int $relations = RelationType::ALL, int $depth = 3)
    {
        return self::get($forKey, $relations, $depth, $for);
    }

    /**
     * Get object.
     *
     * @param mixed  $byKey     Key
     * @param string $by        The field that defines the for
     * @param int    $relations Load relations
     * @param int    $depth     Relation depth
     *
     * @return mixed
     *
     * @since 1.0.0
     * @todo by and for look the same, this cannot be correct.
     */
    public static function getBy($byKey, string $by, int $relations = RelationType::ALL, int $depth = 3)
    {
        return self::get($byKey, $relations, $depth, $by);
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
        return self::getArray($refKey, $relations, $depth, $ref);
    }

    /**
     * Get object.
     *
     * @param mixed  $byKey     Key
     * @param string $by        The field that defines the for
     * @param int    $relations Load relations
     * @param int    $depth     Relation depth
     *
     * @return mixed
     *
     * @since 1.0.0
     */
    public static function getByArray($byKey, string $by, int $relations = RelationType::ALL, int $depth = 3)
    {
        return self::getArray($byKey, $relations, $depth, $by);
    }

    /**
     * Get object.
     *
     * @param int $relations Load relations
     * @param int $depth     Relation depth
     *
     * @return array
     *
     * @since 1.0.0
     */
    public static function getAll(int $relations = RelationType::ALL, int $depth = 3) : array
    {
        $result = self::get(null, $relations, $depth);

        if (\is_object($result)) {
            if (\stripos(\get_class($result), '\Null') !== false) {
                return [];
            }
        }

        return !\is_array($result) ? [$result] : $result;
    }

    /**
     * Get object.
     *
     * @param int $relations Load relations
     * @param int $depth     Relation depth
     *
     * @return array
     *
     * @since 1.0.0
     */
    public static function getAllArray(int $relations = RelationType::ALL, int $depth = 3) : array
    {
        $result = self::getArray(null, $relations, $depth);

        return !\is_array(\reset($result)) ? [$result] : $result;
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
     *
     * @return array
     *
     * @since 1.0.0
     */
    public static function getNewest(int $limit = 1, Builder $query = null, int $relations = RelationType::ALL, int $depth = 3) : array
    {
        $query ??= self::getQuery(null, [], $relations, $depth);
        $query->limit($limit);

        if (!empty(static::$createdAt)) {
            $query->orderBy(static::$table  . '_' . $depth . '.' . static::$columns[static::$createdAt]['name'], 'DESC');
        } else {
            $query->orderBy(static::$table  . '_' . $depth . '.' . static::$columns[static::$primaryField]['name'], 'DESC');
        }

        return self::getAllByQuery($query, $relations, $depth);
    }

    /**
     * Parent parent.
     *
     * @param int   $depth Relation depth
     * @param mixed $value Parent value id
     *
     * @return array
     *
     * @since 1.0.0
     */
    public static function getByParent($value, int $depth = 3) : array
    {
        $query = self::getQuery();
        $query->where(static::$table . '_' . $depth . '.' . static::$parent, '=', $value);

        return self::getAllByQuery($query, RelationType::ALL, $depth);
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
        $result = self::get(null, $relations, $depth, null, $query);

        if (\is_object($result)) {
            if (\stripos(\get_class($result), '\Null') !== false) {
                return [];
            }
        }

        return !\is_array($result) ? [$result] : $result;
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
        $query->random(static::$primaryField)
            ->limit($amount);

        return self::getAllByQuery($query, $relations, $depth, null, $query);
    }

    /**
     * Fill object with relations
     *
     * @param mixed $obj       Object to fill
     * @param int   $relations Relations type
     * @param int   $depth     Relation depth
     *
     * @return void
     *
     * @since 1.0.0
     */
    public static function fillRelations($obj, int $relations = RelationType::ALL, int $depth = 3) : void
    {
        if ($depth < 1) {
            return;
        }

        if ($relations === RelationType::NONE) {
            return;
        }

        if (empty(static::$hasMany)) {
            return;
        }

        $key = self::getObjectId($obj);

        if (empty($key)) {
            return;
        }

        // @todo: check if there are more cases where the relation is already loaded with joins etc.
        // there can be pseudo has many elements like localizations. They are has manies but these are already loaded with joins!
        $hasRealHasMany = false;
        foreach (static::$hasMany as $many) {
            if (!isset($many['column'])) {
                $hasRealHasMany = true;
                break;
            }
        }

        if (!$hasRealHasMany) {
            return;
        }

        // todo: let hasmanyraw return the full data already and let populatemanytomany just fill it!
        // todo: create a get has many raw id function because sometimes we need this (see update function)
        self::populateManyToMany(self::getHasManyRaw($key, $relations), $obj, $depth);
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

        if (empty(static::$hasMany)) {
            return;
        }

        $key = $obj[static::$columns[static::$primaryField]['internal']];

        if (empty($key)) {
            return;
        }

        /* loading relations from relations table and populating them and then adding them to the object */
        self::populateManyToManyArray(self::getHasManyRaw($key, $relations), $obj, $depth);
    }

    /**
     * Get object.
     *
     * @param mixed        $keys      Key
     * @param int          $relations Relations type
     * @param int          $depth     Relation depth
     * @param null|string  $ref       Ref (for getBy and getFor)
     * @param null|Builder $query     Query
     *
     * @return array
     *
     * @since 1.0.0
     */
    public static function getRaw($keys, int $relations = RelationType::ALL, int $depth = 3, string $ref = null, Builder $query = null) : array
    {
        $comparison = \is_array($keys) && \count($keys) > 1 ? 'in' : '=';
        $keys       = $comparison === 'in' ? $keys : \reset($keys);

        $query ??= self::getQuery(null, [], $relations, $depth);
        $hasBy   = $ref === null ? false : isset(static::$columns[self::getColumnByMember($ref)]);

        if ($ref === null || $hasBy) {
            $ref = $ref === null || !$hasBy ? static::$primaryField : static::$columns[self::getColumnByMember($ref)]['name'];

            if ($keys !== null && $keys !== false) {
                $query->where(static::$table . '_' . $depth . '.' . $ref, $comparison, $keys);
            }
        } else {
            if (isset(static::$hasMany[$ref])) {
                if ($keys !== null && $keys !== false) {
                    $query->where(static::$hasMany[$ref]['table'] . '_' . $depth . '.' . static::$hasMany[$ref]['external'], $comparison, $keys);
                }

                $query->leftJoin(static::$hasMany[$ref]['table'], static::$hasMany[$ref]['table'] . '_' . $depth);
                if (static::$hasMany[$ref]['external'] !== null) {
                    $query->on(
                        static::$table . '_' . $depth . '.' . static::$hasMany[$ref][static::$primaryField], '=',
                        static::$hasMany[$ref]['table'] . '_' . $depth . '.' . static::$hasMany[$ref]['self'], 'and',
                        static::$hasMany[$ref]['table'] . '_' . $depth
                    );
                } else {
                    $query->on(
                        static::$table . '_' . $depth . '.' . static::$primaryField, '=',
                        static::$hasMany[$ref]['table'] . '_' . $depth . '.' . static::$hasMany[$ref]['self'], 'and',
                        static::$hasMany[$ref]['table'] . '_' . $depth
                    );
                }
            } elseif (isset(static::$belongsTo[$ref]) && static::$belongsTo[$ref]['external'] !== null) {
                if ($keys !== null && $keys !== false) {
                    $query->where(static::$table . '_' . $depth . '.' . $ref, $comparison, $keys);
                }

                $query->leftJoin(static::$belongsTo[$ref]['mapper']::getTable(), static::$belongsTo[$ref]['mapper']::getTable() . '_' . $depth)
                    ->on(
                        static::$table . '_' . $depth . '.' . static::$belongsTo[$ref]['external'], '=',
                        static::$belongsTo[$ref]['mapper']::getTable() . '_' . $depth . '.' . static::$belongsTo[$ref]['mapper']::getPrimaryField() , 'and',
                        static::$belongsTo[$ref]['mapper']::getTable() . '_' . $depth
                    );
            } elseif (isset(static::$ownsOne[$ref]) && static::$ownsOne[$ref]['external'] !== null) {
                if ($keys !== null && $keys !== false) {
                    $query->where(static::$table . '_' . $depth . '.' . $ref, $comparison, $keys);
                }

                $query->leftJoin(static::$ownsOne[$ref]['mapper']::getTable(), static::$ownsOne[$ref]['mapper']::getTable() . '_' . $depth)
                    ->on(
                        static::$table . '_' . $depth . '.' . static::$ownsOne[$ref]['external'], '=',
                        static::$ownsOne[$ref]['mapper']::getTable() . '_' . $depth . '.' . static::$ownsOne[$ref]['mapper']::getPrimaryField() , 'and',
                        static::$ownsOne[$ref]['mapper']::getTable() . '_' . $depth
                    );
            }
        }

        try {
            $sth = self::$db->con->prepare($query->toSql());
            $sth->execute();
            $results = $sth->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\Throwable $t) {
            $results = false;
            \var_dump($query->toSql());
            \var_dump($t->getMessage());
        }

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
    public static function getHasManyPrimaryKeys($refKey, string $ref) : array
    {
        $query = new Builder(self::$db);
        $query->select(static::$hasMany[$ref]['table'] . '.' . static::$hasMany[$ref]['self'])
            ->from(static::$hasMany[$ref]['table'])
            ->where(static::$hasMany[$ref]['table'] . '.' . static::$hasMany[$ref]['external'], '=', $refKey);

        $sth = self::$db->con->prepare($query->toSql());
        $sth->execute();

        $result = $sth->fetchAll(\PDO::FETCH_NUM);

        return $result === false ? [] : \array_column($result, 0);
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
        $result       = [];
        $cachedTables = []; // used by conditionals

        self::$relations = $relations;

        foreach (static::$hasMany as $member => $value) {
            if ($value['writeonly'] ?? false === true) {
                continue;
            }

            if (!isset($cachedTables[$value['table']])) {
                $query = new Builder(self::$db);

                if (self::$relations === RelationType::ALL) {
                    /** @var string $primaryField */
                    $src = $value['external'] ?? $value['mapper']::$primaryField;

                    $query->select($value['table'] . '.' . $src)
                        ->from($value['table'])
                        ->where($value['table'] . '.' . $value['self'], '=', $primaryKey);

                    $modelName = $value['mapper']::getModelName();
                    foreach (self::$conditionals as $condKey => $condValue) {
                        if (($column = $value['mapper']::getColumnByMember($condKey)) === null
                            || ($condValue['models'] !== null && !\in_array($modelName, $condValue['models']))
                            || ($value['conditional'] ?? false) === false
                        ) {
                            continue;
                        }

                        $query->andWhere($value['table'] . '.' . $column, $condValue['comparison'], $condValue['value']);
                    }
                }

                $sth = self::$db->con->prepare($query->toSql());
                $sth->execute();

                $cachedTables[$value['table']] = $sth->fetchAll(\PDO::FETCH_COLUMN);
            }

            $result[$member] = $cachedTables[$value['table']];
        }

        return $result;
    }

    /**
     * Get mapper specific builder
     *
     * @param Builder $query     Query to fill
     * @param array   $columns   Columns to use
     * @param int     $relations Which relations should be considered in the query
     * @param int     $depth     Depths of the relations to be considered
     *
     * @return Builder
     *
     * @since 1.0.0
     */
    public static function getQuery(Builder $query = null, array $columns = [], int $relations = RelationType::ALL, int $depth = 3) : Builder
    {
        $query ??= new Builder(self::$db);

        if (empty($columns)) {
            $columns = static::$columns;
        }

        self::$relations = $relations;

        foreach ($columns as $key => $values) {
            if ($values['writeonly'] ?? false === false) {
                $query->selectAs(static::$table . '_' . $depth . '.' . $key, $key . '_' . $depth);
            }
        }

        if (empty($query->from)) {
            $query->fromAs(static::$table, static::$table . '_' . $depth);
        }

        // handle conditional
        $modelName = self::getModelName();
        foreach (self::$conditionals as $condKey => $condValue) {
            if (($column = self::getColumnByMember($condKey)) === null
                || ($condValue['models'] !== null && !\in_array($modelName, $condValue['models']))
            ) {
                continue;
            }

            $query->andWhere(static::$table . '_' . $depth . '.' . $column, $condValue['comparison'], $condValue['value']);
        }

        // get OwnsOneQuery
        if ($depth > 1 && self::$relations === RelationType::ALL) {
            foreach (static::$ownsOne as $rel) {
                $query->leftJoin($rel['mapper']::getTable(), $rel['mapper']::getTable() . '_' . ($depth - 1))
                    ->on(
                        static::$table . '_' . $depth . '.' . $rel['external'], '=',
                        $rel['mapper']::getTable() . '_' . ($depth - 1) . '.' . (
                            isset($rel['by']) ? $rel['mapper']::getColumnByMember($rel['by']) : $rel['mapper']::getPrimaryField()
                        ), 'and',
                        $rel['mapper']::getTable() . '_' . ($depth - 1)
                    );

                $query = $rel['mapper']::getQuery(
                    $query,
                    isset($rel['column']) ? [$rel['mapper']::getColumnByMember($rel['column']) => []] : [],
                    self::$relations,
                    $depth - 1
                );
            }
        }

        // get BelognsToQuery
        if ($depth > 1 && self::$relations === RelationType::ALL) {
            foreach (static::$belongsTo as $rel) {
                $query->leftJoin($rel['mapper']::getTable(), $rel['mapper']::getTable() . '_' . ($depth - 1))
                    ->on(
                        static::$table . '_' . $depth . '.' . $rel['external'], '=',
                        $rel['mapper']::getTable() . '_' . ($depth - 1) . '.' . (
                            isset($rel['by']) ? $rel['mapper']::getColumnByMember($rel['by']) : $rel['mapper']::getPrimaryField()
                        ), 'and',
                        $rel['mapper']::getTable() . '_' . ($depth - 1)
                    );

                $query = $rel['mapper']::getQuery(
                    $query,
                    isset($rel['column']) ? [$rel['mapper']::getColumnByMember($rel['column']) => []] : [],
                    self::$relations,
                    $depth - 1
                );
            }
        }

        // get HasManyQuery (but only for elements which have a 'column' defined)
        if ($depth > 1 && self::$relations === RelationType::ALL) {
            foreach (static::$hasMany as $rel) {
                if (isset($rel['external']) || !isset($rel['column'])) { // @todo: conflict with getHasMany()???!?!?!?!
                    continue;
                }

                // todo: handle self and self === null
                $query->leftJoin($rel['mapper']::getTable(), $rel['mapper']::getTable() . '_' . ($depth - 1))
                    ->on(
                        static::$table . '_' . $depth . '.' . ($rel['external'] ?? static::$primaryField), '=',
                        $rel['mapper']::getTable() . '_' . ($depth - 1) . '.' . (
                            isset($rel['by']) ? $rel['mapper']::getColumnByMember($rel['by']) : $rel['self']
                        ), 'and',
                        $rel['mapper']::getTable() . '_' . ($depth - 1)
                    );

                $query = $rel['mapper']::getQuery(
                    $query,
                    isset($rel['column']) ? [$rel['mapper']::getColumnByMember($rel['column']) => []] : [],
                    self::$relations,
                    $depth - 1
                );
            }
        }

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
     * @param int    $depth  Model depth
     *
     * @return void
     *
     * @since 1.0.0
     */
    private static function addInitialized(string $mapper, $id, object $obj = null, int $depth = 3) : void
    {
        if (!isset(self::$initObjects[$mapper])) {
            self::$initObjects[$mapper] = [];
        }

        self::$initObjects[$mapper][$id] = [
            'obj'      => $obj,
            'relation' => self::$relations,
            'depth'    => $depth,
        ];
    }

    /**
     * Add initialized object to local cache
     *
     * @param string $mapper Mapper name
     * @param mixed  $id     Object id
     * @param array  $obj    Model to cache locally
     * @param int    $depth  Model depth
     *
     * @return void
     *
     * @since 1.0.0
     */
    private static function addInitializedArray(string $mapper, $id, array $obj = null, int $depth = 3) : void
    {
        if (!isset(self::$initArrays[$mapper])) {
            self::$initArrays[$mapper] = [];
        }

        self::$initArrays[$mapper][$id] = [
            'obj'      => $obj,
            'relation' => self::$relations,
            'depth'    => $depth,
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
    private static function isInitialized(string $mapper, $id, int $depth = 3) : bool
    {
        return !empty($id)
            && isset(self::$initObjects[$mapper], self::$initObjects[$mapper][$id])
            && self::$initObjects[$mapper][$id]['relation'] >= self::$relations
            && self::$initObjects[$mapper][$id]['depth'] >= $depth;
    }

    /**
     * Check if a object is initialized
     *
     * @param string $mapper Mapper name
     * @param mixed  $id     Object id
     * @param int    $depth  Model depth
     *
     * @return bool
     *
     * @since 1.0.0
     */
    private static function isInitializedArray(string $mapper, $id, int $depth = 3) : bool
    {
        return !empty($id)
            && isset(self::$initArrays[$mapper], self::$initArrays[$mapper][$id])
            && self::$initArrays[$mapper][$id]['relation'] >= self::$relations
            && self::$initArrays[$mapper][$id]['depth'] >= $depth;
    }

    /**
     * Get initialized object
     *
     * @param string $mapper Mapper name
     * @param mixed  $id     Object id
     * @param int    $depth  Depth
     *
     * @return mixed
     *
     * @since 1.0.0
     */
    private static function getInitialized(string $mapper, $id, int $depth)
    {
        if (!self::isInitialized($mapper, $id, $depth)) {
            return null;
        }

        return self::$initObjects[$mapper][$id]['obj'] ?? null;
    }

    /**
     * Get initialized object
     *
     * @param string $mapper Mapper name
     * @param mixed  $id     Object id
     * @param int    $depth  Depth
     *
     * @return mixed
     *
     * @since 1.0.0
     */
    private static function getInitializedArray(string $mapper, $id, int $depth)
    {
        if (!self::isInitializedArray($mapper, $id, $depth)) {
            return null;
        }

        return self::$initArrays[$mapper][$id]['obj'] ?? null;
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
        if (isset(self::$initObjects[$mapper][$id])) {
            unset(self::$initObjects[$mapper][$id]);
        }

        if (isset(self::$initArrays[$mapper][$id])) {
            unset(self::$initArrays[$mapper][$id]);
        }
    }

    /**
     * Clear cache
     *
     * @return mixed
     *
     * @since 1.0.0
     */
    public static function clearCache()
    {
        self::$initObjects = [];
        self::$initArrays  = [];
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
        foreach (static::$columns as $cName => $column) {
            if ($column['internal'] === $name) {
                return $cName;
            }
        }

        return null;
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
        $class     = empty(static::$model) ? \substr(static::class, 0, -6) : static::$model;
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
        $class = empty(static::$model) ? \substr(static::class, 0, -6) : static::$model;

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
    private static function getModelName() : string
    {
        return empty(static::$model) ? \substr(static::class, 0, -6) : static::$model;
    }
}

/* C0: setup for C1
CREATE TABLE IF NOT EXISTS `tag` (
  `tag_id` int(11) NOT NULL,
  `tag_bla` int(11) NULL,
  PRIMARY KEY (`tag_id`)
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `tag_l11n` (
  `tag_l11n_id` int(11) NOT NULL,
  `tag_l11n_tag` int(11) NOT NULL,
  `tag_l11n_title` varchar(250) NOT NULL,
  `tag_l11n_language` varchar(2) NOT NULL,
  PRIMARY KEY (`tag_l11n_id`)
) DEFAULT CHARSET=utf8;

INSERT INTO `tag` (`tag_id`) VALUES
  ('1'), ('2'), ('3');

INSERT INTO `tag_l11n` (`tag_l11n_id`, `tag_l11n_tag`, `tag_l11n_title`, `tag_l11n_language`) VALUES
  ('1', '1', 'German', 'de'),
  ('2', '2', 'German', 'de'),
  ('3', '1', 'English', 'en'),
  ('4', '1', 'Italian', 'it'),
  ('5', '3', 'German', 'de'),
  ('6', '2', 'English', 'en'),
  ('7', '3', 'Spanish', 'sp');

*/

/* C1: conditional values with priorities
https://dbfiddle.uk/?rdbms=mariadb_10.4&fiddle=54359372c3481cfee85f423af76665e4
SELECT
    `tag_3`.`tag_id` as tag_id_3, `tag_3`.`tag_bla` as tag_bla_3,
    `tag_l11n_2`.`tag_l11n_title` as tag_l11n_title_2, `tag_l11n_2`.`tag_l11n_language`
FROM
    `tag` as tag_3
LEFT JOIN
    `tag_l11n` as tag_l11n_2 ON `tag_3`.`tag_id` = `tag_l11n_2`.`tag_l11n_tag`
WHERE (
    `tag_l11n_2`.`tag_l11n_language` = 'it'
OR (
    `tag_l11n_2`.`tag_l11n_language` = 'en'
    AND NOT EXISTS (SELECT *
                    FROM tag_l11n t3
                    WHERE t3.tag_l11n_tag = tag_3.tag_id
                    AND t3.tag_l11n_language = 'it')
)
OR (
    NOT EXISTS (SELECT *
                    FROM tag_l11n t3
                    WHERE t3.tag_l11n_tag = tag_3.tag_id
                    AND t3.tag_l11n_language in ('en', 'it')))
)
GROUP BY tag_id_3
ORDER BY
    `tag_3`.`tag_id` ASC
LIMIT 25;
*/

/* C2: try this
SELECT
    `tag_3`.`tag_id` as tag_id_3,
    COALESCE(`tag_l11n_2`.`tag_l11n_title`, `tag_l11n_3`.`tag_l11n_title`, `tag_l11n_4`.`tag_l11n_title`) as tag_l11n_title_2
FROM
    `tag` as tag_3
LEFT JOIN
    `tag_l11n` as tag_l11n_2 ON `tag_3`.`tag_id` = `tag_l11n_2`.`tag_l11n_tag`
                            AND `tag_l11n_2`.`tag_l11n_language` = 'it'
LEFT JOIN
    `tag_l11n` as tag_l11n_3 ON `tag_3`.`tag_id` = `tag_l11n_3`.`tag_l11n_tag`
                            AND `tag_l11n_3`.`tag_l11n_language` = 'en'
LEFT JOIN
    `tag_l11n` as tag_l11n_4 ON `tag_3`.`tag_id` = `tag_l11n_4`.`tag_l11n_tag`
                            AND `tag_l11n_4`.`tag_l11n_language` NOT IN ('en', 'it')
ORDER BY
    `tag_3`.`tag_id` ASC
LIMIT 25;
*/
