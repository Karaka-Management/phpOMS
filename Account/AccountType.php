<?php
/**
 * Orange Management
 *
 * PHP Version 8.0
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
 * Account type enum.
 *
 * @package phpOMS\Account
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
abstract class AccountType extends Enum
{
    public const USER = 0;

    public const GROUP = 1;
}
