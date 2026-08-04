<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   phpOMS\DataStorage\Database\Connection
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.2
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\DataStorage\Database\Connection;

/**
 * Database handler.
 *
 * @package phpOMS\DataStorage\Database\Connection
 * @license OMS License 2.2
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class NullConnection extends ConnectionAbstract
{
    /**
     * {@inheritdoc}
     */
    public function connect(?array $dbdata = null) : void
    {
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize() : mixed
    {
        return ['id' => $this->id];
    }

    /**
     * {@inheritdoc}
     */
    public function beginTransaction() : void
    {
    }

    /**
     * {@inheritdoc}
     */
    public function rollBack() : void
    {
    }

    /**
     * {@inheritdoc}
     */
    public function commit() : void
    {
    }
}
