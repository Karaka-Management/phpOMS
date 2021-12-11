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

    public function execute(...$options) : mixed
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

        $this->deleteSingleRelation($obj, $refClass, $this->mapper::BELONGS_TO);
        $this->deleteHasMany($refClass, $obj, $objId);
        $this->deleteModel($objId);
        $this->deleteSingleRelation($obj, $refClass, $this->mapper::OWNS_ONE);

        return $objId;
    }

    private function deleteModel(mixed $objId) : void
    {
        $query = new Builder($this->db);
        $query->delete()
            ->from($this->mapper::TABLE)
            ->where($this->mapper::TABLE . '.' . $this->mapper::PRIMARYFIELD, '=', $objId);

        $sth = $this->db->con->prepare($query->toSql());
        if ($sth !== false) {
            $sth->execute();
        }
    }

    private function deleteSingleRelation(mixed $obj, \ReflectionClass $refClass, array $relation) : void
    {
        if (empty($relation)) {
            return;
        }

        foreach ($relation as $member => $relData) {
            if (!isset($this->with[$member])) {
                continue;
            }

            /** @var class-string<DataMapperFactory> $mapper */
            $mapper = $relData['mapper'];

            /** @var self $relMapper */
            $relMapper        = $this->createRelationMapper($mapper::delete(db: $this->db), $member);
            $relMapper->depth = $this->depth + 1;

            $refProp = $refClass->getProperty($member);
            if (!$refProp->isPublic()) {
                $refProp->setAccessible(true);
                $relMapper->execute($refProp->getValue($obj));
                $refProp->setAccessible(false);
            } else {
                $relMapper->execute($obj->{$member});
            }
        }
    }

    private function deleteHasMany(\ReflectionClass $refClass, object $obj, mixed $objId) : void
    {
        if (empty($this->mapper::HAS_MANY)) {
            return;
        }

        foreach ($this->mapper::HAS_MANY as $member => $rel) {
            // always
            if (!isset($this->with[$member]) && !isset($rel['external'])) {
                continue;
            }

            $objIds  = [];
            $refProp = $refClass->getProperty($member);
            if (!$refProp->isPublic()) {
                $refProp->setAccessible(true);
                $values = $refProp->getValue($obj);
                $refProp->setAccessible(false);
            } else {
                $values = $obj->{$member};
            }

            if (!\is_array($values)) {
                // conditionals
                continue;
            }

            /** @var class-string<DataMapperFactory> $mapper */
            $mapper             = $this->mapper::HAS_MANY[$member]['mapper'];
            $relReflectionClass = !empty($values) ? new \ReflectionClass(\reset($values)) : null;

            foreach ($values as $key => $value) {
                if (!\is_object($value)) {
                    // Is scalar => already in database
                    $objIds[$key] = $value;

                    continue;
                }

                $objIds[$key] = $mapper::getObjectId($value, $relReflectionClass);
            }

            // delete relation tables
            if (isset($rel['external'])) {
                $this->deleteRelationTable($member, $objIds, $objId);
            } else {
                // only delete related obj if it is NOT in a relation table
                // if it is not in a relation table it must be directly related
                // this means it CAN ONLY be related to this object and not others
                foreach ($objIds as $id) {
                    $mapper::delete(db: $this->db)->execute($id);
                }
            }
        }
    }

    public function deleteRelationTable(string $member, array $objIds = null, mixed $objId) : void
    {
        if ((empty($objIds) && $objIds !== null)
            || $this->mapper::HAS_MANY[$member]['table'] === $this->mapper::TABLE
            || $this->mapper::HAS_MANY[$member]['table'] === $this->mapper::HAS_MANY[$member]['mapper']::TABLE
        ) {
            return;
        }

        $relQuery = new Builder($this->db);
        $relQuery->delete()
            ->from($this->mapper::HAS_MANY[$member]['table'])
            ->where($this->mapper::HAS_MANY[$member]['table'] . '.' . $this->mapper::HAS_MANY[$member]['self'], '=', $objId);

        if ($objIds !== null) {
            $relQuery->where($this->mapper::HAS_MANY[$member]['table'] . '.' . $this->mapper::HAS_MANY[$member]['external'], 'in', $objIds);
        }

        $sth = $this->db->con->prepare($relQuery->toSql());
        if ($sth !== false) {
            $sth->execute();
        }
    }
}
