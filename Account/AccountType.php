<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   phpOMS\Account
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Account;

use phpOMS\Stdlib\Base\Enum;

/**
 * Account type enum.
 *
 * @package phpOMS\Account
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
abstract class AccountType extends Enum
{
    public const USER = 0;

    public const GROUP = 1;
}
