<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\DataStorage\Database\Mapper
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://karaka.app
 */
declare(strict_types=1);

namespace phpOMS\DataStorage\Database\Mapper;

use phpOMS\DataStorage\Database\Query\Builder;
use phpOMS\DataStorage\Database\Query\Where;
use phpOMS\Utils\ArrayUtils;

/**
 * Read mapper (SELECTS).
 *
 * @package phpOMS\DataStorage\Database\Mapper
 * @license OMS License 1.0
 * @link    https://karaka.app
 * @since   1.0.0
 */
final class ReadMapper extends DataMapperAbstract
{
    /**
     * Columns to load
     *
     * @var array
     * @since 1.0.0
     */
    private array $columns = [];

    /**
     * Create get mapper
     *
     * This makes execute() return a single object or an array of object depending the result size
     *
     * @return self
     *
     * @since 1.0.0
     */
    public function get() : self
    {
        $this->type = MapperType::GET;

        return $this;
    }

    /**
     * Get raw result set
     *
     * @return self
     *
     * @since 1.0.0
     */
    public function getRaw() : self
    {
        $this->type = MapperType::GET_RAW;

        return $this;
    }

    /**
     * Create get mapper
     *
     * This makes execute() always return an array of objects (or an empty array)
     *
     * @return self
     *
     * @since 1.0.0
     */
    public function getAll() : self
    {
        $this->type = MapperType::GET_ALL;

        return $this;
    }

    /**
     * Create count mapper
     *
     * @return self
     *
     * @since 1.0.0
     */
    public function count() : self
    {
        $this->type = MapperType::COUNT_MODELS;

        return $this;
    }

    /**
     * Create random mapper
     *
     * @return self
     *
     * @since 1.0.0
     */
    public function getRandom() : self
    {
        $this->type = MapperType::GET_RANDOM;

        return $this;
    }

    /**
     * Create find mapper
     *
     * @return self
     *
     * @since 1.0.0
     */
    public function find() : self
    {
        $this->type = MapperType::FIND;

        return $this;
    }

    /**
     * Define the columns to load
     *
     * @param array $columns Columns to load
     *
     * @return self
     *
     * @since 1.0.0
     * @todo: consider to accept properties instead and then check ::COLUMNS which contian the property and ADD that array into $this->columns. Maybe also consider a rename from columns() to property()
     */
    public function columns(array $columns) : self
    {
        $this->columns = $columns;

        return $this;
    }

    /**
     * Execute mapper
     *
     * @param mixed ...$options Options to pass to read mapper
     *
     * @return mixed
     *
     * @since 1.0.0
     */
    public function execute(...$options) : mixed
    {
        switch($this->type) {
            case MapperType::GET:
                /** @var null|Builder ...$options */
                return $this->executeGet(...$options);
            case MapperType::GET_RAW:
                /** @var null|Builder ...$options */
                return $this->executeGetRaw(...$options);
            case MapperType::GET_ALL:
                /** @var null|Builder ...$options */
                return $this->executeGetAll(...$options);
            case MapperType::GET_RANDOM:
                return $this->executeGetRaw();
            case MapperType::COUNT_MODELS:
                return $this->executeCount();
            default:
                return null;
        }
    }

    /**
     * Execute mapper
     *
     * @param null|Builder $query Query to use instead of the internally generated query
     *                            Careful, this doesn't merge with the internal query.
     *                            If you want to merge it use ->query() instead
     *
     * @return object|array
     *
     * @todo: consider to always return an array, this way we could remove executeGetAll
     * @since 1.0.0
     */
    public function executeGet(Builder $query = null) : mixed
    {
        $primaryKeys          = [];
        $memberOfPrimaryField = $this->mapper::COLUMNS[$this->mapper::PRIMARYFIELD]['internal'];
        $emptyWhere           = empty($this->where);

        if (isset($this->where[$memberOfPrimaryField])) {
            $keys        = $this->where[$memberOfPrimaryField][0]['value'];
            $primaryKeys = \array_merge(\is_array($keys) ? $keys : [$keys], $primaryKeys);
        }

        // Get initialized objects from memory cache.
        $obj = [];

        // Get remaining objects (not available in memory cache) or remaining where clauses.
        if (!empty($primaryKeys) || (!empty($this->where) || $emptyWhere)) {
            $dbData = $this->executeGetRaw($query);

            foreach ($dbData as $row) {
                $value       = $row[$this->mapper::PRIMARYFIELD . '_d' . $this->depth];
                $obj[$value] = $this->mapper::createBaseModel();

                $obj[$value] = $this->populateAbstract($row, $obj[$value]);
                $this->loadHasManyRelations($obj[$value]);
            }
        }

        $countResulsts = \count($obj);

        if ($countResulsts === 0) {
            return $this->mapper::createNullModel();
        } elseif ($countResulsts === 1) {
            return \reset($obj);
        }

        return $obj;
    }

    /**
     * Execute mapper
     *
     * @param null|Builder $query Query to use instead of the internally generated query
     *                            Careful, this doesn't merge with the internal query.
     *                            If you want to merge it use ->query() instead
     *
     * @return array
     *
     * @since 1.0.0
     */
    public function executeGetRaw(Builder $query = null) : array
    {
        $query ??= $this->getQuery();

        try {
            $results = false;

            $sth = $this->db->con->prepare($a = $query->toSql());
            if ($sth !== false) {
                $sth->execute();
                $results = $sth->fetchAll(\PDO::FETCH_ASSOC);
            }
        } catch (\Throwable $t) {
            $results = false;

            \var_dump($q = $query->toSql());
            \var_dump($t->getMessage());
        }

        return $results === false ? [] : $results;
    }

    /**
     * Execute mapper
     *
     * @param null|Builder $query Query to use instead of the internally generated query
     *                            Careful, this doesn't merge with the internal query.
     *                            If you want to merge it use ->query() instead
     *
     * @return array
     *
     * @since 1.0.0
     */
    public function executeGetAll(Builder $query = null) : array
    {
         $result = $this->executeGet($query);

        if (\is_object($result) && \stripos(\get_class($result), '\Null') !== false) {
            return [];
        }

        return !\is_array($result) ? [$result] : $result;
    }

    /**
     * Count the number of elements
     *
     * @return int
     *
     * @since 1.0.0
     */
    public function executeCount() : int
    {
        $query = $this->getQuery(null, ['COUNT(*)' => 'count']);

        return (int) $query->execute()->fetchColumn();
    }

    /**
     * Get random object
     *
     * @return mixed
     *
     * @since 1.0.0
     */
    public function executeRandom() : mixed
    {
        $query = $this->getQuery();
        $query->random($this->mapper::PRIMARYFIELD);

        return $this->executeGet($query);
    }

    /**
     * Get mapper specific builder
     *
     * @param Builder $query   Query to fill
     * @param array   $columns Columns to use
     *
     * @return Builder
     *
     * @since 1.0.0
     */
    public function getQuery(Builder $query = null, array $columns = []) : Builder
    {
        $query ??= $this->query ?? new Builder($this->db, true);
        $columns = empty($columns)
            ? (empty($this->columns) ? $this->mapper::COLUMNS : $this->columns)
            : $columns;

        foreach ($columns as $key => $values) {
            if (\is_string($values)) {
                $query->selectAs($key, $values);
            } else {
                if (($values['writeonly'] ?? false) === false || isset($this->with[$values['internal']])) {
                    $query->selectAs($this->mapper::TABLE . '_d' . $this->depth . '.' . $key, $key . '_d' . $this->depth);
                }
            }
        }

        if (empty($query->from)) {
            $query->fromAs($this->mapper::TABLE, $this->mapper::TABLE . '_d' . $this->depth);
        }

        // where
        foreach ($this->where as $member => $values) {
            // handle where query
            if ($member === '' && $values[0]['value'] instanceof Where) {
                $query->where($values[0]['value'], boolean: $values[0]['comparison']);

                continue;
            }

            if (($col = $this->mapper::getColumnByMember($member)) !== null) {
                /* variable in model */
                foreach ($values as $where) {
                    // @todo: the has many, etc. if checks only work if it is a relation on the first level, if we have a deeper where condition nesting this fails
                    if ($where['child'] !== '') {
                        continue;
                    }

                    $comparison = \is_array($where['value']) && \count($where['value']) > 1 ? 'in' : $where['logic'];
                    $query->where($this->mapper::TABLE . '_d' . $this->depth . '.' . $col, $comparison, $where['value'], $where['comparison']);
                }
            } /* elseif (isset($this->mapper::HAS_MANY[$member])) {
                // variable in has many
                // @todo: maybe needed in the future, but needs adjustment, doesn't make sense at the moment
                foreach ($values as $where) {
                    // @todo: the has many, etc. if checks only work if it is a relation on the first level, if we have a deeper where condition nesting this fails
                    if ($where['child'] !== '') {
                        continue;
                    }

                    $comparison = \is_array($where['value']) && \count($where['value']) > 1 ? 'in' : $where['logic'];
                    $query->where($this->mapper::HAS_MANY[$member]['table'] . '_d' . $this->depth . '.' . $this->mapper::HAS_MANY[$member]['external'], $comparison, $where['value'], $where['comparison']);

                    $query->leftJoin($this->mapper::HAS_MANY[$member]['table'], $this->mapper::HAS_MANY[$member]['table'] . '_d' . $this->depth);
                    if ($this->mapper::HAS_MANY[$member]['external'] !== null) {
                        $query->on(
                            $this->mapper::TABLE . '_d' . $this->depth . '.' . $this->mapper::HAS_MANY[$member][$this->mapper::PRIMARYFIELD], '=',
                            $this->mapper::HAS_MANY[$member]['table'] . '_d' . $this->depth . '.' . $this->mapper::HAS_MANY[$member]['self'], 'AND',
                            $this->mapper::HAS_MANY[$member]['table'] . '_d' . $this->depth
                        );
                    } else {
                        $query->on(
                            $this->mapper::TABLE . '_d' . $this->depth . '.' . $this->mapper::PRIMARYFIELD, '=',
                            $this->mapper::HAS_MANY[$member]['table'] . '_d' . $this->depth . '.' . $this->mapper::HAS_MANY[$member]['self'], 'AND',
                            $this->mapper::HAS_MANY[$member]['table'] . '_d' . $this->depth
                        );
                    }
                }

            } */ /* elseif (isset($this->mapper::BELONGS_TO[$member])) {
                // variable in belogns to
                // @todo: maybe needed in the future, but needs adjustment, doesn't make sense at the moment
                foreach ($values as $index => $where) {
                    // @todo: the has many, etc. if checks only work if it is a relation on the first level, if we have a deeper where condition nesting this fails
                    if ($where['child'] !== '') {
                        continue;
                    }

                    $comparison = \is_array($where['value']) && \count($where['value']) > 1 ? 'in' : $where['logic'];
                    $query->where($this->mapper::TABLE . '_d' . $this->depth . '.' . $member, $comparison, $where['value'], $where['comparison']);

                    $query->leftJoin($this->mapper::BELONGS_TO[$member]['mapper']::TABLE, $this->mapper::BELONGS_TO[$member]['mapper']::TABLE . '_d' . $this->depth)
                        ->on(
                            $this->mapper::TABLE . '_d' . $this->depth . '.' . $this->mapper::BELONGS_TO[$member]['external'], '=',
                            $this->mapper::BELONGS_TO[$member]['mapper']::TABLE . '_d' . $this->depth . '.' . $this->mapper::BELONGS_TO[$member]['mapper']::PRIMARYFIELD , 'AND',
                            $this->mapper::BELONGS_TO[$member]['mapper']::TABLE . '_d' . $this->depth
                        );
                }
            } */ /* elseif (isset($this->mapper::OWNS_ONE[$member])) {
                // variable in owns one
                // @todo: maybe needed in the future, but needs adjustment, doesn't make sense at the moment
                foreach ($values as $index => $where) {
                    // @todo: the has many, etc. if checks only work if it is a relation on the first level, if we have a deeper where condition nesting this fails
                    if ($where['child'] !== '') {
                        continue;
                    }

                    $comparison = \is_array($where['value']) && \count($where['value']) > 1 ? 'in' : $where['logic'];
                    $query->where($this->mapper::TABLE . '_d' . $this->depth . '.' . $member, $comparison, $where['value'], $where['comparison']);
                    $query->leftJoin($this->mapper::OWNS_ONE[$member]['mapper']::TABLE, $this->mapper::OWNS_ONE[$member]['mapper']::TABLE . '_d' . $this->depth)
                        ->on(
                            $this->mapper::TABLE . '_d' . $this->depth . '.' . $this->mapper::OWNS_ONE[$member]['external'], '=',
                            $this->mapper::OWNS_ONE[$member]['mapper']::TABLE . '_d' . $this->depth . '.' . $this->mapper::OWNS_ONE[$member]['mapper']::PRIMARYFIELD , 'AND',
                            $this->mapper::OWNS_ONE[$member]['mapper']::TABLE . '_d' . $this->depth
                        );
                }
            } */
        }

        // load relations
        foreach ($this->with as $member => $data) {
            $rel = null;
            if ((isset($this->mapper::OWNS_ONE[$member]) || isset($this->mapper::BELONGS_TO[$member]))
                || (!isset($this->mapper::HAS_MANY[$member]['external']) && isset($this->mapper::HAS_MANY[$member]['column']))
            ) {
                $rel = $this->mapper::OWNS_ONE[$member] ?? ($this->mapper::BELONGS_TO[$member] ?? ($this->mapper::HAS_MANY[$member] ?? null));
            } else {
                continue;
            }

            foreach ($data as $with) {
                if ($with['child'] !== '') {
                    continue;
                }

                if (isset($this->mapper::OWNS_ONE[$member]) || isset($this->mapper::BELONGS_TO[$member])) {
                    $query->leftJoin($rel['mapper']::TABLE, $rel['mapper']::TABLE . '_d' . ($this->depth + 1))
                        ->on(
                            $this->mapper::TABLE . '_d' . $this->depth . '.' . $rel['external'], '=',
                            $rel['mapper']::TABLE . '_d' . ($this->depth + 1) . '.' . (
                                isset($rel['by']) ? $rel['mapper']::getColumnByMember($rel['by']) : $rel['mapper']::PRIMARYFIELD
                            ), 'and',
                            $rel['mapper']::TABLE . '_d' . ($this->depth + 1)
                        );
                } elseif (!isset($this->mapper::HAS_MANY[$member]['external']) && isset($this->mapper::HAS_MANY[$member]['column'])) {
                    // get HasManyQuery (but only for elements which have a 'column' defined)

                    // @todo: handle self and self === null
                    $query->leftJoin($rel['mapper']::TABLE, $rel['mapper']::TABLE . '_d' . ($this->depth + 1))
                        ->on(
                            $this->mapper::TABLE . '_d' . $this->depth . '.' . ($rel['external'] ?? $this->mapper::PRIMARYFIELD), '=',
                            $rel['mapper']::TABLE . '_d' . ($this->depth + 1) . '.' . (
                                isset($rel['by']) ? $rel['mapper']::getColumnByMember($rel['by']) : $rel['self']
                            ), 'and',
                            $rel['mapper']::TABLE . '_d' . ($this->depth + 1)
                        );
                }

                /** @var self $relMapper */
                $relMapper        = $this->createRelationMapper($rel['mapper']::reader(db: $this->db), $member);
                $relMapper->depth = $this->depth + 1;

                $query = $relMapper->getQuery(
                    $query,
                    isset($rel['column']) ? [$rel['mapper']::getColumnByMember($rel['column']) => []] : []
                );

                break; // there is only one root element (one element with child === '')
            }
        }

        // handle sort, the column name order is very important. Therefore it cannot be done in the foreach loop above!
        foreach ($this->sort as $member => $data) {
            foreach ($data as $sort) {
                if (($column = $this->mapper::getColumnByMember($member)) === null
                    || ($sort['child'] !== '')
                ) {
                    continue;
                }

                $query->orderBy($this->mapper::TABLE . '_d' . $this->depth . '.' . $column, $sort['order']);

                break; // there is only one root element (one element with child === '')
            }
        }

        // handle limit
        foreach ($this->limit as $member => $data) {
            if ($member !== '') {
                continue;
            }

            foreach ($data as $limit) {
                if ($limit['child'] === '') {
                    $query->limit($limit['limit']);

                    break 2; // there is only one root element (one element with child === '')
                }
            }
        }

        return $query;
    }

    /**
     * Populate data.
     *
     * @param array  $result Query result set
     * @param object $obj    Object to populate
     *
     * @return object
     *
     * @since 1.0.0
     */
    public function populateAbstract(array $result, object $obj) : object
    {
        $refClass = new \ReflectionClass($obj);

        foreach ($this->mapper::COLUMNS as $column => $def) {
            $alias = $column . '_d' . $this->depth;

            if (!\array_key_exists($alias, $result)) {
                continue;
            }

            $value = $result[$alias];

            $hasPath   = false;
            $aValue    = [];
            $arrayPath = '';

            if (\stripos($def['internal'], '/') !== false) {
                $hasPath = true;
                $path    = \explode('/', \ltrim($def['internal'], '/'));
                $member  = $path[0];

                $refProp = $refClass->getProperty($path[0]);
                if (!($isPublic = $refProp->isPublic())) {
                    $refProp->setAccessible(true);
                }

                \array_shift($path);
                $arrayPath = \implode('/', $path);
                $aValue    = $isPublic ? $obj->{$path[0]} : $refProp->getValue($obj);
            } else {
                $refProp = $refClass->getProperty($def['internal']);
                $member  = $def['internal'];

                if (!($isPublic = $refProp->isPublic())) {
                    $refProp->setAccessible(true);
                }
            }

            if (isset($this->mapper::OWNS_ONE[$def['internal']])) {
                $default = null;
                if (!isset($this->with[$member]) && $refProp->isInitialized($obj)) {
                    $default = $isPublic ? $obj->{$def['internal']} : $refProp->getValue($obj);
                }

                $value = $this->populateOwnsOne($def['internal'], $result, $default);

                // loads has many relations. other relations are loaded in the populateOwnsOne
                if (\is_object($value) && isset($this->mapper::OWNS_ONE[$def['internal']]['mapper'])) {
                    $this->mapper::OWNS_ONE[$def['internal']]['mapper']::reader(db: $this->db)->loadHasManyRelations($value);
                }

                if (!empty($value)) {
                    // @todo: find better solution. this was because of a bug with the sales billing list query depth = 4. The address was set (from the client, referral or creator) but then somehow there was a second address element which was all null and null cannot be asigned to a string variable (e.g. country). The problem with this solution is that if the model expects an initialization (e.g. at lest set the elements to null, '', 0 etc.) this is now not done.
                    $refProp->setValue($obj, $value);
                }
            } elseif (isset($this->mapper::BELONGS_TO[$def['internal']])) {
                $default = null;
                if (!isset($this->with[$member]) && $refProp->isInitialized($obj)) {
                    $default = $isPublic ? $obj->{$def['internal']} : $refProp->getValue($obj);
                }

                $value = $this->populateBelongsTo($def['internal'], $result, $default);

                // loads has many relations. other relations are loaded in the populateBelongsTo
                if (\is_object($value) && isset($this->mapper::BELONGS_TO[$def['internal']]['mapper'])) {
                    $this->mapper::BELONGS_TO[$def['internal']]['mapper']::reader(db: $this->db)->loadHasManyRelations($value);
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
                $member = $isPublic ? $obj->{$def['internal']} : $refProp->getValue($obj);

                if ($member === null || $value === null) {
                    $obj->{$def['internal']} = $value;
                } else {
                    $member->unserialize($value);
                }
            }

            if (!$isPublic) {
                $refProp->setAccessible(false);
            }
        }

        foreach ($this->mapper::HAS_MANY as $member => $def) {
            $column = $def['mapper']::getColumnByMember($def['column'] ?? $member);
            $alias  = $column . '_d' . ($this->depth + 1);

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

                if (!($isPublic = $refProp->isPublic())) {
                    $refProp->setAccessible(true);
                }

                \array_shift($path);
                $arrayPath = \implode('/', $path);
                $aValue    = $isPublic ? $obj->{$path[0]} : $refProp->getValue($obj);
            } else {
                $refProp = $refClass->getProperty($member);

                if (!($isPublic = $refProp->isPublic())) {
                    $refProp->setAccessible(true);
                }
            }

            if (\in_array($def['mapper']::COLUMNS[$column]['type'], ['string', 'int', 'float', 'bool'])) {
                if ($value !== null || $refProp->getValue($obj) !== null) {
                    \settype($value, $def['mapper']::COLUMNS[$column]['type']);
                }

                if ($hasPath) {
                    $value = ArrayUtils::setArray($arrayPath, $aValue, $value, '/', true);
                }

                $refProp->setValue($obj, $value);
            } elseif ($def['mapper']::COLUMNS[$column]['type'] === 'DateTime') {
                $value = $value === null ? null : new \DateTime($value);
                if ($hasPath) {
                    $value = ArrayUtils::setArray($arrayPath, $aValue, $value, '/', true);
                }

                $refProp->setValue($obj, $value);
            } elseif ($def['mapper']::COLUMNS[$column]['type'] === 'DateTimeImmutable') {
                $value = $value === null ? null : new \DateTimeImmutable($value);
                if ($hasPath) {
                    $value = ArrayUtils::setArray($arrayPath, $aValue, $value, '/', true);
                }

                $refProp->setValue($obj, $value);
            } elseif ($def['mapper']::COLUMNS[$column]['type'] === 'Json') {
                if ($hasPath) {
                    $value = ArrayUtils::setArray($arrayPath, $aValue, $value, '/', true);
                }

                $refProp->setValue($obj, \json_decode($value, true));
            } elseif ($def['mapper']::COLUMNS[$column]['type'] === 'Serializable') {
                $member = $isPublic ? $obj->{$member} : $refProp->getValue($obj);
                $member->unserialize($value);
            }

            if (!$isPublic) {
                $refProp->setAccessible(false);
            }
        }

        return $obj;
    }

    /**
     * Populate data.
     *
     * @param string $member  Member name
     * @param array  $result  Result data
     * @param mixed  $default Default value
     *
     * @return mixed
     *
     * @todo: in the future we could pass not only the $id ref but all of the data as a join!!! and save an additional select!!!
     * @todo: parent and child elements however must be loaded because they are not loaded
     *
     * @since 1.0.0
     */
    public function populateOwnsOne(string $member, array $result, mixed $default = null) : mixed
    {
        /** @var class-string<DataMapperFactory> $mapper */
        $mapper = $this->mapper::OWNS_ONE[$member]['mapper'];

        if (!isset($this->with[$member])) {
            if (\array_key_exists($this->mapper::OWNS_ONE[$member]['external'] . '_d' . ($this->depth), $result)) {
                return isset($this->mapper::OWNS_ONE[$member]['column'])
                    ? $result[$this->mapper::OWNS_ONE[$member]['external'] . '_d' . ($this->depth)]
                    : $mapper::createNullModel($result[$this->mapper::OWNS_ONE[$member]['external'] . '_d' . ($this->depth)]);
            } else {
                return $default;
            }
        }

        if (isset($this->mapper::OWNS_ONE[$member]['column'])) {
            return $result[$mapper::getColumnByMember($this->mapper::OWNS_ONE[$member]['column']) . '_d' . $this->depth];
        }

        if (!isset($result[$mapper::PRIMARYFIELD . '_d' . ($this->depth + 1)])) {
            return $mapper::createNullModel();
        }

        /** @var self $ownsOneMapper */
        $ownsOneMapper        = $this->createRelationMapper($mapper::get($this->db), $member);
        $ownsOneMapper->depth = $this->depth + 1;

        return $ownsOneMapper->populateAbstract($result, $mapper::createBaseModel());
    }

    /**
     * Populate data.
     *
     * @param string $member  Member name
     * @param array  $result  Result data
     * @param mixed  $default Default value
     *
     * @return mixed
     *
     * @todo: in the future we could pass not only the $id ref but all of the data as a join!!! and save an additional select!!!
     * @todo: only the belongs to model gets populated the children of the belongsto model are always null models. either this function needs to call the get for the children, it should call get for the belongs to right away like the has many, or i find a way to recursevily load the data for all sub models and then populate that somehow recursively, probably too complex.
     *
     * @since 1.0.0
     */
    public function populateBelongsTo(string $member, array $result, mixed $default = null) : mixed
    {
        /** @var class-string<DataMapperFactory> $mapper */
        $mapper = $this->mapper::BELONGS_TO[$member]['mapper'];

        if (!isset($this->with[$member])) {
            if (\array_key_exists($this->mapper::BELONGS_TO[$member]['external'] . '_d' . ($this->depth), $result)) {
                return isset($this->mapper::BELONGS_TO[$member]['column'])
                    ? $result[$this->mapper::BELONGS_TO[$member]['external'] . '_d' . ($this->depth)]
                    : $mapper::createNullModel($result[$this->mapper::BELONGS_TO[$member]['external'] . '_d' . ($this->depth)]);
            } else {
                return $default;
            }
        }

        if (isset($this->mapper::BELONGS_TO[$member]['column'])) {
            return $result[$mapper::getColumnByMember($this->mapper::BELONGS_TO[$member]['column']) . '_d' . $this->depth];
        }

        if (!isset($result[$mapper::PRIMARYFIELD . '_d' . ($this->depth + 1)])) {
            return $mapper::createNullModel();
        }

        // get the belongs to based on a different column (not primary key)
        // this is often used if the value is actually a different model:
        //      you want the profile but the account id is referenced
        //      in this case you can get the profile by loading the profile based on the account reference column
        if (isset($this->mapper::BELONGS_TO[$member]['by'])) {
            /** @var self $belongsToMapper */
            $belongsToMapper        = $this->createRelationMapper($mapper::get($this->db), $member);
            $belongsToMapper->depth = $this->depth + 1;
            $belongsToMapper->where(
                $this->mapper::BELONGS_TO[$member]['by'],
                $result[$mapper::getColumnByMember($this->mapper::BELONGS_TO[$member]['by']) . '_d' . ($this->depth + 1)],
                '='
            );

            return $belongsToMapper->execute();
        }

        /** @var self $belongsToMapper */
        $belongsToMapper        = $this->createRelationMapper($mapper::get($this->db), $member);
        $belongsToMapper->depth = $this->depth + 1;

        return $belongsToMapper->populateAbstract($result, $mapper::createBaseModel());
    }

    /**
     * Fill object with relations
     *
     * @param object $obj Object to fill
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function loadHasManyRelations(object $obj) : void
    {
        if (empty($this->with)) {
            return;
        }

        $primaryKey = $this->mapper::getObjectId($obj);
        if (empty($primaryKey)) {
            return;
        }

        $refClass = null;

        // @todo: check if there are more cases where the relation is already loaded with joins etc.
        // there can be pseudo has many elements like localizations. They are has manies but these are already loaded with joins!
        foreach ($this->with as $member => $withData) {
            if (isset($this->mapper::HAS_MANY[$member])) {
                $many = $this->mapper::HAS_MANY[$member];
                if (isset($many['column'])) {
                    continue;
                }

                $query = new Builder($this->db, true);
                $src   = $many['external'] ?? $many['mapper']::PRIMARYFIELD;

                // @todo: what if a specific column name is defined instead of primaryField for the join? Fix, it should be stored in 'column'
                $query->select($many['table'] . '.' . $src)
                    ->from($many['table'])
                    ->where($many['table'] . '.' . $many['self'], '=', $primaryKey);

                if ($many['table'] !== $many['mapper']::TABLE) {
                    $query->leftJoin($many['mapper']::TABLE)
                        ->on($many['table'] . '.' . $src, '=', $many['mapper']::TABLE . '.' . $many['mapper']::PRIMARYFIELD);
                }

                $sth = $this->db->con->prepare($query->toSql());
                if ($sth === false) {
                    continue;
                }

                $sth->execute();
                $result =  $sth->fetchAll(\PDO::FETCH_COLUMN);

                if (empty($result)) {
                    continue;
                }

                $objects = $this->createRelationMapper($many['mapper']::get(db: $this->db), $member)
                    ->where($many['mapper']::COLUMNS[$many['mapper']::PRIMARYFIELD]['internal'], $result, 'in')
                    ->execute();

                if ($refClass === null) {
                    $refClass = new \ReflectionClass($obj);
                }

                $refProp = $refClass->getProperty($member);
                if (!$refProp->isPublic()) {
                    $refProp->setAccessible(true);
                    $refProp->setValue($obj, !\is_array($objects) && ($many['conditional'] ?? false) === false
                        ? [$many['mapper']::getObjectId($objects) => $objects]
                        : $objects // if conditional === true the obj will be asigned (e.g. has many localizations but only one is loaded for the model)
                    );
                    $refProp->setAccessible(false);
                } else {
                    $obj->{$member} = !\is_array($objects) && ($many['conditional'] ?? false) === false
                        ? [$many['mapper']::getObjectId($objects) => $objects]
                        : $objects; // if conditional === true the obj will be asigned (e.g. has many localizations but only one is loaded for the model)
                }

                continue;
            } elseif (isset($this->mapper::OWNS_ONE[$member])
                || isset($this->mapper::BELONGS_TO[$member])
            ) {
                $relation = isset($this->mapper::OWNS_ONE[$member])
                    ? $this->mapper::OWNS_ONE[$member]
                    : $this->mapper::BELONGS_TO[$member];

                if (\count($withData) < 2) {
                    continue;
                }

                if ($refClass === null) {
                    $refClass = new \ReflectionClass($obj);
                }

                /** @var ReadMapper $relMapper */
                $relMapper = $this->createRelationMapper($relation['mapper']::reader($this->db), $member);

                $refProp = $refClass->getProperty($member);
                if (!$refProp->isPublic()) {
                    $refProp->setAccessible(true);
                    $relMapper->loadHasManyRelations($refProp->getValue($obj));
                    $refProp->setAccessible(false);
                } else {
                    $relMapper->loadHasManyRelations($obj->{$member});
                }
            }
        }
    }
}
