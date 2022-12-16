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
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\DataStorage\Database\Mapper;

use phpOMS\DataStorage\Database\Exception\InvalidMapperException;
use phpOMS\DataStorage\Database\Query\Builder;
use phpOMS\DataStorage\Database\Query\QueryType;
use phpOMS\Utils\ArrayUtils;

/**
 * Write mapper (CREATE).
 *
 * @package phpOMS\DataStorage\Database\Mapper
 * @license OMS License 1.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class WriteMapper extends DataMapperAbstract
{
    /**
     * Create create mapper
     *
     * @return self
     *
     * @since 1.0.0
     */
    public function create() : self
    {
        $this->type = MapperType::CREATE;

        return $this;
    }

    /**
     * Execute mapper
     *
     * @param mixed ...$options Model to create
     *
     * @return mixed
     *
     * @since 1.0.0
     */
    public function execute(mixed ...$options) : mixed
    {
        switch($this->type) {
            case MapperType::CREATE:
                /** @var object ...$options */
                return $this->executeCreate(...$options);
            default:
                return null;
        }
    }

    /**
     * Create object
     *
     * @param object $obj Object to create
     *
     * @return mixed
     *
     * @since 1.0.0
     */
    public function executeCreate(object $obj) : mixed
    {
        $refClass = new \ReflectionClass($obj);

        if ($this->mapper::isNullModel($obj)) {
            $objId = $this->mapper::getObjectId($obj, $refClass);

            return $objId === 0 ? null : $objId;
        }

        if (!empty($id = $this->mapper::getObjectId($obj, $refClass)) && $this->mapper::AUTOINCREMENT) {
            $objId = $id;
        } else {
            $objId = $this->createModel($obj, $refClass);
            $this->mapper::setObjectId($refClass, $obj, $objId);
        }

        $this->createHasMany($refClass, $obj, $objId);

        return $objId;
    }

    /**
     * Create model
     *
     * @param object           $obj      Object to create
     * @param \ReflectionClass $refClass Reflection of the object to create
     *
     * @return mixed
     *
     * @since 1.0.0
     */
    private function createModel(object $obj, \ReflectionClass $refClass) : mixed
    {
        $query = new Builder($this->db);
        $query->into($this->mapper::TABLE);

        $publicProperties = \get_object_vars($obj);

        foreach ($this->mapper::COLUMNS as $column) {
            $propertyName = \stripos($column['internal'], '/') !== false
                ? \explode('/', $column['internal'])[0]
                : $column['internal'];

            if (isset($this->mapper::HAS_MANY[$propertyName])
                || ($column['name'] === $this->mapper::PRIMARYFIELD && $this->mapper::AUTOINCREMENT)
            ) {
                continue;
            }

            if (!isset($publicProperties[$propertyName])) {
                $property = $refClass->getProperty($propertyName);
                $property->setAccessible(true);
                $tValue = $property->getValue($obj);
                $property->setAccessible(false);
            } else {
                $tValue = $publicProperties[$propertyName];
            }

            if (isset($this->mapper::OWNS_ONE[$propertyName])) {
                $id    = \is_object($tValue) ? $this->createOwnsOne($propertyName, $tValue) : $tValue;
                $value = $this->parseValue($column['type'], $id);

                $query->insert($column['name'])->value($value);
            } elseif (isset($this->mapper::BELONGS_TO[$propertyName])) {
                $id    = \is_object($tValue) ? $this->createBelongsTo($propertyName, $tValue) : $tValue;
                $value = $this->parseValue($column['type'], $id);

                $query->insert($column['name'])->value($value);
            } else {
                if (\stripos($column['internal'], '/') !== false) {
                    /** @var array $tValue */
                    $path   = \substr($column['internal'], \stripos($column['internal'], '/') + 1);
                    $tValue = ArrayUtils::getArray($path, $tValue, '/');
                }

                $value = $this->parseValue($column['type'], $tValue);

                $query->insert($column['name'])->value($value);
            }
        }

        // if a table only has a single column = primary key column. This must be done otherwise the query is empty
        if ($query->getType() === QueryType::NONE) {
            $query->insert($this->mapper::PRIMARYFIELD)->value(0);
        }

        try {
            $sth = $this->db->con->prepare($query->toSql());
            $sth->execute();
        } catch (\Throwable $t) {
            // @codeCoverageIgnoreStart
            \var_dump($t->getMessage());
            \var_dump($a = $query->toSql());
            \var_dump(\debug_backtrace());

            return -1;
            // @codeCoverageIgnoreEND
        }

        $objId = empty($id = $this->mapper::getObjectId($obj, $refClass)) ? $this->db->con->lastInsertId() : $id;
        \settype($objId, $this->mapper::COLUMNS[$this->mapper::PRIMARYFIELD]['type']);

        return $objId;
    }

    /**
     * Create owns one model
     *
     * @param string $propertyName Name of the owns one property
     * @param object $obj          Object which contains the owns one model
     *
     * @return mixed
     *
     * @since 1.0.0
     */
    private function createOwnsOne(string $propertyName, object $obj) : mixed
    {
        if (!\is_object($obj)) {
            return $obj;
        }

        /** @var class-string<DataMapperFactory> $mapper */
        $mapper     = $this->mapper::OWNS_ONE[$propertyName]['mapper'];
        $primaryKey = $mapper::getObjectId($obj);

        if (empty($primaryKey)) {
            return $mapper::create(db: $this->db)->execute($obj);
        }

        return $primaryKey;
    }

    /**
     * Create belongs to model
     *
     * @param string $propertyName Name of the belongs to property
     * @param object $obj          Object which contains the belongs to model
     *
     * @return mixed
     *
     * @since 1.0.0
     */
    private function createBelongsTo(string $propertyName, object $obj) : mixed
    {
        if (!\is_object($obj)) {
            return $obj;
        }

        $mapper     = '';
        $primaryKey = 0;

        if (isset($this->mapper::BELONGS_TO[$propertyName]['by'])) {
            // has by (obj is stored as a different model e.g. model = profile but reference/db is account)

            $refClass = new \ReflectionClass($obj);
            $refProp  = $refClass->getProperty($this->mapper::BELONGS_TO[$propertyName]['by']);

            if (!$refProp->isPublic()) {
                $refProp->setAccessible(true);
                $obj = $refProp->getValue($obj);
                $refProp->setAccessible(false);
            } else {
                $obj = $obj->{$this->mapper::BELONGS_TO[$propertyName]['by']};
            }
        }

        /** @var class-string<DataMapperFactory> $mapper */
        $mapper     = $this->mapper::BELONGS_TO[$propertyName]['mapper'];
        $primaryKey = $mapper::getObjectId($obj);

        // @todo: the $mapper::create() might cause a problem if 'by' is set. because we don't want to create this obj but the child obj.
        return empty($primaryKey) ? $mapper::create(db: $this->db)->execute($obj) : $primaryKey;
    }

    /**
     * Create has many models
     *
     * @param \ReflectionClass $refClass Reflection of the object to create
     * @param object           $obj      Object to create
     * @param mixed            $objId    Id of the parent object
     *
     * @return void
     *
     * @since 1.0.0
     */
    private function createHasMany(\ReflectionClass $refClass, object $obj, mixed $objId) : void
    {
        foreach ($this->mapper::HAS_MANY as $propertyName => $rel) {
            if (!isset($this->mapper::HAS_MANY[$propertyName]['mapper'])) {
                throw new InvalidMapperException(); // @codeCoverageIgnore
            }

            $property = $refClass->getProperty($propertyName);
            if (!($isPublic = $property->isPublic())) {
                $property->setAccessible(true);
                $values = $property->getValue($obj);
            } else {
                $values = $obj->{$propertyName};
            }

            /** @var class-string<DataMapperFactory> $mapper */
            $mapper       = $this->mapper::HAS_MANY[$propertyName]['mapper'];
            $internalName = isset($mapper::COLUMNS[$this->mapper::HAS_MANY[$propertyName]['self']])
                ? $mapper::COLUMNS[$this->mapper::HAS_MANY[$propertyName]['self']]['internal']
                : 'ERROR';

            if (\is_object($values)) {
                // conditionals
                $publicProperties = \get_object_vars($values);

                if (!isset($publicProperties[$internalName])) {
                    $relReflectionClass = new \ReflectionClass($values);
                    $relProperty        = $relReflectionClass->getProperty($internalName);

                    $relProperty->setAccessible(true);
                    $relProperty->setValue($values, $objId);
                    $relProperty->setAccessible(false);
                } else {
                    $values->{$internalName} = $objId;
                }

                if (!$isPublic) {
                    $property->setAccessible(false);
                }

                $mapper::create(db: $this->db)->execute($values);
                continue;
            } elseif (!\is_array($values)) {
                if (!$isPublic) {
                    $property->setAccessible(false);
                }

                // @todo: conditionals???
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
                if (!isset($this->mapper::HAS_MANY[$propertyName]['external'])) {
                    $relProperty = $relReflectionClass->getProperty($internalName);

                    if (!($isRelPublic = $relProperty->isPublic())) {
                        $relProperty->setAccessible(true);
                    }

                    // todo maybe consider to just set the column type to object, and then check for that (might be faster)
                    if (isset($mapper::BELONGS_TO[$internalName])
                        || isset($mapper::OWNS_ONE[$internalName])
                    ) {
                        if (!$isRelPublic) {
                            $relProperty->setValue($value,  $this->mapper::createNullModel($objId));
                        } else {
                            $value->{$internalName} =  $this->mapper::createNullModel($objId);
                        }
                    } else {
                        if (!$isRelPublic) {
                            $relProperty->setValue($value, $objId);
                        } else {
                            $value->{$internalName} = $objId;
                        }
                    }

                    if (!$isRelPublic) {
                        $relProperty->setAccessible(false);
                    }
                }

                $objsIds[$key] = $mapper::create(db: $this->db)->execute($value);
            }

            $this->createRelationTable($propertyName, $objsIds, $objId);
        }
    }

    /**
     * Create has many relations if the relation is handled in a relation table
     *
     * @param string $propertyName Property which contains the has many models
     * @param array  $objsIds      Objects which should be related to the parent object
     * @param mixed  $objId        Parent object id
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function createRelationTable(string $propertyName, array $objsIds, mixed $objId) : void
    {
        if (empty($objsIds) || !isset($this->mapper::HAS_MANY[$propertyName]['external'])) {
            return;
        }

        $relQuery = new Builder($this->db);
        $relQuery->into($this->mapper::HAS_MANY[$propertyName]['table'])
            ->insert($this->mapper::HAS_MANY[$propertyName]['external'], $this->mapper::HAS_MANY[$propertyName]['self']);

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
            $sth = $this->db->con->prepare($relQuery->toSql());
            if ($sth !== false) {
                $sth->execute();
            }
        } catch (\Throwable $e) {
            // @codeCoverageIgnoreStart
            \var_dump($e->getMessage());
            \var_dump($relQuery->toSql());
            // @codeCoverageIgnoreEnd
        }
    }
}
