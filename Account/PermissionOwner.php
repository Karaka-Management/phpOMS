<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   phpOMS\Account
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Account;

use phpOMS\Stdlib\Base\Enum;

/**
 * Permision type/owner enum.
 *
 * A permission can be long to a group or an account.
 *
 * @package phpOMS\Account
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
abstract class PermissionOwner extends Enum
{
    public const GROUP   = 1;
    public const ACCOUNT = 2;
}
