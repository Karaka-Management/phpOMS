<?php
/**
 * Orange Management
 *
 * PHP Version 8.0
 *
 * @package   phpOMS\DataStorage\Cache\Connection
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\DataStorage\Cache\Connection;

/**
 * Null cache class.
 *
 * @package phpOMS\DataStorage\Cache\Connection
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
final class NullCache extends ConnectionAbstract
{
    /**
     * {@inheritdoc}
     */
    public function connect(array $data = null) : void
    {
    }

    /**
     * {@inheritdoc}
     */
    public function set(int|string $key, mixed $value, int $expire = -1) : void
    {
    }

    /**
     * {@inheritdoc}
     */
    public function add(int|string $key, mixed $value, int $expire = -1) : bool
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function get(int|string $key, int $expire = -1) : mixed
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function delete(int|string $key, int $expire = -1) : bool
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function flush(int $expire = 0) : bool
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function flushAll() : bool
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function replace(int|string $key, mixed $value, int $expire = -1) : bool
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function stats() : array
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getThreshold() : int
    {
        return 0;
    }
}
