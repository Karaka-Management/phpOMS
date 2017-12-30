<?php
/**
 * Orange Management
 *
 * PHP Version 7.1
 *
 * @package    Framework
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */
declare(strict_types = 1);

namespace phpOMS\Account;

use phpOMS\Stdlib\Base\Enum;

/**
 * Account type enum.
 *
 * @package    Framework
 * @license    OMS License 1.0
 * @link       http://website.orange-management.de
 * @since      1.0.0
 */
abstract class AccountType extends Enum
{
    /* public */ const USER  = 0;
    /* public */ const GROUP = 1;
}
