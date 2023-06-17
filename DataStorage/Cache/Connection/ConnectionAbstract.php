<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\DataStorage\Cache\Connection
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\DataStorage\Cache\Connection;

use phpOMS\Contract\SerializableInterface;
use phpOMS\DataStorage\Cache\CacheStatus;
use phpOMS\DataStorage\Cache\CacheType;

/**
 * Cache handler.
 *
 * Handles the cache connection.
 * Implementing wrapper functions for multiple caches is planned (far away).
 *
 * @package phpOMS\DataStorage\Cache\Connection
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
abstract class ConnectionAbstract implements ConnectionInterface
{
    /**
     * Connection object.
     *
     * This can be used externally to define queries and execute them.
     *
     * @var mixed
     * @since 1.0.0
     */
    protected $con = null;

    /**
     * Database data.
     *
     * @var null|string[]
     * @since 1.0.0
     */
    protected ?array $dbdata = null;

    /**
     * Database type.
     *
     * @var string
     * @since 1.0.0
     */
    protected string $type = CacheType::UNDEFINED;

    /**
     * Database status.
     *
     * @var int
     * @since 1.0.0
     */
    protected int $status = CacheStatus::CLOSED;

    /**
     * {@inheritdoc}
     */
    public function getType() : string
    {
        return $this->type;
    }

    /**
     * {@inheritdoc}
     */
    public function getStatus() : int
    {
        return $this->status;
    }

    /**
     * Get database name.
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function getCache() : string
    {
        return (string) ($this->dbdata['db'] ?? '');
    }

    /**
     * Get database host.
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function getHost() : string
    {
        return $this->dbdata['host'] ?? '';
    }

    /**
     * Get database port.
     *
     * @return int
     *
     * @since 1.0.0
     */
    public function getPort() : int
    {
        return (int) ($this->dbdata['port'] ?? 0);
    }

    /**
     * Object destructor.
     *
     * Sets the database connection to null
     *
     * @since 1.0.0
     */
    public function __destruct()
    {
        $this->close();
    }

    /**
     * Closes the chache.
     *
     * @since 1.0.0
     */
    public function close() : void
    {
        $this->con    = null;
        $this->status = CacheStatus::CLOSED;
    }

    /**
     * Analyze caching data type.
     *
     * @param mixed $value Data to cache
     *
     * @return int Returns the cache type for a value
     *
     * @throws \InvalidArgumentException This exception is thrown if an unsupported datatype is used
     *
     * @since 1.0.0
     */
    protected function dataType(mixed $value) : int
    {
        if (\is_int($value)) {
            return CacheValueType::_INT;
        } elseif (\is_float($value)) {
            return CacheValueType::_FLOAT;
        } elseif (\is_string($value)) {
            return CacheValueType::_STRING;
        } elseif (\is_bool($value)) {
            return CacheValueType::_BOOL;
        } elseif (\is_array($value)) {
            return CacheValueType::_ARRAY;
        } elseif ($value === null) {
            return CacheValueType::_NULL;
        } elseif ($value instanceof SerializableInterface) {
            return CacheValueType::_SERIALIZABLE;
        } elseif ($value instanceof \JsonSerializable) {
            return CacheValueType::_JSONSERIALIZABLE;
        }

        throw new \InvalidArgumentException('Invalid value type.');
    }
}
