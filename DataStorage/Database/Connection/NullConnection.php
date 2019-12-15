<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   phpOMS\DataStorage\Database\Connection
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\DataStorage\Database\Connection;

/**
 * Database handler.
 *
 * @package phpOMS\DataStorage\Database\Connection
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
final class NullConnection extends ConnectionAbstract
{
    /**
     * {@inheritdoc}
     */
    public function connect(array $dbdata = null) : void
    {
    }
}
