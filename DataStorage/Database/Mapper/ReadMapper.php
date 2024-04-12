<?php
/**
 * Jingga
 *
 * PHP Version 8.2
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
 * @todo Add getArray functions to get array instead of object
 *      https://github.com/Karaka-Management/phpOMS/issues/350
 *
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
                return $this->executeYield(...$options);
            case MapperType::GET_RAW:
                /** @var null|Builder ...$options */
                return $this->executeGetRaw(...$options);
            case MapperType::GET_ALL:
                /** @var null|Builder ...$options */
                return $this->executeGetArray(...$options);
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
        $objs    = [];
        $indexed = [];

        $hasFactory = $this->mapper::hasFactory();
        $baseClass  = $hasFactory ? null : $this->mapper::getBaseModelClass();

        foreach ($this->executeGetRawYield($query) as $row) {
            if ($row === []) {
                continue;
            }

            $value        = $row[$this->mapper::PRIMARYFIELD . '_d' . $this->depth . $this->joinAlias];
            $objs[$value] = $hasFactory ? $this->mapper::createBaseModel($row) : new $baseClass();
            $objs[$value] = $this->populateAbstract($row, $objs[$value]);

            if (!empty($this->indexedBy) && isset($row[$this->indexedBy . '_d' . $this->depth . $this->joinAlias])) {
                if (!isset($indexed[$row[$this->indexedBy . '_d' . $this->depth . $this->joinAlias]])) {
                    $indexed[$row[$this->indexedBy . '_d' . $this->depth . $this->joinAlias]] = [];
                }

                $indexed[$row[$this->indexedBy . '_d' . $this->depth . $this->joinAlias]][] = $objs[$value];
            }
        }

        if (!empty($this->with) && !empty($objs)) {
            $this->loadHasManyRelations($objs);
        }

        if (!empty($this->indexedBy)) {
            return $indexed;
        } elseif ($this->type === MapperType::GET_ALL) {
            return $objs;
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
     * @return \Generator<R>
     *
     * @since 1.0.0
     */
    public function executeYield(?Builder $query = null) : \Generator
    {
        foreach ($this->executeGetRawYield($query) as $row) {
            $obj = $this->mapper::createBaseModel($row);
            $obj = $this->populateAbstract($row, $obj);

            if (!empty($this->with)) {
                $this->loadHasManyRelations([$obj]);
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
     * @return R[]
     *
     * @since 1.0.0
     */
    public function executeGetArray(?Builder $query = null) : array
    {
        $this->getAll();

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
        $this->count();

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
        $this->sum();

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
        $this->exists();

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
            $columns = empty($this->columns) ? $this->mapper::COLUMNS : $this->columns;
        }

        foreach ($columns as $key => $values) {
            if (\is_array($values)
                && (($values['writeonly'] ?? false) === false || isset($this->with[$values['internal']]))
            ) {
                if (\is_int($key)) {
                    $query->select($key);
                } else {
                    $query->selectAs(
                        $this->mapper::TABLE . '_d' . $this->depth . $this->joinAlias . '.' . $key,
                        $key . '_d' . $this->depth . $this->joinAlias
                    );
                }
            } elseif (\is_int($values)) {
                $query->select($values);
            } elseif (\is_string($values)) {
                $query->selectAs($key, $values);
            }
        }

        if (empty($query->from)) {
            $query->fromAs($this->mapper::TABLE, $this->mapper::TABLE . '_d' . $this->depth . $this->joinAlias);
        }

        // Join tables manually without using "with()" (NOT hasMany/owns one etc.)
        // This is necessary for special cases, e.g. when joining in the other direction
        // Example: Show all profiles who have written a news article.
        //          "with()" only allows to go from articles to accounts but we want to go the other way
        //
        // @feature Create join functionality for mappers which supports joining and filtering based on other tables
        //      Example: show all profiles which have written a news article
        //      https://github.com/Karaka-Management/phpOMS/issues/253
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
                        $relJoinTable = $join['mapper']::HAS_MANY[$join['value']]['table'];

                        // join with relation table
                        $query->join($relJoinTable, $join['type'], $relJoinTable . '_d' . ($this->depth + 1) . $this->joinAlias)
                            ->on(
                                $this->mapper::TABLE . '_d' . $this->depth . $this->joinAlias . '.' . $col,
                                '=',
                                $relJoinTable . '_d' . ($this->depth + 1) . $this->joinAlias . '.' . $join['mapper']::HAS_MANY[$join['value']]['external'],
                                'AND',
                                $relJoinTable . '_d' . ($this->depth + 1) . $this->joinAlias
                            );

                        // join with model table
                        $query->join($join['mapper']::TABLE, $join['type'], $join['mapper']::TABLE . '_d' . ($this->depth + 1) . $this->joinAlias)
                            ->on(
                                $relJoinTable . '_d' . ($this->depth + 1) . $this->joinAlias . '.' . $join['mapper']::HAS_MANY[$join['value']]['self'],
                                '=',
                                $join['mapper']::TABLE . '_d' . ($this->depth + 1) . $this->joinAlias . '.' . $join['mapper']::PRIMARYFIELD,
                                'AND',
                                $join['mapper']::TABLE . '_d' . ($this->depth + 1) . $this->joinAlias
                            );

                        if (isset($this->on[$join['value']])) {
                            foreach ($this->on[$join['value']] as $on) {
                                $query->where(
                                    $join['mapper']::TABLE . '_d' . ($this->depth + 1) . $this->joinAlias . '.' . $join['mapper']::getColumnByMember($on['member']),
                                    '=',
                                    $on['value'],
                                    'AND'
                                );
                            }
                        }
                    }
                } else {
                    $query->join($join['mapper']::TABLE, $join['type'], $join['mapper']::TABLE . '_d' . ($this->depth + 1) . $this->joinAlias)
                        ->on(
                            $this->mapper::TABLE . '_d' . $this->depth . $this->joinAlias . '.' . $col,
                            '=',
                            $join['mapper']::TABLE . '_d' . ($this->depth + 1) . $this->joinAlias . '.' . $join['mapper']::getColumnByMember($join['value']),
                            'AND',
                            $join['mapper']::TABLE . '_d' . ($this->depth + 1) . $this->joinAlias
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
                    $where1->where($this->mapper::TABLE . '_d' . $this->depth . $this->joinAlias . '.' . $col, $comparison, $where['value'], 'and');

                    $where2 = new Builder($this->db);
                    $where2->select(1)
                        ->from($this->mapper::TABLE . '_d' . $this->depth . $this->joinAlias)
                        ->where($this->mapper::TABLE . '_d' . $this->depth . $this->joinAlias . '.' . $col, 'in', $alt);

                    $where1->where($this->mapper::TABLE . '_d' . $this->depth . $this->joinAlias . '.' . $col, 'not exists', $where2, 'and');

                    $query->where($this->mapper::TABLE . '_d' . $this->depth . $this->joinAlias . '.' . $col, $comparison, $where1, 'or');

                    $alt[] = $where['value'];
                } else {
                    $previous = $where;
                    $query->where($this->mapper::TABLE . '_d' . $this->depth . $this->joinAlias . '.' . $col, $comparison, $where['value'], $where['comparison']);
                }
            }
        }

        // load relations
        foreach ($this->with as $member => $data) {
            $rel = null;
            if ((isset($this->mapper::OWNS_ONE[$member]) || isset($this->mapper::BELONGS_TO[$member]))
                || (!isset($this->mapper::HAS_MANY[$member]['external']) && isset($this->mapper::HAS_MANY[$member]['column']))
            ) {
                $rel = $this->mapper::OWNS_ONE[$member] ?? (
                        $this->mapper::BELONGS_TO[$member] ?? (
                            $this->mapper::HAS_MANY[$member] ?? null
                        )
                    );
            } else {
                continue;
            }

            foreach ($data as $with) {
                if ($with['child'] !== '') {
                    continue;
                }

                if (isset($this->mapper::OWNS_ONE[$member]) || isset($this->mapper::BELONGS_TO[$member])) {
                    $tableAlias = $rel['mapper']::TABLE . '_d' . ($this->depth + 1) . '_' . $member;
                    $query->leftJoin($rel['mapper']::TABLE, $tableAlias)
                        ->on(
                            $this->mapper::TABLE . '_d' . $this->depth . $this->joinAlias . '.' . $rel['external'], '=',
                            $tableAlias . '.' . (
                                isset($rel['by']) ? $rel['mapper']::getColumnByMember($rel['by']) : $rel['mapper']::PRIMARYFIELD
                            ), 'and',
                            $tableAlias
                        );
                } elseif (!isset($this->mapper::HAS_MANY[$member]['external']) && isset($this->mapper::HAS_MANY[$member]['column'])) {
                    // get HasManyQuery (but only for elements which have a 'column' defined)
                    $tableAlias = $rel['mapper']::TABLE . '_d' . ($this->depth + 1) . '_' . $member;

                    // @todo handle self and self === null
                    $query->leftJoin($rel['mapper']::TABLE, $tableAlias)
                        ->on(
                            $this->mapper::TABLE . '_d' . $this->depth . $this->joinAlias . '.' . ($rel['external'] ?? $this->mapper::PRIMARYFIELD), '=',
                            $tableAlias . '.' . (
                                isset($rel['by']) ? $rel['mapper']::getColumnByMember($rel['by']) : $rel['self']
                            ), 'and',
                            $tableAlias
                        );
                }

                /** @var self $relMapper */
                $relMapper            = $this->createRelationMapper($rel['mapper']::reader(db: $this->db), $member);
                $relMapper->depth     = $this->depth + 1;
                $relMapper->type      = $this->type;
                $relMapper->joinAlias = '_' . $member;

                // Here we go further into the depth of the model (e.g. a hasMany/ownsOne can again have ownsOne...)
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

                $query->orderBy($this->mapper::TABLE . '_d' . $this->depth . $this->joinAlias . '.' . $column, $sort['order']);

                // @bug It looks like that only one sort parameter is supported despite SQL supporting multiple
                //      https://github.com/Karaka-Management/phpOMS/issues/364
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
        $refClass = null;

        $aValue    = null;
        $arrayPath = '';

        foreach ($this->mapper::COLUMNS as $column => $def) {
            $alias = $column . '_d' . $this->depth . $this->joinAlias;
            if (!\array_key_exists($alias, $result)) {
                continue;
            }

            $value     = $result[$alias];
            $hasPath   = false;
            $refProp   = null;
            $isPrivate = $def['private'] ?? false;
            $member    = $def['internal'];

            if ($isPrivate && $refClass === null) {
                $refClass = new \ReflectionClass($obj);
            }

            if (\stripos($member, '/') !== false) {
                $hasPath = true;
                $path    = \explode('/', \ltrim($member, '/'));
                $member  = $path[0];

                if ($isPrivate) {
                    $refProp = $refClass->getProperty($path[0]);
                    $aValue  = $refProp->getValue($obj);
                } else {
                    $aValue = $obj->{$path[0]};
                }

                \array_shift($path);
                $arrayPath = \implode('/', $path);
            } elseif ($isPrivate) {
                $refProp = $refClass->getProperty($member);
            }

            $type = $def['type'];
            if (isset($this->mapper::OWNS_ONE[$member])) {
                $default = null;
                if (!isset($this->with[$member])
                    && ($isPrivate ? $refProp->isInitialized($obj) : isset($obj->{$member}))
                ) {
                    $default = $isPrivate ? $refProp->getValue($obj) : $obj->{$member};
                }

                $value = $this->populateOwnsOne($member, $result, $default);
            } elseif (isset($this->mapper::BELONGS_TO[$member])) {
                $default = null;
                if (!isset($this->with[$member])
                    && ($isPrivate ? $refProp->isInitialized($obj) : isset($obj->{$member}))
                ) {
                    $default = $isPrivate ? $refProp->getValue($obj) : $obj->{$member};
                }

                $value = $this->populateBelongsTo($member, $result, $default);
            } elseif (\in_array($type, ['string', 'compress', 'int', 'float', 'bool'])) {
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
                $value = $value === null ? null : new \DateTime($value);
                if ($hasPath) {
                    $value = ArrayUtils::setArray($arrayPath, $aValue, $value, '/', true);
                }
            } elseif ($type === 'DateTimeImmutable') {
                $value = $value === null ? null : new \DateTimeImmutable($value);
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

        if (empty($this->with)) {
            return $obj;
        }

        // This is only for hasMany elements where only one hasMany child object is loaded
        // Example: A model usually only loads one l11n element despite having localizations for multiple languages
        // @todo The code below is basically a copy of the foreach from above.
        //       Maybe we can combine them in a smart way without adding much overhead
        foreach ($this->mapper::HAS_MANY as $member => $def) {
            // Only if column is defined do we have a pseudo 1-to-1 relation
            // The content of the column will be loaded directly in the member variable
            if (!isset($this->with[$member])
                || !isset($def['column'])
            ) {
                continue;
            }

            $column = $def['mapper']::getColumnByMember($def['column'] ?? $member);
            $alias  = $column . '_d' . ($this->depth + 1) . '_' . $member;

            if (!\array_key_exists($alias, $result)) {
                continue;
            }

            $value     = $result[$alias];
            $hasPath   = false;
            $refProp   = null;
            $isPrivate = $def['private'] ?? false;

            if ($isPrivate && $refClass === null) {
                $refClass = new \ReflectionClass($obj);
            }

            if (\stripos($member, '/') !== false) {
                $hasPath = true;
                $path    = \explode('/', \ltrim($member, '/'));
                $member  = $path[0];

                if ($isPrivate) {
                    $refProp = $refClass->getProperty($path[0]);
                    $aValue  = $refProp->getValue($obj);
                } else {
                    $aValue = $obj->{$path[0]};
                }

                \array_shift($path);
                $arrayPath = \implode('/', $path);
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
                $value = $value === null ? null : new \DateTime($value);
                if ($hasPath) {
                    $value = ArrayUtils::setArray($arrayPath, $aValue, $value, '/', true);
                }
            } elseif ($type === 'DateTimeImmutable') {
                $value = $value === null ? null : new \DateTimeImmutable($value);
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
     * @since 1.0.0
     */
    public function populateOwnsOne(string $member, array $result, mixed $default = null) : mixed
    {
        /** @var class-string<DataMapperFactory> $mapper */
        $mapper = $this->mapper::OWNS_ONE[$member]['mapper'];

        if (!isset($this->with[$member])) {
            if (\array_key_exists($this->mapper::OWNS_ONE[$member]['external'] . '_d' . $this->depth . $this->joinAlias, $result)) {
                return isset($this->mapper::OWNS_ONE[$member]['column'])
                    ? $result[$this->mapper::OWNS_ONE[$member]['external'] . '_d' . $this->depth . $this->joinAlias]
                    : $mapper::createNullModel(
                        $result[$this->mapper::OWNS_ONE[$member]['external'] . '_d' . $this->depth . $this->joinAlias],
                        $this->mapper::OWNS_ONE[$member]['by'] ?? null
                    );
            } else {
                return $default;
            }
        } elseif (isset($this->mapper::OWNS_ONE[$member]['column'])) {
            return $result[$mapper::getColumnByMember($this->mapper::OWNS_ONE[$member]['column']) . '_d' . $this->depth . '_' . $member];
        } elseif (!isset($result[$mapper::PRIMARYFIELD . '_d' . ($this->depth + 1) . '_' . $member])) {
            return $mapper::createNullModel();
        }

        /** @var self $ownsOneMapper */
        $ownsOneMapper            = $this->createRelationMapper($mapper::get($this->db), $member);
        $ownsOneMapper->depth     = $this->depth + 1;
        $ownsOneMapper->joinAlias = '_' . $member;

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
     * @since 1.0.0
     */
    public function populateBelongsTo(string $member, array $result, mixed $default = null) : mixed
    {
        /** @var class-string<DataMapperFactory> $mapper */
        $mapper = $this->mapper::BELONGS_TO[$member]['mapper'];

        if (!isset($this->with[$member])) {
            if (\array_key_exists($this->mapper::BELONGS_TO[$member]['external'] . '_d' . $this->depth . $this->joinAlias, $result)) {
                return isset($this->mapper::BELONGS_TO[$member]['column'])
                    ? $result[$this->mapper::BELONGS_TO[$member]['external'] . '_d' . $this->depth . $this->joinAlias]
                    : $mapper::createNullModel(
                        $result[$this->mapper::BELONGS_TO[$member]['external'] . '_d' . $this->depth . $this->joinAlias],
                        $this->mapper::BELONGS_TO[$member]['by'] ?? null
                    );
            } else {
                return $default;
            }
        } elseif (isset($this->mapper::BELONGS_TO[$member]['column'])) {
            return $result[$mapper::getColumnByMember($this->mapper::BELONGS_TO[$member]['column']) . '_d' . $this->depth . '_' . $member];
        } elseif (!isset($result[$mapper::PRIMARYFIELD . '_d' . ($this->depth + 1) . '_' . $member])) {
            return $mapper::createNullModel();
        } elseif (isset($this->mapper::BELONGS_TO[$member]['by'])) {
            // get the belongs to based on a different column (not primary key)
            // this is often used if the value is actually a different model:
            //      you want the profile but the account id is referenced
            //      in this case you can get the profile by loading the profile based on the account reference column

            /** @var self $belongsToMapper */
            $belongsToMapper            = $this->createRelationMapper($mapper::get($this->db), $member);
            $belongsToMapper->depth     = $this->depth + 1;
            $belongsToMapper->joinAlias = '_' . $member;

            $belongsToMapper->where(
                $this->mapper::BELONGS_TO[$member]['by'],
                $result[$mapper::getColumnByMember($this->mapper::BELONGS_TO[$member]['by']) . '_d' . ($this->depth + 1) . '_' . $member],
                '='
            );

            return $belongsToMapper->execute();
        }

        /** @var self $belongsToMapper */
        $belongsToMapper            = $this->createRelationMapper($mapper::get($this->db), $member);
        $belongsToMapper->depth     = $this->depth + 1;
        $belongsToMapper->joinAlias = '_' . $member;

        return $belongsToMapper->populateAbstract($result, $mapper::createBaseModel($result));
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
    public function loadHasManyRelations(array $objs) : void
    {
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

        // @todo Check if there are more cases where the relation is already loaded with joins etc.
        //      There can be pseudo hasMany elements like localizations.
        //      They are hasMany but these are already loaded with joins!
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
                        ->selectAs($many['table'] . '.' . $many['self'], $many['self'] . '_d' . $this->depth . $this->joinAlias)
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
                    $refClass ??= new \ReflectionClass($obj);

                    foreach ($primaryKeys as $idx => $key) {
                        if (!isset($objects[$key])) {
                            continue;
                        }

                        if (($many['conditional'] ?? false) && \is_array($objects[$key])) {
                            $objects[$key] = \reset($objects[$key]);
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

                        if (($many['conditional'] ?? false) && \is_array($objects[$key])) {
                            $objects[$key] = \reset($objects[$key]);
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
                if (\count($withData) < 2) {
                    continue;
                }

                $relation = isset($this->mapper::OWNS_ONE[$member])
                    ? $this->mapper::OWNS_ONE[$member]
                    : $this->mapper::BELONGS_TO[$member];

                /** @var ReadMapper $relMapper */
                $relMapper = $this->createRelationMapper($relation['mapper']::reader($this->db), $member);

                $isPrivate = $relation['private'] ?? false;
                $tempObjs  = [];

                if ($isPrivate) {
                    $refClass ??= new \ReflectionClass($obj);

                    $refProp = $refClass->getProperty($member);

                    foreach ($objs as $obj) {
                        $tempObjs[] = $refProp->getValue($obj);
                    }
                } else {
                    foreach ($objs as $obj) {
                        $tempObjs[] = $obj->{$member};
                    }
                }

                $relMapper->loadHasManyRelations($tempObjs);
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

        // @performance Check if there are more cases where the relation is already loaded with joins etc.
        //      There can be pseudo hasMany elements like localizations. They are hasMany but these are already loaded with joins!
        //      Variation of https://github.com/Karaka-Management/phpOMS/issues/363
        foreach ($this->with as $member => $withData) {
            if (isset($this->mapper::HAS_MANY[$member])) {
                $many = $this->mapper::HAS_MANY[$member];
                if (isset($many['column'])) {
                    continue;
                }

                $isPrivate = $many['private'] ?? false;

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
                if (\count($withData) < 2) {
                    continue;
                }

                $relation = isset($this->mapper::OWNS_ONE[$member])
                    ? $this->mapper::OWNS_ONE[$member]
                    : $this->mapper::BELONGS_TO[$member];

                /** @var ReadMapper $relMapper */
                $relMapper = $this->createRelationMapper($relation['mapper']::reader($this->db), $member);

                $isPrivate = $relation['private'] ?? false;
                if ($isPrivate) {
                    $refClass ??= new \ReflectionClass($obj);

                    $refProp = $refClass->getProperty($member);
                    return $relMapper->hasManyRelations($refProp->getValue($obj));
                } else {
                    return $relMapper->hasManyRelations($obj->{$member});
                }
            }
        }
    }

    public function paginate(string $member, string $ptype, mixed $offset) : self
    {
        if ($ptype === 'p') {
            $this->where($member, $offset ?? 0, '<');
        } elseif ($ptype === 'n') {
            $this->where($member, $offset ?? 0, '>');
        } else {
            $this->where($member, 0, '>');
        }

        return $this;
    }
}
