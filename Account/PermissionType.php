<?php
/**
 * Karaka
 *
 * PHP Version 8.0
 *
 * @package   phpOMS\Account
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://karaka.app
 */
declare(strict_types=1);

namespace phpOMS\Account;

use phpOMS\Stdlib\Base\Enum;

/**
 * Permission type enum.
 *
 * @package phpOMS\Account
 * @license OMS License 1.0
 * @link    https://karaka.app
 * @since   1.0.0
 */
abstract class PermissionType extends Enum
{
    public const NONE = 1;  // No permission

    public const READ = 2;  // Is able to read models/data

    public const CREATE = 4;  // Is able to create models/data

    public const MODIFY = 8;  // Is able to modify models/data

    public const DELETE = 16; // Is able to delete models/data

    public const PERMISSION = 32; // Is able to change permissions
}
