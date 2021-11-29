<?php
/**
 * Orange Management
 *
 * PHP Version 8.0
 *
 * @package   phpOMS\DataStorage\Database\Mapper
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\DataStorage\Database\Mapper;

use phpOMS\DataStorage\Database\Query\Builder;
use phpOMS\Utils\ArrayUtils;

/**
 * Read mapper (SELECTS).
 *
 * @package phpOMS\DataStorage\Database\Mapper
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
class ReadMapper extends DataMapperAbstract
{
    private $columns = [];

    public function get() : self
    {
        $this->type = MapperType::GET;

        return $this;
    }

    public function getRaw() : self
    {
        $this->type = MapperType::GET_RAW;

        return $this;
    }

    public function getAll() : self
    {
        $this->type = MapperType::GET_ALL;

        return $this;
    }

    public function count() : self
    {
        $this->type = MapperType::COUNT_MODELS;

        return $this;
    }

    public function getRandom() : self
    {
        $this->type = MapperType::GET_RANDOM;

        return $this;
    }

    public function find() : self
    {
        $this->type = MapperType::FIND;

        return $this;
    }

    public function columns(array $columns) : self
    {
        $this->columns = $columns;

        return $this;
    }

    public function execute(array ...$options) : mixed
    {
        switch($this->type) {
            case MapperType::GET:
                return $options !== null
                    ? $this->executeGet(...$options)
                    : $this->executeGet();
            case MapperType::GET_RAW:
                return $options !== null
                    ? $this->executeGetRaw(...$options)
                    : $this->executeGetRaw();
            case MapperType::GET_ALL:
                return $options !== null
                    ? $this->executeGetAll(...$options)
                    : $this->executeGetAll();
            case MapperType::GET_RANDOM:
                return $this->executeGetRaw();
            case MapperType::COUNT_MODELS:
                return $this->executeCount();
            default:
                return null;
        }
    }

    // @todo: consider to always return an array, this way we could remove executeGetAll
    public function executeGet(Builder $query = null) : mixed
    {
        $primaryKeys = [];
        $memberOfPrimaryField = $this->mapper::COLUMNS[$this->mapper::PRIMARYFIELD]['internal'];
        $emptyWhere = empty($this->where);

        if (isset($this->where[$memberOfPrimaryField])) {
            $keys = $this->where[$memberOfPrimaryField][0]['value'];
            $primaryKeys = \array_merge(\is_array($keys) ? $keys : [$keys], $primaryKeys);
        }

        // Get initialized objects from memory cache.
        $obj = [];
        foreach ($primaryKeys as $index => $value) {
            if (!$this->mapper::isInitialized($this->mapper::class, $value)) {
                continue;
            }

            $obj[$value] = $this->mapper::getInitialized($this->mapper::class, $value);
            unset($this->where[$memberOfPrimaryField]);
            unset($primaryKeys[$index]);
        }

        // Get remaining objects (not available in memory cache) or remaining where clauses.
        if (!empty($primaryKeys) || (!empty($this->where) || $emptyWhere)) {
            $dbData = $this->executeGetRaw($query);

            foreach ($dbData as $row) {
                $value       = $row[$this->mapper::PRIMARYFIELD . '_d' . $this->depth];
                $obj[$value] = $this->mapper::createBaseModel();
                $this->mapper::addInitialized($this->mapper::class, $value, $obj[$value]);

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

    public function executeGetRaw(Builder $query = null) : array
    {
        $query ??= $this->getQuery($query);

        try {
            $results = false;

            $sth = $this->db->con->prepare($query->toSql());
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
        $query = $this->getQuery();
        $query->select('COUNT(*)');

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
     * @param Builder $query     Query to fill
     * @param array   $columns   Columns to use
     *
     * @return Builder
     *
     * @since 1.0.0
     */
    public function getQuery(Builder $query = null, array $columns = []) : Builder
    {
        $query ??= new Builder($this->db);
        $columns = empty($columns)
            ? (empty($this->columns) ? $this->mapper::COLUMNS : $this->columns)
            : $columns;

        foreach ($columns as $key => $values) {
            if ($values['writeonly'] ?? false === false) {
                $query->selectAs($this->mapper::TABLE . '_d' . $this->depth . '.' . $key, $key . '_d' . $this->depth);
            }
        }

        if (empty($query->from)) {
            $query->fromAs($this->mapper::TABLE, $this->mapper::TABLE . '_d' . $this->depth);
        }

        // where
        foreach ($this->where as $member => $values) {
            if(($col = $this->mapper::getColumnByMember($member)) !== null) {
                /* variable in model */
                foreach ($values as $index => $where) {
                    // @todo: the has many, etc. if checks only work if it is a relation on the first level, if we have a deeper where condition nesting this fails
                    if ($where['child'] !== '') {
                        continue;
                    }

                    $comparison = \is_array($where['value']) && \count($where['value']) > 1 ? 'in' : $where['logic'];
                    $query->where($this->mapper::TABLE . '_d' . $this->depth . '.' . $col, $comparison, $where['value'], $where['comparison']);
                }
            } elseif (isset($this->mapper::HAS_MANY[$member])) {
                /* variable in has many */
                foreach ($values as $index => $where) {
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
            } elseif (isset($this->mapper::BELONGS_TO[$member])) {
                /* variable in belogns to */
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
            } elseif (isset($this->mapper::OWNS_ONE[$member])) {
                /* variable in owns one */
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
                break;
            }

            foreach ($data as $index => $with) {
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

                    // todo: handle self and self === null
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
                $relMapper = $this->createRelationMapper($rel['mapper']::reader(db: $this->db), $member);
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
            foreach ($data as $index => $sort) {
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

            foreach ($data as $index => $limit) {
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
     * @param array $result Query result set
     * @param mixed $obj    Object to populate
     *
     * @return mixed
     *
     * @throws \UnexpectedValueException
     *
     * @since 1.0.0
     */
    public function populateAbstract(array $result, mixed $obj) : mixed
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
                $path    = \explode('/', $def['internal']);
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
                    // todo: find better solution. this was because of a bug with the sales billing list query depth = 4. The address was set (from the client, referral or creator) but then somehow there was a second address element which was all null and null cannot be asigned to a string variable (e.g. country). The problem with this solution is that if the model expects an initialization (e.g. at lest set the elements to null, '', 0 etc.) this is now not done.
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

        // @todo: MUST handle if member is in with here!!!

        if (isset($this->mapper::OWNS_ONE[$member]['column'])) {
            return $result[$mapper::getColumnByMember($this->mapper::OWNS_ONE[$member]['column']) . '_d' . $this->depth];
        }

        if (!isset($result[$mapper::PRIMARYFIELD . '_d' . ($this->depth + 1)])) {
            return $mapper::createNullModel();
        }

        $obj = $mapper::getInitialized($mapper, $result[$mapper::PRIMARYFIELD . '_d' . ($this->depth + 1)]);
        if ($obj !== null) {
            return $obj;
        }

        /** @var class-string<DataMapperFactory> $ownsOneMapper */
        $ownsOneMapper = $this->createRelationMapper($mapper::get($this->db), $member);
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

        // @todo: MUST handle if member is in with here!!! ???

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
            /** @var class-string<DataMapperFactory> $belongsToMapper */
            $belongsToMapper = $this->createRelationMapper($mapper::get($this->db), $member);
            $belongsToMapper->depth = $this->depth + 1;
            $belongsToMapper->where($this->mapper::BELONGS_TO[$member]['by'], $result[$mapper::getColumnByMember($this->mapper::BELONGS_TO[$member]['by']) . '_d' . $this->depth], '=');

            return $belongsToMapper->execute();
        }

        $obj = $mapper::getInitialized($mapper, $result[$mapper::PRIMARYFIELD . '_d' . ($this->depth + 1)]);
        if ($obj !== null) {
            return $obj;
        }

        /** @var class-string<DataMapperFactory> $belongsToMapper */
        $belongsToMapper = $this->createRelationMapper($mapper::get($this->db), $member);
        $belongsToMapper->depth = $this->depth + 1;

        return $belongsToMapper->populateAbstract($result, $mapper::createBaseModel());
    }

    /**
     * Fill object with relations
     *
     * @param mixed $obj       Object to fill
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function loadHasManyRelations(mixed $obj) : void
    {
        if (empty($this->with) || empty($this->mapper::HAS_MANY)) {
            return;
        }

        $primaryKey = $this->mapper::getObjectId($obj);
        if (empty($primaryKey)) {
            return;
        }

        $refClass = new \ReflectionClass($obj);

        // @todo: check if there are more cases where the relation is already loaded with joins etc.
        // there can be pseudo has many elements like localizations. They are has manies but these are already loaded with joins!
        foreach ($this->mapper::HAS_MANY as $member => $many) {
            if (isset($many['column']) || !isset($this->with[$member])) {
                continue;
            }

            $query = new Builder($this->db);
            $src   = $many['external'] ?? $many['mapper']::PRIMARYFIELD;

            // @todo: what if a specific column name is defined instead of primaryField for the join? Fix, it should be stored in 'column'
            $query->select($many['table'] . '.' . $src)
                ->from($many['table'])
                ->where($many['table'] . '.' . $many['self'], '=', $primaryKey);

            if ($many['mapper']::TABLE !== $many['table']) {
                $query->leftJoin($many['mapper']::TABLE)
                    ->on($many['table'] . '.' . $src, '=', $many['mapper']::TABLE . '.' . $many['mapper']::PRIMARYFIELD);
            }

            $sth = $this->db->con->prepare($query->toSql());
            if ($sth === false) {
                continue;
            }

            $sth->execute();
            $result =  $sth->fetchAll(\PDO::FETCH_COLUMN);

            $objects = $this->createRelationMapper($many['mapper']::get(db: $this->db), $member)
                ->where($many['mapper']::COLUMNS[$many['mapper']::PRIMARYFIELD]['internal'], $result, 'in')
                ->execute();

            $refProp = $refClass->getProperty($member);
            if (!$refProp->isPublic()) {
                $refProp->setAccessible(true);
                $refProp->setValue($obj, !\is_array($objects) && !isset($this->mapper::HAS_MANY[$member]['conditional'])
                    ? [$many['mapper']::getObjectId($objects) => $objects]
                    : $objects
                );
                $refProp->setAccessible(false);
            } else {
                $obj->{$member} = !\is_array($objects) && !isset($this->mapper::HAS_MANY[$member]['conditional'])
                    ? [$many['mapper']::getObjectId($objects) => $objects]
                    : $objects;
            }
        }
    }
}
