<?php
/**
 * Orange Management
 *
 * PHP Version 7.1
 *
 * @package    phpOMS\DataStorage\Cache\Connection
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */
declare(strict_types=1);

namespace phpOMS\DataStorage\Cache\Connection;

use phpOMS\DataStorage\Cache\CacheStatus;

/**
 * Cache handler.
 *
 * Handles the cache connection.
 * Implementing wrapper functions for multiple caches is planned (far away).
 *
 * @package    phpOMS\DataStorage\Cache\Connection
 * @license    OMS License 1.0
 * @link       http://website.orange-management.de
 * @since      1.0.0
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
    private $con = null;

    /**
     * Database prefix.
     *
     * The database prefix name for unique table names
     *
     * @var string
     * @since 1.0.0
     */
    public $prefix = '';

    /**
     * Database data.
     *
     * @var string[]
     * @since 1.0.0
     */
    protected $dbdata = null;

    /**
     * Database type.
     *
     * @var string
     * @since 1.0.0
     */
    protected $type = CacheStatus::UNDEFINED;

    /**
     * Database status.
     *
     * @var int
     * @since 1.0.0
     */
    protected $status = CacheStatus::INACTIVE;

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
     * @since  1.0.0
     */
    public function getCache() : string
    {
        return $this->dbdata['database'] ?? '';
    }

    /**
     * Get database host.
     *
     * @return string
     *
     * @since  1.0.0
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
     * @since  1.0.0
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
     * @since  1.0.0
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
     * @since  1.0.0
     */
    public function __destruct()
    {
        $this->close();
    }

    /**
     * {@inheritdoc}
     */
    public function close() : void
    {
        $this->con    = null;
        $this->status = CacheStatus::INACTIVE;
    }
}
