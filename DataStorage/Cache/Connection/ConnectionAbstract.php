<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   phpOMS\DataStorage\Cache\Connection
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\DataStorage\Cache\Connection;

use phpOMS\DataStorage\Cache\CacheStatus;
use phpOMS\DataStorage\Cache\CacheType;

/**
 * Cache handler.
 *
 * Handles the cache connection.
 * Implementing wrapper functions for multiple caches is planned (far away).
 *
 * @package phpOMS\DataStorage\Cache\Connection
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
abstract class ConnectionAbstract implements ConnectionInterface
{

    /**
     * Connection object.
     *
     * This can be used externally to define queries and execute them.
     *
     * @var   mixed
     * @since 1.0.0
     */
    protected $con = null;

    /**
     * Database prefix.
     *
     * The database prefix name for unique table names
     *
     * @var   string
     * @since 1.0.0
     */
    public string $prefix = '';

    /**
     * Database data.
     *
     * @var   null|string[]
     * @since 1.0.0
     */
    protected ?array $dbdata = null;

    /**
     * Database type.
     *
     * @var   string
     * @since 1.0.0
     */
    protected string $type = CacheType::UNDEFINED;

    /**
     * Database status.
     *
     * @var   int
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
        return (int) $this->dbdata['port'] ?? 0;
    }

    /**
     * Get table prefix.
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function getPrefix() : string
    {
        return $this->prefix;
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
     * Parse values for cache storage
     *
     * @param mixed $value Value to parse
     *
     * @return mixed
     *
     * @since 1.0.0
     */
    protected function parseValue($value)
    {
        if (\is_array($value)) {
            return \json_encode($value);
        }

        return $value;
    }
}
