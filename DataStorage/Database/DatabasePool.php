<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   phpOMS\DataStorage\Database
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\DataStorage\Database;

use phpOMS\DataStorage\Database\Connection\ConnectionAbstract;
use phpOMS\DataStorage\Database\Connection\ConnectionFactory;
use phpOMS\DataStorage\Database\Connection\NullConnection;
use phpOMS\DataStorage\DataStorageConnectionInterface;
use phpOMS\DataStorage\DataStoragePoolInterface;

/**
 * Database pool handler.
 *
 * @package phpOMS\DataStorage\Database
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class DatabasePool implements DataStoragePoolInterface
{
    /**
     * Databases.
     *
     * @var ConnectionAbstract[]
     * @since 1.0.0
     */
    private array $pool = [];

    /**
     * Add database.
     *
     * @param string                         $key Database key
     * @param DataStorageConnectionInterface $db  Database
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function add(string $key, DataStorageConnectionInterface $db) : bool
    {
        if (isset($this->pool[$key])) {
            return false;
        }

        $this->pool[$key] = $db;

        return true;
    }

    /**
     * Get database.
     *
     * @param string $key Database key
     *
     * @return ConnectionAbstract
     *
     * @since 1.0.0
     */
    public function get(string $key = '') : ConnectionAbstract
    {
        if ((!empty($key) && !isset($this->pool[$key])) || empty($this->pool)) {
            return new NullConnection();
        }

        $con = empty($key) ? \reset($this->pool) : $this->pool[$key];
        if ($con->getStatus() !== DatabaseStatus::OK) {
            $con->connect();
        }

        return $con;
    }

    /**
     * Remove database.
     *
     * @param string $key Database key
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function remove(string $key) : bool
    {
        if (!isset($this->pool[$key])) {
            return false;
        }

        unset($this->pool[$key]);

        return true;
    }

    /**
     * Create database.
     *
     * @param string                                                                                                                    $key    Database key
     * @param array{db:string, database:string}|array{db:string, host:string, port:int, login:string, password:string, database:string} $config Database config data
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function create(string $key, array $config) : bool
    {
        if (isset($this->pool[$key])) {
            return false;
        }

        $this->pool[$key] = ConnectionFactory::create($config);

        return true;
    }
}
