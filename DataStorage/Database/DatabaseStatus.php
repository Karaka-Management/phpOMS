<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\DataStorage\Database
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\DataStorage\Database;

use phpOMS\Stdlib\Base\Enum;

/**
 * Database status enum.
 *
 * Possible database connection status
 *
 * @package phpOMS\DataStorage\Database
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
abstract class DatabaseStatus extends Enum
{
    public const OK = 0; /* Database connection successful */

    public const MISSING_DATABASE = 1; /* Couldn't find database */

    public const MISSING_TABLE = 2; /* One of the core tables couldn't be found */

    public const FAILURE = 3; /* Unknown failure */

    public const READONLY = 4; /* Database connection is in readonly (but ok) */

    public const CLOSED = 5; /* Database connection closed */
}
