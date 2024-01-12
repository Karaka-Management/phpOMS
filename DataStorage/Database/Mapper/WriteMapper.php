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

use phpOMS\DataStorage\Database\Exception\InvalidMapperException;
use phpOMS\DataStorage\Database\Query\Builder;
use phpOMS\DataStorage\Database\Query\QueryType;
use phpOMS\Utils\ArrayUtils;

/**
 * Write mapper (CREATE).
 *
 * @package phpOMS\DataStorage\Database\Mapper
 * @license OMS License 2.0
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
        $refClass = null;

        if ($this->mapper::isNullModel($obj)) {
            $objId = $this->mapper::getObjectId($obj);

            return $objId === 0 ? null : $objId;
        }

        if (!empty($id = $this->mapper::getObjectId($obj)) && $this->mapper::AUTOINCREMENT) {
            $objId = $id;
        } else {
            $objId = $this->createModel($obj, $refClass);
            $this->mapper::setObjectId($obj, $objId, $refClass);
        }

        $this->createHasMany($obj, $objId, $refClass);

        return $objId;
    }

    /**
     * Create model
     *
     * @param object                $obj      Object to create
     * @param null|\ReflectionClass $refClass Reflection of the object to create
     *
     * @return mixed
     *
     * @since 1.0.0
     */
    private function createModel(object $obj, \ReflectionClass &$refClass = null) : mixed
    {
        try {
            $query = new Builder($this->db);
            $query->into($this->mapper::TABLE);

            foreach ($this->mapper::COLUMNS as $column) {
                $propertyName = \stripos($column['internal'], '/') !== false
                    ? \explode('/', $column['internal'])[0]
                    : $column['internal'];

                if (isset($this->mapper::HAS_MANY[$propertyName])
                    || ($column['name'] === $this->mapper::PRIMARYFIELD && $this->mapper::AUTOINCREMENT)
                ) {
                    continue;
                }

                $tValue = null;
                if ($column['private'] ?? false) {
                    if ($refClass === null) {
                        $refClass = new \ReflectionClass($obj);
                    }

                    $property = $refClass->getProperty($propertyName);
                    $tValue   = $property->getValue($obj);
                } else {
                    $tValue = $obj->{$propertyName};
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

            $sth = $this->db->con->prepare($query->toSql());
            $sth->execute();

            $objId = empty($id = $this->mapper::getObjectId($obj)) ? $this->db->con->lastInsertId() : $id;
            \settype($objId, $this->mapper::COLUMNS[$this->mapper::PRIMARYFIELD]['type']);

            return $objId;
        } catch (\Throwable $t) {
            // @codeCoverageIgnoreStart
            \phpOMS\Log\FileLogger::getInstance()->error(
                \phpOMS\Log\FileLogger::MSG_FULL, [
                    'message' => $t->getMessage() . ':' . $query->toSql(),
                    'line'    => __LINE__,
                    'file'    => self::class,
                ]
            );

            return -1;
            // @codeCoverageIgnoreEND
        }
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

            if ($this->mapper::BELONGS_TO[$propertyName]['private'] ?? false) {
                $refClass = new \ReflectionClass($obj);
                $refProp  = $refClass->getProperty($this->mapper::BELONGS_TO[$propertyName]['by']);
                $obj      = $refProp->getValue($obj);
            } else {
                $obj = $obj->{$this->mapper::BELONGS_TO[$propertyName]['by']};
            }
        }

        /** @var class-string<DataMapperFactory> $mapper */
        $mapper     = $this->mapper::BELONGS_TO[$propertyName]['mapper'];
        $primaryKey = $mapper::getObjectId($obj);

        // @todo the $mapper::create() might cause a problem if 'by' is set. because we don't want to create this obj but the child obj.
        return empty($primaryKey) ? $mapper::create(db: $this->db)->execute($obj) : $primaryKey;
    }

    /**
     * Create has many models
     *
     * @param object                $obj      Object to create
     * @param mixed                 $objId    Id of the parent object
     * @param null|\ReflectionClass $refClass Reflection of the object to create
     *
     * @return void
     *
     * @throws InvalidMapperException
     *
     * @since 1.0.0
     */
    private function createHasMany(object $obj, mixed $objId, \ReflectionClass &$refClass = null) : void
    {
        foreach ($this->mapper::HAS_MANY as $propertyName => $rel) {
            if (!isset($this->mapper::HAS_MANY[$propertyName]['mapper'])) {
                throw new InvalidMapperException(); // @codeCoverageIgnore
            }

            $isPrivate = $rel['private'] ?? false;
            $property  = null;
            $values    = null;

            if ($isPrivate) {
                if ($refClass === null) {
                    $refClass = new \ReflectionClass($obj);
                }

                $property = $refClass->getProperty($propertyName);
                $values   = $property->getValue($obj);
            } else {
                $values = $obj->{$propertyName};
            }

            /** @var class-string<DataMapperFactory> $mapper */
            $mapper       = $this->mapper::HAS_MANY[$propertyName]['mapper'];
            $internalName = $mapper::COLUMNS[$this->mapper::HAS_MANY[$propertyName]['self']]['internal'] ?? 'ERROR-BAD-SELF';

            // @todo this or $isRelPrivate is wrong, don't know which one.
            $isInternalPrivate = $mapper::COLUMNS[$this->mapper::HAS_MANY[$propertyName]['self']]['private'] ?? false;

            if (\is_object($values)) {
                // conditionals
                if ($isInternalPrivate) {
                    $relReflectionClass = new \ReflectionClass($values);
                    $relProperty        = $relReflectionClass->getProperty($internalName);

                    $relProperty->setValue($values, $objId);
                } else {
                    $values->{$internalName} = $objId;
                }

                $mapper::create(db: $this->db)->execute($values);
                continue;
            }

            if (!\is_array($values)) {
                // @todo conditionals???
                continue;
            }

            $objsIds            = [];
            $isRelPrivate       = $mapper::COLUMNS[$this->mapper::HAS_MANY[$propertyName]['self']]['private'] ?? false;
            $relReflectionClass = $isRelPrivate && !empty($values) ? new \ReflectionClass(\reset($values)) : null;

            foreach ($values as $key => $value) {
                if (!\is_object($value)) {
                    // Is scalar => already in database
                    $objsIds[$key] = $value;

                    continue;
                }

                $primaryKey = $mapper::getObjectId($value);

                // already in db
                if (!empty($primaryKey)) {
                    $objsIds[$key] = $value;

                    continue;
                }

                // Setting relation value (id) for relation (since the relation is not stored in an extra relation table)
                if (!isset($this->mapper::HAS_MANY[$propertyName]['external'])) {
                    $relProperty = null;
                    if ($isRelPrivate) {
                        $relProperty = $relReflectionClass->getProperty($internalName);
                    }

                    // @todo maybe consider to just set the column type to object, and then check for that (might be faster)
                    if (isset($mapper::BELONGS_TO[$internalName])
                        || isset($mapper::OWNS_ONE[$internalName])
                    ) {
                        if ($isRelPrivate) {
                            $relProperty->setValue($value,  $this->mapper::createNullModel($objId));
                        } else {
                            $value->{$internalName} =  $this->mapper::createNullModel($objId);
                        }
                    } elseif ($isRelPrivate) {
                        $relProperty->setValue($value, $objId);
                    } else {
                        $value->{$internalName} = $objId;
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
        try {
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

            $sth = $this->db->con->prepare($relQuery->toSql());
            if ($sth !== false) {
                $sth->execute();
            }
        } catch (\Throwable $t) {
            // @codeCoverageIgnoreStart
            \phpOMS\Log\FileLogger::getInstance()->error(
                \phpOMS\Log\FileLogger::MSG_FULL, [
                    'message' => $t->getMessage() . ':' . $relQuery->toSql(),
                    'line'    => __LINE__,
                    'file'    => self::class,
                ]
            );
            // @codeCoverageIgnoreEnd
        }
    }
}
