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
 * Null implementation of PDO.
 *
 * @package phpOMS\DataStorage\Database\Connection
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
final class NullPDO extends \PDO
{
    /**
     * Constructor
     *
     * @since 1.0.0
     */
    public function __construct()
    {

    }
}
