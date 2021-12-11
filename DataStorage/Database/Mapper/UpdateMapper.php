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

use phpOMS\DataStorage\Database\Exception\InvalidMapperException;
use phpOMS\DataStorage\Database\Query\Builder;
use phpOMS\Utils\ArrayUtils;

/**
 * Update mapper (CREATE).
 *
 * @todo: allow to define single fields which should be updated (e.g. only description)
 * @todo: allow to define where clause if no object is loaded yet
 *
 * @package phpOMS\DataStorage\Database\Mapper
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
class UpdateMapper extends DataMapperAbstract
{
    public function update() : self
    {
        $this->type = MapperType::UPDATE;

        return $this;
    }

    public function execute(...$options) : mixed
    {
        switch($this->type) {
            case MapperType::UPDATE:
                return $this->executeUpdate(...$options);
            default:
                return null;
        }
    }

    public function executeUpdate(mixed $obj) : mixed
    {
        if (!isset($obj)) {
            return null;
        }

        $refClass = new \ReflectionClass($obj);
        $objId    = $this->mapper::getObjectId($obj, $refClass);

        if ($this->mapper::isNullModel($obj)) {
            return $objId === 0 ? null : $objId;
        }

        $this->updateHasMany($refClass, $obj, $objId);

        if (empty($objId)) {
            return $this->mapper::create(db: $this->db)->execute($obj);
        }

        $this->updateModel($obj, $objId, $refClass);

        return $objId;
    }

    private function updateModel(object $obj, mixed $objId, \ReflectionClass $refClass = null) : void
    {
        // Model doesn't have anything to update
        if (\count($this->mapper::COLUMNS) < 2) {
            return;
        }

        $query = new Builder($this->db);
        $query->update($this->mapper::TABLE)
            ->where($this->mapper::TABLE . '.' . $this->mapper::PRIMARYFIELD, '=', $objId);

        foreach ($this->mapper::COLUMNS as $column) {
            $propertyName = \stripos($column['internal'], '/') !== false ? \explode('/', $column['internal'])[0] : $column['internal'];
            if (isset($this->mapper::HAS_MANY[$propertyName])
                || $column['internal'] === $this->mapper::PRIMARYFIELD
                || ($column['readonly'] ?? false === true)
            ) {
                continue;
            }

            $refClass = $refClass ?? new \ReflectionClass($obj);
            $property = $refClass->getProperty($propertyName);

            if (!($isPublic = $property->isPublic())) {
                $property->setAccessible(true);
                $tValue = $property->getValue($obj);
                $property->setAccessible(false);
            } else {
                $tValue = $obj->{$propertyName};
            }

            if (isset($this->mapper::OWNS_ONE[$propertyName])) {
                $id    = $this->updateOwnsOne($propertyName, $tValue);
                $value = $this->parseValue($column['type'], $id);

                /**
                 * @todo Orange-Management/phpOMS#232
                 *  If a model gets updated all it's relations are also updated.
                 *  This should be prevented if the relations didn't change.
                 *  No solution yet.
                 */
                $query->set([$this->mapper::TABLE . '.' . $column['name'] => $value]);
            } elseif (isset($this->mapper::BELONGS_TO[$propertyName])) {
                $id    = $this->updateBelongsTo($propertyName, $tValue);
                $value = $this->parseValue($column['type'], $id);

                /**
                 * @todo Orange-Management/phpOMS#232
                 *  If a model gets updated all it's relations are also updated.
                 *  This should be prevented if the relations didn't change.
                 *  No solution yet.
                 */
                $query->set([$this->mapper::TABLE . '.' . $column['name'] => $value]);
            } elseif ($column['name'] !== $this->mapper::PRIMARYFIELD) {
                if (\stripos($column['internal'], '/') !== false) {
                    $path   = \substr($column['internal'], \stripos($column['internal'], '/') + 1);
                    $tValue = ArrayUtils::getArray($path, $tValue, '/');
                }

                $value = $this->parseValue($column['type'], $tValue);

                $query->set([$this->mapper::TABLE . '.' . $column['name'] => $value]);
            }
        }

        try {
            $sth = $this->db->con->prepare($query->toSql());
            if ($sth !== false) {
                $sth->execute();
            }
        } catch (\Throwable $t) {
            echo $t->getMessage();
            echo $query->toSql();
        }
    }

    private function updateBelongsTo(string $propertyName, mixed $obj) : mixed
    {
        if (!\is_object($obj)) {
            return $obj;
        }

        /** @var class-string<DataMapperFactory> $mapper */
        $mapper = $this->mapper::BELONGS_TO[$propertyName]['mapper'];

        /** @var self $relMapper */
        $relMapper = $this->createRelationMapper($mapper::update(db: $this->db), $propertyName);
        $relMapper->depth = $this->depth + 1;

        return $relMapper->execute($obj);
    }

    private function updateOwnsOne(string $propertyName, mixed $obj) : mixed
    {
        if (!\is_object($obj)) {
            return $obj;
        }

        /** @var class-string<DataMapperFactory> $mapper */
        $mapper = $this->mapper::OWNS_ONE[$propertyName]['mapper'];

        /** @var self $relMapper */
        $relMapper = $this->createRelationMapper($mapper::update(db: $this->db), $propertyName);
        $relMapper->depth = $this->depth + 1;

        return $relMapper->execute($obj);
    }

    private function updateHasMany(\ReflectionClass $refClass, object $obj, mixed $objId) : void
    {
        // @todo: what if has_one has a has_many child (see readmapper, we already solved this here)
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

            $property = $refClass->getProperty($propertyName);

            if (!($isPublic = $property->isPublic())) {
                $property->setAccessible(true);
                $values = $property->getValue($obj);
                $property->setAccessible(false);
            } else {
                $values = $obj->{$propertyName};
            }

            if (!\is_array($values) || empty($values)) {
                continue;
            }

            /** @var class-string<DataMapperFactory> $mapper */
            $mapper                 = $this->mapper::HAS_MANY[$propertyName]['mapper'];
            $relReflectionClass     = new \ReflectionClass(\reset($values));
            $objsIds[$propertyName] = [];

            foreach ($values as $key => &$value) {
                if (!\is_object($value)) {
                    // Is scalar => already in database
                    $objsIds[$propertyName][$key] = $value;

                    continue;
                }

                $primaryKey = $mapper::getObjectId($value, $relReflectionClass);

                // already in db
                if (!empty($primaryKey)) {
                    /** @var self $relMapper */
                    $relMapper = $this->createRelationMapper($mapper::update(db: $this->db), $propertyName);
                    $relMapper->depth = $this->depth + 1;

                    $relMapper->execute($value);

                    $objsIds[$propertyName][$key] = $primaryKey;

                    continue;
                }

                // create if not existing
                if ($this->mapper::HAS_MANY[$propertyName]['table'] === $this->mapper::HAS_MANY[$propertyName]['mapper']::TABLE
                    && isset($mapper::COLUMNS[$this->mapper::HAS_MANY[$propertyName]['self']])
                ) {
                    $relProperty = $relReflectionClass->getProperty($mapper::COLUMNS[$this->mapper::HAS_MANY[$propertyName]['self']]['internal']);

                    if (!$isPublic) {
                        $relProperty->setAccessible(true);
                        $relProperty->setValue($value, $objId);
                        $relProperty->setAccessible(false);
                    } else {
                        $value->{$mapper::COLUMNS[$this->mapper::HAS_MANY[$propertyName]['self']]['internal']} = $objId;
                    }
                }

                $objsIds[$propertyName][$key] = $mapper::create(db: $this->db)->execute($value); // @todo: pass where
            }
        }

        $this->updateRelationTable($objsIds, $objId);
    }

    private function updateRelationTable(array $objsIds, mixed $objId) : void
    {
        foreach ($this->mapper::HAS_MANY as $member => $many) {
            if (isset($many['column']) || !isset($this->with[$member])) {
                continue;
            }

            $query = new Builder($this->db);
            $src   = $many['external'] ?? $many['mapper']::PRIMARYFIELD;

            // @todo: what if a specific column name is defined instead of primaryField for the join? Fix, it should be stored in 'column'
            $query->select($many['table'] . '.' . $src)
                ->from($many['table'])
                ->where($many['table'] . '.' . $many['self'], '=', $objId);

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

            $removes = \array_diff($result, \array_values($objsIds[$member] ?? []));
            $adds    = \array_diff(\array_values($objsIds[$member] ?? []), $result);

            if (!empty($removes)) {
                $this->mapper::remover(db: $this->db)->deleteRelationTable($member, $removes, $objId);
            }

            if (!empty($adds)) {
                $this->mapper::writer(db: $this->db)->createRelationTable($member, $adds, $objId);
            }
        }
    }
}
