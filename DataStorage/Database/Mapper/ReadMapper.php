<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\DataStorage\Database\Mapper
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
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
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 *
 * @todo Add memory cache per read mapper parent call (These should be cached: attribute types, file types, etc.)
 * @todo Add getArray functions to get array instead of object
 * @todo Allow to define columns in all functions instead of members?
 *
 * @template R
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
     * Create yield mapper
     *
     * This makes execute() return a single object or an array of object depending the result size
     *
     * @return self
     *
     * @since 1.0.0
     */
    public function yield() : self
    {
        $this->type = MapperType::GET_YIELD;

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
     * Create sum mapper
     *
     * @return self
     *
     * @since 1.0.0
     */
    public function sum() : self
    {
        $this->type = MapperType::SUM_MODELS;

        return $this;
    }

    /**
     * Create exists mapper
     *
     * @return self
     *
     * @since 1.0.0
     */
    public function exists() : self
    {
        $this->type = MapperType::MODEL_EXISTS;

        return $this;
    }

    /**
     * Create has mapper
     *
     * @return self
     *
     * @since 1.0.0
     */
    public function has() : self
    {
        $this->type = MapperType::MODEL_HAS_RELATION;

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
     * Define the columns to load
     *
     * @param array $columns Columns to load
     *
     * @return self
     *
     * @since 1.0.0
     */
    public function columns(array $columns) : self
    {
        $this->columns = $columns;

        return $this;
    }

    /**
     * Define the properties to load
     *
     * @param array $properties Properties to load
     *
     * @return self
     *
     * @since 1.0.0
     */
    public function properties(array $properties) : self
    {
        foreach ($properties as $property) {
            $this->columns[] = $this->mapper::getColumnByMember($property);
        }

        return $this;
    }

    /**
     * Execute mapper
     *
     * @param mixed ...$options Options to pass to read mapper
     *
     * @return R
     *
     * @since 1.0.0
     */
    public function execute(mixed ...$options) : mixed
    {
        switch($this->type) {
            case MapperType::GET:
                /** @var null|Builder ...$options */
                return $this->executeGet(...$options);
            case MapperType::GET_YIELD:
                /** @var null|Builder ...$options */
                return $this->executeGetYield(...$options);
            case MapperType::GET_RAW:
                /** @var null|Builder ...$options */
                return $this->executeGetRaw(...$options);
            case MapperType::GET_ALL:
                /** @var null|Builder ...$options */
                return $this->executeGetAll(...$options);
            case MapperType::GET_RANDOM:
                return $this->executeRandom();
            case MapperType::COUNT_MODELS:
                return $this->executeCount();
            case MapperType::SUM_MODELS:
                return $this->executeSum();
            case MapperType::MODEL_EXISTS:
                return $this->executeExists();
            case MapperType::MODEL_HAS_RELATION:
                return $this->executeHas();
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
     * @return R
     *
     * @since 1.0.0
     */
    public function executeGet(?Builder $query = null) : mixed
    {
        $primaryKeys          = [];
        $memberOfPrimaryField = $this->mapper::COLUMNS[$this->mapper::PRIMARYFIELD]['internal'];

        if (isset($this->where[$memberOfPrimaryField])) {
            $keys        = $this->where[$memberOfPrimaryField][0]['value'];
            $primaryKeys = \array_merge(\is_array($keys) ? $keys : [$keys], $primaryKeys);
        }

        // Get initialized objects from memory cache.
        $objs = [];
        $indexed = [];

        // Get remaining objects (not available in memory cache) or remaining where clauses.
        //$dbData = $this->executeGetRaw($query);

        foreach ($this->executeGetRawYield($query) as $row) {
            if ($row === []) {
                continue;
            }

            $value       = $row[$this->mapper::PRIMARYFIELD . '_d' . $this->depth];
            $objs[$value] = $this->mapper::createBaseModel($row);
            $objs[$value] = $this->populateAbstract($row, $objs[$value]);

            if (!empty($this->indexedBy) && isset($row[$this->indexedBy . '_d' . $this->depth])) {
                if (!isset($indexed[$row[$this->indexedBy . '_d' . $this->depth]])) {
                    $indexed[$row[$this->indexedBy . '_d' . $this->depth]] = [];
                }

                $indexed[$row[$this->indexedBy . '_d' . $this->depth]][] = $objs[$value];
            }
        }

        if (!empty($this->with) && !empty($objs)) {
            $this->loadHasManyRelationsTest($objs);
        }

        if (!empty($this->indexedBy)) {
            return $indexed;
        }

        $countResults = \count($objs);
        if ($countResults === 0) {
            return $this->mapper::createNullModel();
        } elseif ($countResults === 1) {
            return \reset($objs);
        }

        return $objs;
    }

    /**
     * Execute mapper
     *
     * @param null|Builder $query Query to use instead of the internally generated query
     *                            Careful, this doesn't merge with the internal query.
     *                            If you want to merge it use ->query() instead
     *
     * @since 1.0.0
     */
    public function executeGetYield(?Builder $query = null)
    {
        $primaryKeys          = [];
        $memberOfPrimaryField = $this->mapper::COLUMNS[$this->mapper::PRIMARYFIELD]['internal'];

        if (isset($this->where[$memberOfPrimaryField])) {
            $keys        = $this->where[$memberOfPrimaryField][0]['value'];
            $primaryKeys = \array_merge(\is_array($keys) ? $keys : [$keys], $primaryKeys);
        }

        foreach ($this->executeGetRawYield($query) as $row) {
            $obj = $this->mapper::createBaseModel($row);
            $obj = $this->populateAbstract($row, $obj);

            if (!empty($this->with)) {
                $this->loadHasManyRelationsTest([$obj]);
            }

            yield $obj;
        }
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
    public function executeGetRaw(?Builder $query = null) : array
    {
        $query ??= $this->getQuery();
        $results = false;

        try {
            $sth = $this->db->con->prepare($query->toSql());
            if ($sth !== false) {
                $sth->execute();
                $results = $sth->fetchAll(\PDO::FETCH_ASSOC);
            }
        } catch (\Throwable $t) {
            \phpOMS\Log\FileLogger::getInstance()->error(
                \phpOMS\Log\FileLogger::MSG_FULL, [
                    'message' => $t->getMessage() . ':' . $query->toSql(),
                    'line'    => __LINE__,
                    'file'    => self::class,
                ]
            );
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
    public function executeGetRawYield(?Builder $query = null)
    {
        $query ??= $this->getQuery();

        try {
            $sth = $this->db->con->prepare($query->toSql());
            if ($sth === false) {
                yield [];

                return;
            }

            $sth->execute();

            while ($row = $sth->fetch(\PDO::FETCH_ASSOC)) {
                yield $row;
            }
        } catch (\Throwable $t) {
            \phpOMS\Log\FileLogger::getInstance()->error(
                \phpOMS\Log\FileLogger::MSG_FULL, [
                    'message' => $t->getMessage() . ':' . $query->toSql(),
                    'line'    => __LINE__,
                    'file'    => self::class,
                ]
            );

            yield [];
        }
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
    public function executeGetAll(?Builder $query = null) : array
    {
        $result = $this->executeGet($query);

        if (\is_object($result)
            && (\str_starts_with($class = \get_class($result), 'Null') || \stripos($class, '\Null') !== false)
        ) {
            return [];
        }

        return \is_array($result) ? $result : [$result];
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
        $query = $this->getQuery(
            null,
            [
                'COUNT(' . (empty($this->columns) ? '*' : \implode(',', $this->columns)) . ')' => 'count',
            ]
        );

        return (int) $query->execute()?->fetchColumn();
    }

    /**
     * Sum the number of elements
     *
     * @return int|float
     *
     * @since 1.0.0
     */
    public function executeSum() : int|float
    {
        $query = $this->getQuery(
            null,
            [
                'SUM(' . (empty($this->columns) ? '*' : \implode(',', $this->columns)) . ')' => 'sum',
            ]
        );

        $result = $query->execute()?->fetchColumn();
        if (empty($result)) {
            return 0;
        }

        return \stripos($result, '.') === false ? (int) $result : (float) $result;
    }

    /**
     * Check if any element exists
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function executeExists() : bool
    {
        $query = $this->getQuery(null, [1]);

        return ($query->execute()?->fetchColumn() ?? 0) > 0;
    }

    /**
     * Check if any element exists
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function executeHas() : bool
    {
        $obj = isset($this->where[$this->mapper::COLUMNS[$this->mapper::PRIMARYFIELD]['internal']])
            ? $this->mapper::createNullModel($this->where[$this->mapper::COLUMNS[$this->mapper::PRIMARYFIELD]['internal']][0]['value'])
            : $this->columns([1])->executeGet();

        return $this->hasManyRelations($obj);
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
    public function getQuery(?Builder $query = null, array $columns = []) : Builder
    {
        $query ??= $this->query ?? new Builder($this->db, true);

        if (empty($columns) && $this->type < MapperType::COUNT_MODELS) {
            if (empty($this->columns)) {
                $columns = $this->mapper::COLUMNS;
            } else {
                $columns = $this->columns;
            }
        }

        foreach ($columns as $key => $values) {
            if (\is_string($values) || \is_int($values)) {
                if (\is_int($key)) {
                    $query->select($values);
                } else {
                    $query->selectAs($key, $values);
                }
            } elseif (($values['writeonly'] ?? false) === false || isset($this->with[$values['internal']])) {
                if (\is_int($key)) {
                    $query->select($key);
                } else {
                    $query->selectAs($this->mapper::TABLE . '_d' . $this->depth . '.' . $key, $key . '_d' . $this->depth);
                }
            }
        }

        if (empty($query->from)) {
            $query->fromAs($this->mapper::TABLE, $this->mapper::TABLE . '_d' . $this->depth);
        }

        // Join tables manually without using "with()" (NOT hasMany/owns one etc.)
        // This is necessary for special cases, e.g. when joining in the other direction
        // Example: Show all profiles who have written a news article.
        //          "with()" only allows to go from articles to accounts but we want to go the other way
        foreach ($this->join as $member => $values) {
            if (($col = $this->mapper::getColumnByMember($member)) === null) {
                continue;
            }

            /* variable in model */
            // @todo join handling is extremely ugly, needs to be refactored
            foreach ($values as $join) {
                // @todo the hasMany, etc. if checks only work if it is a relation on the first level, if we have a deeper where condition nesting this fails
                if ($join['child'] !== '') {
                    continue;
                }

                if (isset($join['mapper']::HAS_MANY[$join['value']])) {
                    if (isset($join['mapper']::HAS_MANY[$join['value']]['external'])) {
                        // join with relation table
                        $query->join($join['mapper']::HAS_MANY[$join['value']]['table'], $join['type'], $join['mapper']::HAS_MANY[$join['value']]['table'] . '_d' . ($this->depth + 1))
                            ->on(
                                $this->mapper::TABLE . '_d' . $this->depth . '.' . $col,
                                '=',
                                $join['mapper']::HAS_MANY[$join['value']]['table'] . '_d' . ($this->depth + 1) . '.' . $join['mapper']::HAS_MANY[$join['value']]['external'],
                                'AND',
                                $join['mapper']::HAS_MANY[$join['value']]['table'] . '_d' . ($this->depth + 1)
                            );

                        // join with model table
                        $query->join($join['mapper']::TABLE, $join['type'], $join['mapper']::TABLE . '_d' . ($this->depth + 1))
                            ->on(
                                $join['mapper']::HAS_MANY[$join['value']]['table'] . '_d' . ($this->depth + 1) . '.' . $join['mapper']::HAS_MANY[$join['value']]['self'],
                                '=',
                                $join['mapper']::TABLE . '_d' . ($this->depth + 1) . '.' . $join['mapper']::PRIMARYFIELD,
                                'AND',
                                $join['mapper']::TABLE . '_d' . ($this->depth + 1)
                            );

                        if (isset($this->on[$join['value']])) {
                            foreach ($this->on[$join['value']] as $on) {
                                $query->where(
                                    $join['mapper']::TABLE . '_d' . ($this->depth + 1) . '.' . $join['mapper']::getColumnByMember($on['member']),
                                    '=',
                                    $on['value'],
                                    'AND'
                                );
                            }
                        }
                    }
                } else {
                    $query->join($join['mapper']::TABLE, $join['type'], $join['mapper']::TABLE . '_d' . ($this->depth + 1))
                        ->on(
                            $this->mapper::TABLE . '_d' . $this->depth . '.' . $col,
                            '=',
                            $join['mapper']::TABLE . '_d' . ($this->depth + 1) . '.' . $join['mapper']::getColumnByMember($join['value']),
                            'AND',
                            $join['mapper']::TABLE . '_d' . ($this->depth + 1)
                        );
                }
            }
        }

        // where
        foreach ($this->where as $member => $values) {
            // handle where query
            if ($member === '' && $values[0]['value'] instanceof Where) {
                $query->where($values[0]['value'], boolean: $values[0]['comparison']);

                continue;
            }

            if (($col = $this->mapper::getColumnByMember($member)) === null) {
                continue;
            }

            // In case alternative where values are allowed
            // This is different from normal or conditions as these are exclusive or conditions
            // This means they are only selected IFF the previous where clause fails
            $alt = [];

            /* variable in model */
            $previous = null;
            foreach ($values as $where) {
                // @todo the hasMany, etc. if checks only work if it is a relation on the first level, if we have a deeper where condition nesting this fails
                if ($where['child'] !== '') {
                    continue;
                }

                $comparison = \is_array($where['value']) && \count($where['value']) > 1 ? 'IN' : $where['logic'];
                if ($where['comparison'] === 'ALT') {
                    // This uses an alternative value if the previous value(s) in the where clause don't exist (e.g. for localized results where you allow a user language, alternatively a primary language, and then alternatively any language if the first two don't exist).

                    // is first value
                    if (empty($alt)) {
                        $alt[] = $previous['value'];
                    }

                    /*
                    select * from table_name
                        where // where starts here
                            field1 = 'value1' // comes from normal where
                            or ( // where1 starts here
                                field1 = 'default'
                                and NOT EXISTS ( // where2 starts here
                                    select 1 from table_name where field1 = 'value1'
                                )
                            )
                    */
                    $where1 = new Where($this->db);
                    $where1->where($this->mapper::TABLE . '_d' . $this->depth . '.' . $col, $comparison, $where['value'], 'and');

                    $where2 = new Builder($this->db);
                    $where2->select(1)
                        ->from($this->mapper::TABLE . '_d' . $this->depth)
                        ->where($this->mapper::TABLE . '_d' . $this->depth . '.' . $col, 'in', $alt);

                    $where1->where($this->mapper::TABLE . '_d' . $this->depth . '.' . $col, 'not exists', $where2, 'and');

                    $query->where($this->mapper::TABLE . '_d' . $this->depth . '.' . $col, $comparison, $where1, 'or');

                    $alt[] = $where['value'];
                } else {
                    $previous = $where;
                    $query->where($this->mapper::TABLE . '_d' . $this->depth . '.' . $col, $comparison, $where['value'], $where['comparison']);
                }
            }
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

                    // @todo handle self and self === null
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
                $relMapper->type  = $this->type;

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
                // @todo Is this true? sort can have multiple sort components!!!
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
        $refClass = null;

        foreach ($this->mapper::COLUMNS as $column => $def) {
            $alias = $column . '_d' . $this->depth;

            if (!\array_key_exists($alias, $result)) {
                continue;
            }

            $value = $result[$alias];

            $hasPath   = false;
            $aValue    = [];
            $arrayPath = '';
            $refProp   = null;
            $isPrivate = $def['private'] ?? false;
            $member    = '';

            if ($isPrivate && $refClass === null) {
                $refClass = new \ReflectionClass($obj);
            }

            if (\stripos($def['internal'], '/') !== false) {
                $hasPath = true;
                $path    = \explode('/', \ltrim($def['internal'], '/'));
                $member  = $path[0];

                if ($isPrivate) {
                    $refProp = $refClass->getProperty($path[0]);
                    $aValue  = $refProp->getValue($obj);
                } else {
                    $aValue = $obj->{$path[0]};
                }

                \array_shift($path);
                $arrayPath = \implode('/', $path);
            } else {
                if ($isPrivate) {
                    $refProp = $refClass->getProperty($def['internal']);
                }

                $member = $def['internal'];
            }

            if (isset($this->mapper::OWNS_ONE[$def['internal']])) {
                $default = null;
                if (!isset($this->with[$member])
                    && ($isPrivate ? $refProp->isInitialized($obj) : isset($obj->{$member}))
                ) {
                    $default = $isPrivate ? $refProp->getValue($obj) : $obj->{$member};
                }

                $value = $this->populateOwnsOne($def['internal'], $result, $default);

                // loads hasMany relations. other relations are loaded in the populateOwnsOne
                if (\is_object($value) && isset($this->mapper::OWNS_ONE[$def['internal']]['mapper'])) {
                    $this->mapper::OWNS_ONE[$def['internal']]['mapper']::reader(db: $this->db)->loadHasManyRelationsTest([$value]);
                }

                if (empty($value)) {
                    // @todo find better solution. this was because of a bug with the sales billing list query depth = 4. The address was set (from the client, referral or creator) but then somehow there was a second address element which was all null and null cannot be assigned to a string variable (e.g. country). The problem with this solution is that if the model expects an initialization (e.g. at lest set the elements to null, '', 0 etc.) this is now not done.
                    $value = $isPrivate ? $refProp->getValue($obj) : $obj->{$member};
                }
            } elseif (isset($this->mapper::BELONGS_TO[$def['internal']])) {
                $default = null;
                if (!isset($this->with[$member])
                    && ($isPrivate ? $refProp->isInitialized($obj) : isset($obj->{$member}))
                ) {
                    $default = $isPrivate ? $refProp->getValue($obj) : $obj->{$member};
                }

                $value = $this->populateBelongsTo($def['internal'], $result, $default);

                // loads hasMany relations. other relations are loaded in the populateBelongsTo
                if (\is_object($value) && isset($this->mapper::BELONGS_TO[$def['internal']]['mapper'])) {
                    $this->mapper::BELONGS_TO[$def['internal']]['mapper']::reader(db: $this->db)->loadHasManyRelationsTest([$value]);
                }
            } elseif (\in_array($def['type'], ['string', 'compress', 'int', 'float', 'bool'])) {
                if ($value !== null && $def['type'] === 'compress') {
                    $def['type'] = 'string';

                    $value = \gzinflate($value);
                }

                $mValue = $isPrivate ? $refProp->getValue($obj) : $obj->{$member};
                if ($value !== null || $mValue !== null) {
                    \settype($value, $def['type']);
                }

                if ($hasPath) {
                    $value = ArrayUtils::setArray($arrayPath, $aValue, $value, '/', true);
                }
            } elseif ($def['type'] === 'DateTime') {
                $value = $value === null ? null : new \DateTime($value);
                if ($hasPath) {
                    $value = ArrayUtils::setArray($arrayPath, $aValue, $value, '/', true);
                }
            } elseif ($def['type'] === 'DateTimeImmutable') {
                $value = $value === null ? null : new \DateTimeImmutable($value);
                if ($hasPath) {
                    $value = ArrayUtils::setArray($arrayPath, $aValue, $value, '/', true);
                }
            } elseif ($def['type'] === 'Json') {
                if ($hasPath) {
                    $value = ArrayUtils::setArray($arrayPath, $aValue, $value, '/', true);
                }

                $value = \json_decode($value, true);
            } elseif ($def['type'] === 'Serializable') {
                $mObj = $isPrivate ? $refProp->getValue($obj) : $obj->{$member};

                if ($mObj !== null && $value !== null) {
                    $mObj->unserialize($value);
                    $value = $mObj;
                }
            }

            if ($isPrivate) {
                $refProp->setValue($obj, $value);
            } else {
                $obj->{$member} = $value;
            }
        }

        // @todo How is this allowed? at the bottom we set $obj->hasMany = value. A hasMany should be always an array?!
        foreach ($this->mapper::HAS_MANY as $member => $def) {
            if (!isset($this->with[$member])
                || !isset($def['column']) // @todo is this required? The code below indicates that this might be stupid
            ) {
                continue;
            }

            $column = $def['mapper']::getColumnByMember($def['column'] ?? $member);
            $alias  = $column . '_d' . ($this->depth + 1);

            if (!\array_key_exists($alias, $result)) {
                continue;
            }

            $value     = $result[$alias];
            $hasPath   = false;
            $aValue    = null;
            $arrayPath = '/';
            $refProp   = null;
            $isPrivate = $def['private'] ?? false;

            if ($isPrivate && $refClass === null) {
                $refClass = new \ReflectionClass($obj);
            }

            if (\stripos($member, '/') !== false) {
                $hasPath = true;
                $path    = \explode('/', $member);
                $member  = $path[0];

                if ($isPrivate) {
                    $refProp = $refClass->getProperty($path[0]);
                }

                \array_shift($path);
                $arrayPath = \implode('/', $path);
                $aValue    = $isPrivate ? $refProp->getValue($obj) : $obj->{$path[0]};
            } elseif ($isPrivate) {
                $refProp = $refClass->getProperty($member);
            }

            $type = $def['mapper']::COLUMNS[$column]['type'];
            if (\in_array($type, ['string', 'compress', 'int', 'float', 'bool'])) {
                if ($value !== null && $type === 'compress') {
                    $type  = 'string';
                    $value = \gzinflate($value);
                }

                if ($value !== null
                    || ($isPrivate ? $refProp->getValue($obj) !== null : $obj->{$member} !== null)
                ) {
                    \settype($value, $type);
                }

                if ($hasPath) {
                    $value = ArrayUtils::setArray($arrayPath, $aValue, $value, '/', true);
                }
            } elseif ($type === 'DateTime') {
                $value ??= new \DateTime($value);
                if ($hasPath) {
                    $value = ArrayUtils::setArray($arrayPath, $aValue, $value, '/', true);
                }
            } elseif ($type === 'DateTimeImmutable') {
                $value ??= new \DateTimeImmutable($value);
                if ($hasPath) {
                    $value = ArrayUtils::setArray($arrayPath, $aValue, $value, '/', true);
                }
            } elseif ($type === 'Json') {
                if ($hasPath) {
                    $value = ArrayUtils::setArray($arrayPath, $aValue, $value, '/', true);
                }

                $value = \json_decode($value, true);
            } elseif ($type === 'Serializable') {
                $mObj = $isPrivate ? $refProp->getValue($obj) : $obj->{$member};

                if ($mObj !== null && $value !== null) {
                    $mObj->unserialize($value);
                    $value = $mObj;
                }
            }

            if ($isPrivate) {
                $refProp->setValue($obj, $value);
            } else {
                $obj->{$member} = $value;
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
     * @todo in the future we could pass not only the $id ref but all of the data as a join!!! and save an additional select!!!
     * @todo parent and child elements however must be loaded because they are not loaded
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

        return $ownsOneMapper->populateAbstract($result, $mapper::createBaseModel($result));
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
     * @todo in the future we could pass not only the $id ref but all of the data as a join!!! and save an additional select!!!
     * @todo only the belongs to model gets populated the children of the belongsto model are always null models. either this function needs to call the get for the children, it should call get for the belongs to right away like the hasMany, or i find a way to recursevily load the data for all sub models and then populate that somehow recursively, probably too complex.
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

        return $belongsToMapper->populateAbstract($result, $mapper::createBaseModel($result));
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
    public function loadHasManyRelationsOld(object $obj) : void
    {
        if (empty($this->with)) {
            return;
        }

        // @todo only accept array and then perform this work on the array here
        // this allows us to better load data for all objects at the same time!

        $primaryKey = $this->mapper::getObjectId($obj);
        if (empty($primaryKey)) {
            return;
        }

        $refClass = null;

        // @todo check if there are more cases where the relation is already loaded with joins etc.
        // there can be pseudo hasMany elements like localizations. They are hasMany but these are already loaded with joins!
        foreach ($this->with as $member => $withData) {
            if (isset($this->mapper::HAS_MANY[$member])) {
                $many = $this->mapper::HAS_MANY[$member];
                if (isset($many['column'])) {
                    continue;
                }

                $isPrivate = $withData['private'] ?? false;

                $objectMapper = $this->createRelationMapper($many['mapper']::get(db: $this->db), $member);
                if ($many['external'] === null) {
                    $objectMapper->where($many['mapper']::COLUMNS[$many['self']]['internal'], $primaryKey);
                } else {
                    $query = new Builder($this->db, true);
                    $query->leftJoin($many['table'])
                        ->on($many['mapper']::TABLE . '_d1.' . $many['mapper']::PRIMARYFIELD, '=', $many['table'] . '.' . $many['external'])
                        ->where($many['table'] . '.' . $many['self'], '=', $primaryKey);

                    // Cannot use join, because join only works on members and we don't have members for a relation table
                    // This is why we need to create a "base" query which contains the join on table columns
                    $objectMapper->query($query);
                }

                // @todo This right here is the problem for performing this on an array of primary keys.
                // In case of a relation table there is no relation info available in the obj or the objects
                // Since we don't retrieve the relation table information (we only use it in the select) we cannot assign
                // the objects to the correct parent obj. For this reason we need to perform the loadHasManyRelations on an individual
                // obj.
                // Maybe we split this function in owns_one/belongs_to and hasMany. This way we could at least perform the action on an array
                // for owns_one/belongs_to.
                // Idea: somehow make the query->execute() return an array indexed by the key the object belongs to? This however would result
                // not in a simple [array] but an array => array.
                // For this we might have to create an internal function or variable called ->indexedBy(whatever_column_to_use_for_index)
                $objects = $objectMapper->execute();
                if (empty($objects) || (!\is_array($objects) && $objects->id === 0)) {
                    continue;
                }

                if ($isPrivate) {
                    if ($refClass === null) {
                        $refClass = new \ReflectionClass($obj);
                    }

                    $refProp = $refClass->getProperty($member);
                    $refProp->setValue($obj, !\is_array($objects) && ($many['conditional'] ?? false) === false
                        ? [$many['mapper']::getObjectId($objects) => $objects]
                        : $objects // if conditional === true the obj will be assigned (e.g. hasMany localizations but only one is loaded for the model)
                    );
                } else {
                    $obj->{$member} = !\is_array($objects) && ($many['conditional'] ?? false) === false
                        ? [$many['mapper']::getObjectId($objects) => $objects]
                        : $objects; // if conditional === true the obj will be assigned (e.g. hasMany localizations but only one is loaded for the model)
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

                /** @var ReadMapper $relMapper */
                $relMapper = $this->createRelationMapper($relation['mapper']::reader($this->db), $member);

                $isPrivate = $withData['private'] ?? false;
                if ($isPrivate) {
                    if ($refClass === null) {
                        $refClass = new \ReflectionClass($obj);
                    }

                    $refProp = $refClass->getProperty($member);
                    $relMapper->loadHasManyRelationsOld($refProp->getValue($obj));
                } else {
                    $relMapper->loadHasManyRelationsOld($obj->{$member});
                }
            }
        }
    }

    /**
     * Fill object with relations
     *
     * @param object[] $objs Object to fill
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function loadHasManyRelationsTest(array $objs) : void
    {
        if (empty($this->with)) {
            return;
        }

        // @todo only accept array and then perform this work on the array here
        // this allows us to better load data for all objects at the same time!

        $primaryKeys = [];
        foreach ($objs as $idx => $obj) {
            $key = $this->mapper::getObjectId($obj);

            if (!empty($key)) {
                $primaryKeys[$idx] = $key;
            }
        }

        if (empty($primaryKeys)) {
            return;
        }

        $refClass = null;

        $cachedKeys = [];

        // @todo check if there are more cases where the relation is already loaded with joins etc.
        // there can be pseudo hasMany elements like localizations. They are hasMany but these are already loaded with joins!
        foreach ($this->with as $member => $withData) {
            if (isset($this->mapper::HAS_MANY[$member])) {
                $many = $this->mapper::HAS_MANY[$member];
                if (isset($many['column'])) {
                    continue;
                }

                $isPrivate = $withData['private'] ?? false;

                $objectMapper = $this->createRelationMapper($many['mapper']::get(db: $this->db), $member);
                if ($many['external'] === null) {
                    $objectMapper->where($many['mapper']::COLUMNS[$many['self']]['internal'], $primaryKeys);
                    $objectMapper->indexedBy($many['self']);
                } else {
                    $query = new Builder($this->db, true);
                    $query
                        ->selectAs($many['table'] . '.' . $many['self'], $many['self'] . '_d' . $this->depth)
                        ->leftJoin($many['table'])
                        ->on($many['mapper']::TABLE . '_d1.' . $many['mapper']::PRIMARYFIELD, '=', $many['table'] . '.' . $many['external'])
                        ->where($many['table'] . '.' . $many['self'], 'IN', $primaryKeys);

                    // Cannot use join, because join only works on members and we don't have members for a relation table
                    // This is why we need to create a "base" query which contains the join on table columns
                    $objectMapper->query($query);
                    $objectMapper->indexedBy($many['self']);
                }

                $objects = $objectMapper->execute();
                if (empty($objects) || !\is_array($objects)) {
                    continue;
                }

                if ($isPrivate) {
                    if ($refClass === null) {
                        $refClass = new \ReflectionClass($obj);
                    }

                    foreach ($primaryKeys as $idx => $key) {
                        if (!isset($objects[$key])) {
                            continue;
                        }

                        $refProp = $refClass->getProperty($member);
                        $refProp->setValue($objs[$idx], !\is_array($objects[$key]) && ($many['conditional'] ?? false) === false
                            ? [$many['mapper']::getObjectId($objects[$key]) => $objects[$key]]
                            : $objects[$key] // if conditional === true the obj will be assigned (e.g. hasMany localizations but only one is loaded for the model)
                        );
                    }
                } else {
                    foreach ($primaryKeys as $idx => $key) {
                        if (!isset($objects[$key])) {
                            continue;
                        }

                        $objs[$idx]->{$member} = !\is_array($objects[$key]) && ($many['conditional'] ?? false) === false
                            ? [$many['mapper']::getObjectId($objects[$key]) => $objects[$key]]
                            : $objects[$key]; // if conditional === true the obj will be assigned (e.g. hasMany localizations but only one is loaded for the model)
                    }
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

                /** @var ReadMapper $relMapper */
                $relMapper = $this->createRelationMapper($relation['mapper']::reader($this->db), $member);

                $isPrivate = $withData['private'] ?? false;
                if ($isPrivate) {
                    if ($refClass === null) {
                        $refClass = new \ReflectionClass($obj);
                    }

                    $refProp = $refClass->getProperty($member);

                    $tempObjs = [];
                    foreach ($objs as $obj) {
                        $tempObjs[] = $refProp->getValue($obj);
                    }

                    $relMapper->loadHasManyRelationsTest($tempObjs);
                } else {
                    $tempObjs = [];
                    foreach ($objs as $obj) {
                        $tempObjs[] = $obj->{$member};
                    }

                    $relMapper->loadHasManyRelationsTest($tempObjs);
                }
            }
        }
    }

    /**
     * Checks if object has certain relations
     *
     * @param object $obj Object to check
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function hasManyRelations(object $obj) : bool
    {
        if (empty($this->with)) {
            return true;
        }

        $primaryKey = $this->mapper::getObjectId($obj);
        if (empty($primaryKey)) {
            return false;
        }

        $refClass = null;

        // @todo check if there are more cases where the relation is already loaded with joins etc.
        // there can be pseudo hasMany elements like localizations. They are hasMany but these are already loaded with joins!
        foreach ($this->with as $member => $withData) {
            if (isset($this->mapper::HAS_MANY[$member])) {
                $many = $this->mapper::HAS_MANY[$member];
                if (isset($many['column'])) {
                    continue;
                }

                // @todo withData doesn't store this directly, it is in [0]['private] ?!?!
                $isPrivate = $withData['private'] ?? false;

                $objectMapper = $this->createRelationMapper($many['mapper']::exists(db: $this->db), $member);
                if ($many['external'] === null/* same as $many['table'] !== $many['mapper']::TABLE */) {
                    $objectMapper->where($many['mapper']::COLUMNS[$many['self']]['internal'], $primaryKey);
                } else {
                    $query = new Builder($this->db, true);
                    $query->leftJoin($many['table'])
                        ->on($many['mapper']::TABLE . '_d1.' . $many['mapper']::PRIMARYFIELD, '=', $many['table'] . '.' . $many['external'])
                        ->where($many['table'] . '.' . $many['self'], '=', $primaryKey);

                    // Cannot use join, because join only works on members and we don't have members for a relation table
                    // This is why we need to create a "base" query which contains the join on table columns
                    $objectMapper->query($query);
                }

                $objects = $objectMapper->execute();

                return !empty($objects) && $objects !== false;
            } elseif (isset($this->mapper::OWNS_ONE[$member])
                || isset($this->mapper::BELONGS_TO[$member])
            ) {
                $relation = isset($this->mapper::OWNS_ONE[$member])
                    ? $this->mapper::OWNS_ONE[$member]
                    : $this->mapper::BELONGS_TO[$member];

                if (\count($withData) < 2) {
                    continue;
                }

                /** @var ReadMapper $relMapper */
                $relMapper = $this->createRelationMapper($relation['mapper']::reader($this->db), $member);

                $isPrivate = $withData['private'] ?? false;
                if ($isPrivate) {
                    if ($refClass === null) {
                        $refClass = new \ReflectionClass($obj);
                    }

                    $refProp = $refClass->getProperty($member);
                    return $relMapper->hasManyRelations($refProp->getValue($obj));
                } else {
                    return $relMapper->hasManyRelations($obj->{$member});
                }
            }
        }
    }
}
