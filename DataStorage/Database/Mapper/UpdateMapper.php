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

use phpOMS\DataStorage\Database\Exception\InvalidMapperException;
use phpOMS\DataStorage\Database\Query\Builder;
use phpOMS\Utils\ArrayUtils;

/**
 * Update mapper (CREATE).
 *
 * @package phpOMS\DataStorage\Database\Mapper
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class UpdateMapper extends DataMapperAbstract
{
    /**
     * Create update mapper
     *
     * @return self
     *
     * @since 1.0.0
     */
    public function update() : self
    {
        $this->type = MapperType::UPDATE;

        return $this;
    }

    /**
     * Execute mapper
     *
     * @param mixed ...$options Options to pass to update mapper
     *
     * @return mixed
     *
     * @since 1.0.0
     */
    public function execute(mixed ...$options) : mixed
    {
        switch($this->type) {
            case MapperType::UPDATE:
                /** @var object ...$options */
                return $this->executeUpdate(...$options);
            default:
                return null;
        }
    }

    /**
     * Execute mapper
     *
     * @param object $obj Object to update
     *
     * @return mixed
     *
     * @since 1.0.0
     */
    public function executeUpdate(object $obj) : mixed
    {
        $refClass = null;
        $objId    = $this->mapper::getObjectId($obj);

        if ($this->mapper::isNullModel($obj)) {
            return $objId === 0 ? null : $objId;
        }

        $this->updateHasMany($obj, $objId, $refClass);

        if (empty($objId)) {
            return $this->mapper::create(db: $this->db)->execute($obj);
        }

        $this->updateModel($obj, $objId, $refClass);

        return $objId;
    }

    /**
     * Update model
     *
     * @param object           $obj      Object to update
     * @param mixed            $objId    Id of the object to update
     * @param \ReflectionClass $refClass Reflection of the object ot update
     *
     * @return void
     *
     * @since 1.0.0
     */
    private function updateModel(object $obj, mixed $objId, ?\ReflectionClass &$refClass = null) : void
    {
        try {
            // Model doesn't have anything to update
            if (\count($this->mapper::COLUMNS) < 2) {
                return;
            }

            $query = new Builder($this->db);
            $query->update($this->mapper::TABLE)
                ->where($this->mapper::TABLE . '.' . $this->mapper::PRIMARYFIELD, '=', $objId);

            foreach ($this->mapper::COLUMNS as $column) {
                $propertyName = \stripos($column['internal'], '/') !== false
                    ? \explode('/', $column['internal'])[0]
                    : $column['internal'];

                if (isset($this->mapper::HAS_MANY[$propertyName])
                    || $column['internal'] === $this->mapper::PRIMARYFIELD
                    || (($column['readonly'] ?? false) && !isset($this->with[$propertyName]))
                    || (($column['writeonly'] ?? false) && !isset($this->with[$propertyName]))
                ) {
                    continue;
                }

                $isPrivate = $column['private'] ?? false;
                $property  = null;
                $tValue    = null;

                if ($isPrivate) {
                    $refClass ??= new \ReflectionClass($obj);

                    $property = $refClass->getProperty($propertyName);
                    $tValue   = $property->getValue($obj);
                } else {
                    $tValue = $obj->{$propertyName};
                }

                if (isset($this->mapper::OWNS_ONE[$propertyName])) {
                    $id    = \is_object($tValue) ? $this->updateOwnsOne($propertyName, $tValue) : $tValue;
                    $value = $this->parseValue($column['type'], $id);

                    $query->set([$column['name'] => $value]);
                } elseif (isset($this->mapper::BELONGS_TO[$propertyName])) {
                    $id    = \is_object($tValue) ? $this->updateBelongsTo($propertyName, $tValue) : $tValue;
                    $value = $this->parseValue($column['type'], $id);

                    $query->set([$column['name'] => $value]);
                } elseif ($column['name'] !== $this->mapper::PRIMARYFIELD) {
                    if (\stripos($column['internal'], '/') !== false) {
                        $path   = \substr($column['internal'], \stripos($column['internal'], '/') + 1);
                        $tValue = ArrayUtils::getArray($path, $tValue, '/');
                    }

                    $value = $this->parseValue($column['type'], $tValue);

                    $query->set([$column['name'] => $value]);
                }
            }

            $sth = $this->db->con->prepare($query->toSql());
            if ($sth === false) {
                throw new \Exception();
            }

            $deadlock = 0;
            do {
                $repeat = false;
                try {
                    ++$deadlock;
                    $sth->execute();
                } catch (\Throwable $t) {
                    if ($deadlock > 3 || $t->errorInfo[1] !== 1213) {
                        throw $t;
                    }

                    \usleep(10000);
                    $repeat = true;
                }
            } while ($repeat);
        } catch (\Throwable $t) {
            // @codeCoverageIgnoreStart
            \phpOMS\Log\FileLogger::getInstance()->error(
                \phpOMS\Log\FileLogger::MSG_FULL, [
                    'message' => $t->getMessage() . ':' . $query->toSql(),
                    'line'    => __LINE__,
                    'file'    => self::class,
                ]
            );
            // @codeCoverageIgnoreEnd
        }
    }

    /**
     * Update belongs to
     *
     * @param string $propertyName Name of the property to update
     * @param object $obj          Object to update
     *
     * @return mixed
     *
     * @since 1.0.0
     */
    private function updateBelongsTo(string $propertyName, object $obj) : mixed
    {
        /** @var class-string<DataMapperFactory> $mapper */
        $mapper = $this->mapper::BELONGS_TO[$propertyName]['mapper'];

        if (isset($this->with[$propertyName])) {
            /** @var self $relMapper */
            $relMapper        = $this->createRelationMapper($mapper::update(db: $this->db), $propertyName);
            $relMapper->depth = $this->depth + 1;

            $id = $relMapper->execute($obj);

            if (!isset($this->mapper::OWNS_ONE[$propertyName]['by'])) {
                return $id;
            }

            if ($this->mapper::OWNS_ONE[$propertyName]['private'] ?? false) {
                $refClass = new \ReflectionClass($obj);
                $refProp  = $refClass->getProperty($this->mapper::OWNS_ONE[$propertyName]['by']);
                $value    = $refProp->getValue($obj);
            } else {
                $value = $obj->{$this->mapper::OWNS_ONE[$propertyName]['by']};
            }

            return $value;
        }

        if (isset($this->mapper::BELONGS_TO[$propertyName]['by'])) {
            // has by (obj is stored as a different model e.g. model = profile but reference/db is account)
            if ($this->mapper::BELONGS_TO[$propertyName]['private'] ?? false) {
                $refClass = new \ReflectionClass($obj);
                $refProp  = $refClass->getProperty($this->mapper::BELONGS_TO[$propertyName]['by']);
                $obj      = $refProp->getValue($obj);
            } else {
                $obj = $obj->{$this->mapper::BELONGS_TO[$propertyName]['by']};
            }

            if (!\is_object($obj)) {
                return $obj;
            }
        }

        $id = $mapper::getObjectId($obj);

        return empty($id) && $mapper::isNullModel($obj)
            ? null
            : $id;
    }

    /**
     * Update owns one
     *
     * @param string $propertyName Name of the property to update
     * @param object $obj          Object to update
     *
     * @return mixed
     *
     * @since 1.0.0
     */
    private function updateOwnsOne(string $propertyName, object $obj) : mixed
    {
        /** @var class-string<DataMapperFactory> $mapper */
        $mapper = $this->mapper::OWNS_ONE[$propertyName]['mapper'];

        if (isset($this->with[$propertyName])) {
            /** @var self $relMapper */
            $relMapper        = $this->createRelationMapper($mapper::update(db: $this->db), $propertyName);
            $relMapper->depth = $this->depth + 1;

            $id = $relMapper->execute($obj);

            if (!isset($this->mapper::OWNS_ONE[$propertyName]['by'])) {
                return $id;
            }

            if ($this->mapper::OWNS_ONE[$propertyName]['private'] ?? false) {
                $refClass = new \ReflectionClass($obj);
                $refProp  = $refClass->getProperty($this->mapper::OWNS_ONE[$propertyName]['by']);
                $value    = $refProp->getValue($obj);
            } else {
                $value = $obj->{$this->mapper::OWNS_ONE[$propertyName]['by']};
            }

            return $value;
        }

        if (isset($this->mapper::OWNS_ONE[$propertyName]['by'])) {
            // has by (obj is stored as a different model e.g. model = profile but reference/db is account)
            if ($this->mapper::OWNS_ONE[$propertyName]['private'] ?? false) {
                $refClass = new \ReflectionClass($obj);
                $refProp  = $refClass->getProperty($this->mapper::OWNS_ONE[$propertyName]['by']);
                $obj      = $refProp->getValue($obj);
            } else {
                $obj = $obj->{$this->mapper::OWNS_ONE[$propertyName]['by']};
            }

            if (!\is_object($obj)) {
                return $obj;
            }
        }

        $id = $mapper::getObjectId($obj);

        return empty($id) && $mapper::isNullModel($obj)
            ? null
            : $id;
    }

    /**
     * Update has many relations
     *
     * @param object                $obj      Object which contains the relations
     * @param mixed                 $objId    Object id which contains the relations
     * @param null|\ReflectionClass $refClass Reflection of the object containing the relations
     *
     * @return void
     *
     * @throws InvalidMapperException
     *
     * @since 1.0.0
     */
    private function updateHasMany(object $obj, mixed $objId, ?\ReflectionClass &$refClass = null) : void
    {
        if (empty($this->with) || empty($this->mapper::HAS_MANY)) {
            return;
        }

        $objsIds = [];

        foreach ($this->mapper::HAS_MANY as $propertyName => $rel) {
            if ($rel['readonly'] ?? false === true) {
                continue;
            }

            if (!isset($this->mapper::HAS_MANY[$propertyName]['mapper'])) {
                throw new InvalidMapperException();
            }

            $isPrivate = $rel['private'] ?? false;
            $property  = null;
            $values    = null;

            if ($isPrivate) {
                $refClass ??= new \ReflectionClass($obj);

                $property = $refClass->getProperty($propertyName);
                $values   = $property->getValue($obj);
            } else {
                $values = $obj->{$propertyName};
            }

            if (!\is_array($values) || empty($values)) {
                continue;
            }

            /** @var class-string<DataMapperFactory> $mapper */
            $mapper       = $this->mapper::HAS_MANY[$propertyName]['mapper'];
            $isPrivateRel = $this->mapper::HAS_MANY[$propertyName]['private'] ?? false;

            if ($isPrivateRel) {
                $relReflectionClass = new \ReflectionClass(\reset($values));
            }

            $objsIds[$propertyName] = [];

            foreach ($values as $key => &$value) {
                if (!\is_object($value)) {
                    // Is scalar => already in database
                    $objsIds[$propertyName][$key] = $value;

                    continue;
                }

                $primaryKey = $mapper::getObjectId($value);

                // already in db
                if (!empty($primaryKey)) {
                    /** @var self $relMapper */
                    $relMapper        = $this->createRelationMapper($mapper::update(db: $this->db), $propertyName);
                    $relMapper->depth = $this->depth + 1;

                    $relMapper->execute($value);

                    $objsIds[$propertyName][$key] = $primaryKey;

                    continue;
                }

                // create if not existing
                if ($this->mapper::HAS_MANY[$propertyName]['table'] === $this->mapper::HAS_MANY[$propertyName]['mapper']::TABLE
                    && isset($mapper::COLUMNS[$this->mapper::HAS_MANY[$propertyName]['self']])
                ) {
                    if ($isPrivateRel) {
                        $relProperty = $relReflectionClass->getProperty($mapper::COLUMNS[$this->mapper::HAS_MANY[$propertyName]['self']]['internal']);
                        $relProperty->setValue($value, $objId);
                    } else {
                        $value->{$mapper::COLUMNS[$this->mapper::HAS_MANY[$propertyName]['self']]['internal']} = $objId;
                    }
                }

                $objsIds[$propertyName][$key] = $mapper::create(db: $this->db)->execute($value);
            }
        }

        $this->updateRelationTable($objsIds, $objId);
    }

    /**
     * Update has many relations if the relation is handled in a relation table
     *
     * @param array $objsIds Objects which should be related to the parent object
     * @param mixed $objId   Parent object id
     *
     * @return void
     *
     * @since 1.0.0
     */
    private function updateRelationTable(array $objsIds, mixed $objId) : void
    {
        foreach ($this->mapper::HAS_MANY as $member => $many) {
            if (isset($many['column']) || !isset($this->with[$member])) {
                continue;
            }

            $query = new Builder($this->db);
            $src   = $many['external'] ?? $many['mapper']::PRIMARYFIELD;

            // @todo what if a specific column name is defined instead of primaryField for the join? Fix, it should be stored in 'column'
            $query->select($many['table'] . '.' . $src)
                ->from($many['table'])
                ->where($many['table'] . '.' . $many['self'], '=', $objId);

            if ($many['table'] !== $many['mapper']::TABLE) {
                $query->leftJoin($many['mapper']::TABLE)
                    ->on($many['table'] . '.' . $src, '=', $many['mapper']::TABLE . '.' . $many['mapper']::PRIMARYFIELD);
            }

            $sth = $this->db->con->prepare($query->toSql());
            if ($sth === false) {
                continue;
            }

            $sth->execute();
            $result = $sth->fetchAll(\PDO::FETCH_COLUMN);

            if ($result === false) {
                return; // @codeCoverageIgnore
            }

            $removes = \array_diff($result, \array_values($objsIds[$member] ?? []));
            $adds    = \array_diff(\array_values($objsIds[$member] ?? []), $result);

            if (!empty($removes)) {
                $this->mapper::remover(db: $this->db)->deleteRelationTable($member, $removes, $objId);
            }

            if (!empty($adds) && isset($this->mapper::HAS_MANY[$member]['external'])) {
                $this->mapper::writer(db: $this->db)->createRelationTable($member, $adds, $objId);
            }
        }
    }
}
