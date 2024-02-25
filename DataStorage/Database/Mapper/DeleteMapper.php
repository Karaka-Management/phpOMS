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

/**
 * Delete mapper (DELETE).
 *
 * @package phpOMS\DataStorage\Database\Mapper
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class DeleteMapper extends DataMapperAbstract
{
    /**
     * Get delete mapper
     *
     * @return self
     *
     * @since 1.0.0
     */
    public function delete() : self
    {
        $this->type = MapperType::DELETE;

        return $this;
    }

    /**
     * Execute mapper
     *
     * @param mixed ...$options Options to pass to the selete mapper
     *
     * @return mixed
     *
     * @since 1.0.0
     */
    public function execute(mixed ...$options) : mixed
    {
        switch($this->type) {
            case MapperType::DELETE:
                /** @var object[] ...$options */
                return $this->executeDelete(...$options);
            default:
                return null;
        }
    }

    /**
     * Execute mapper
     *
     * @param object $obj Object to delete
     *
     * @return mixed
     *
     * @since 1.0.0
     */
    public function executeDelete(object $obj) : mixed
    {
        $refClass = null;
        $objId    = $this->mapper::getObjectId($obj);

        if (empty($objId)) {
            return null;
        }

        $this->deleteSingleRelation($obj, $this->mapper::OWNS_ONE, $refClass);
        $this->deleteHasMany($obj, $objId, $refClass);
        $this->deleteModel($objId);
        $this->deleteSingleRelation($obj, $this->mapper::BELONGS_TO, $refClass);

        return $objId;
    }

    /**
     * Delete model
     *
     * @param mixed $objId Object id to delete
     *
     * @return void
     *
     * @since 1.0.0
     */
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

    /**
     * Delete ownsOne, belongsTo relations
     *
     * @param object                $obj      Object to delete
     * @param array                 $relation Relation data (e.g. ::BELONGS_TO, ::OWNS_ONE)
     * @param null|\ReflectionClass $refClass Reflection of object to delete
     *
     * @return void
     *
     * @since 1.0.0
     */
    private function deleteSingleRelation(object $obj, array $relation, ?\ReflectionClass &$refClass = null) : void
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

            $isPrivate = $relData['private'] ?? false;

            $value = null;
            if ($isPrivate) {
                $refClass ??= new \ReflectionClass($obj);

                $refProp = $refClass->getProperty($member);
                $value   = $refProp->getValue($obj);
            } else {
                $value = $obj->{$member};
            }

            $relMapper->execute($value);
        }
    }

    /**
     * Delete hasMany
     *
     * @param object                $obj      Object to delete
     * @param mixed                 $objId    Object id to delete
     * @param null|\ReflectionClass $refClass Reflection of object to delete
     *
     * @return void
     *
     * @since 1.0.0
     */
    private function deleteHasMany(object $obj, mixed $objId, ?\ReflectionClass &$refClass = null) : void
    {
        if (empty($this->mapper::HAS_MANY)) {
            return;
        }

        foreach ($this->mapper::HAS_MANY as $member => $rel) {
            if (!isset($this->with[$member]) && !isset($rel['external'])) {
                continue;
            }

            $objIds    = [];
            $isPrivate = $rel['private'] ?? false;

            $values = null;
            if ($isPrivate) {
                $refClass ??= new \ReflectionClass($obj);

                $refProp = $refClass->getProperty($member);
                $values  = $refProp->getValue($obj);
            } else {
                $values = $obj->{$member};
            }

            if (!\is_array($values)) {
                // conditionals
                continue;
            }

            /** @var class-string<DataMapperFactory> $mapper */
            $mapper = $this->mapper::HAS_MANY[$member]['mapper'];

            foreach ($values as $key => $value) {
                if (!\is_object($value)) {
                    // Is scalar => already in database
                    $objIds[$key] = $value;

                    continue;
                }

                $objIds[$key] = $mapper::getObjectId($value);
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

    /**
     * Delete has many relations if the relation is handled in a relation table
     *
     * @param string $member Property which contains the has many models
     * @param array  $objIds Objects which are related to the parent object
     * @param mixed  $objId  Parent object id
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function deleteRelationTable(string $member, ?array $objIds = null, mixed $objId) : void
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
            $relQuery->where($this->mapper::HAS_MANY[$member]['table'] . '.' . $this->mapper::HAS_MANY[$member]['external'], 'IN', $objIds);
        }

        $sth = $this->db->con->prepare($relQuery->toSql());
        if ($sth !== false) {
            $sth->execute();
        }
    }
}
