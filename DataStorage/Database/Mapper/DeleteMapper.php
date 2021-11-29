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

/**
 * Delete mapper (DELETE).
 *
 * @todo: allow to define where clause if no object is loaded yet
 *
 * @package phpOMS\DataStorage\Database\Mapper
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
class DeleteMapper extends DataMapperAbstract
{
    public function delete() : self
    {
        $this->type = MapperType::DELETE;

        return $this;
    }

    public function execute(array ...$options) : mixed
    {
        switch($this->type) {
            case MapperType::DELETE:
                return $this->executeDelete(...$options);
            default:
                return null;
        }
    }

    public function executeDelete(mixed $obj) : mixed
    {
        if ($obj === null) {
            $obj = $this->mapper::get()->execute(); // todo: pass where conditions to read mapper
        }

        $refClass = new \ReflectionClass($obj);
        $objId    = $this->mapper::getObjectId($obj, $refClass);

        if (empty($objId)) {
            return null;
        }

        $this->mapper::removeInitialized(static::class, $objId);
        $this->deleteHasMany($refClass, $obj, $objId);
        $this->deleteModel($obj, $objId, $refClass);

        return $objId;
    }

    private function deleteModel(object $obj, mixed $objId, \ReflectionClass $refClass = null) : void
    {
        $query = new Builder($this->db);
        $query->delete()
            ->from($this->mapper::TABLE)
            ->where($this->mapper::TABLE . '.' . $this->mapper::PRIMARYFIELD, '=', $objId);

        $refClass   = $refClass ?? new \ReflectionClass($obj);
        $properties = $refClass->getProperties();

        foreach ($properties as $property) {
            $propertyName = $property->getName();

            if (isset($this->mapper::HAS_MANY[$propertyName])) {
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
            foreach ($this->mapper::COLUMNS as $key => $column) {
                $value = $isPublic ? $obj->{$propertyName} : $property->getValue($obj);
                if (isset($this->mapper::OWNS_ONE[$propertyName])
                        && $column['internal'] === $propertyName
                ) {
                    $this->deleteOwnsOne($propertyName, $value);
                    break;
                } elseif (isset($this->mapper::BELONGS_TO[$propertyName])
                        && $column['internal'] === $propertyName
                ) {
                    $this->deleteBelongsTo($propertyName, $value);
                    break;
                }
            }

            if (!$isPublic) {
                $property->setAccessible(false);
            }
        }

        $sth = $this->db->con->prepare($query->toSql());
        if ($sth !== false) {
            $sth->execute();
        }
    }

    private function deleteBelongsTo(string $propertyName, mixed $obj) : mixed
    {
        if (!\is_object($obj)) {
            return $obj;
        }

        /** @var class-string<DataMapperFactory> $mapper */
        $mapper = $this->mapper::BELONGS_TO[$propertyName]['mapper'];

         /** @var self $relMapper */
        $relMapper = $this->createRelationMapper($mapper::delete(db: $this->db), $propertyName);
        $relMapper->depth = $this->depth + 1;

        return $relMapper->execute($obj);
    }

    private function deleteOwnsOne(string $propertyName, mixed $obj) : mixed
    {
        if (!\is_object($obj)) {
            return $obj;
        }

        /** @var class-string<DataMapperFactory> $mapper */
        $mapper = $this->mapper::OWNS_ONE[$propertyName]['mapper'];

        /**
         * @todo Orange-Management/phpOMS#??? [p:low] [t:question] [d:expert]
         *  Deleting a owned one object is not recommended since it can be owned by something else?
         *  Or does owns one mean that nothing else can have a relation to this model?
         */

         /** @var self $relMapper */
        $relMapper = $this->createRelationMapper($mapper::delete(db: $this->db), $propertyName);
        $relMapper->depth = $this->depth + 1;

        return $relMapper->execute($obj);
    }

    private function deleteHasMany(\ReflectionClass $refClass, object $obj, mixed $objId) : void
    {
        if (empty($this->with) || empty($this->mapper::HAS_MANY)) {
            return;
        }

        foreach ($this->mapper::HAS_MANY as $propertyName => $rel) {
            if (!isset($this->mapper::HAS_MANY[$propertyName]['mapper'])) {
                throw new InvalidMapperException();
            }

            if (isset($rel['column']) || !isset($this->with[$propertyName])) {
                continue;
            }

            $property = $refClass->getProperty($propertyName);

            if (!($isPublic = $property->isPublic())) {
                $property->setAccessible(true);
                $values = $property->getValue($obj);
                $property->setAccessible(false);
            } else {
                $values = $obj->{$propertyName};
            }

            if (!\is_array($values)) {
                // conditionals
                continue;
            }

            /** @var class-string<DataMapperFactory> $mapper */
            $mapper             = $this->mapper::HAS_MANY[$propertyName]['mapper'];
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
                    $objsIds[$key] = $mapper::delete(db: $this->db)->execute($value);

                    continue;
                }

                /**
                 * @todo Orange-Management/phpOMS#233
                 *  On delete the relations and relation tables need to be deleted first
                 *  The exception is of course the belongsTo relation.
                 */
            }

            $this->deleteRelationTable($propertyName, $objsIds, $objId);
        }
    }

    public function deleteRelation(string $member, mixed $id1, mixed $id2) : bool
    {
        if (!isset($this->mapper::HAS_MANY[$member]) || !isset($this->mapper::HAS_MANY[$member]['external'])) {
            return false;
        }

        $this->mapper::removeInitialized(static::class, $id1);
        $this->deleteRelationTable($member, \is_array($id2) ? $id2 : [$id2], $id1);

        return true;
    }

    public function deleteRelationTable(string $propertyName, array $objsIds, mixed $objId) : void
    {
        if (empty($objsIds)
            || $this->mapper::HAS_MANY[$propertyName]['table'] === $this->mapper::TABLE
            || $this->mapper::HAS_MANY[$propertyName]['table'] === $this->mapper::HAS_MANY[$propertyName]['mapper']::TABLE
        ) {
            return;
        }

        foreach ($objsIds as $src) {
            $relQuery = new Builder($this->db);
            $relQuery->delete()
                ->from($this->mapper::HAS_MANY[$propertyName]['table'])
                ->where($this->mapper::HAS_MANY[$propertyName]['table'] . '.' . $this->mapper::HAS_MANY[$propertyName]['external'], '=', $src)
                ->where($this->mapper::HAS_MANY[$propertyName]['table'] . '.' . $this->mapper::HAS_MANY[$propertyName]['self'], '=', $objId, 'and');

            $sth = $this->db->con->prepare($relQuery->toSql());
            if ($sth !== false) {
                $sth->execute();
            }
        }
    }
}
