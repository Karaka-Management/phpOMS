<?php
/**
 * Orange Management
 *
 * PHP Version 7.1
 *
 * @category   TBD
 * @package    TBD
 * @author     OMS Development Team <dev@oms.com>
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://orange-management.com
 */
declare(strict_types=1);

namespace phpOMS\Account;

use phpOMS\Datatypes\Enum;

/**
 * Account status enum.
 *
 * @category   Framework
 * @package    phpOMS\Account
 * @author     OMS Development Team <dev@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
abstract class AccountStatus extends Enum
{
    /* public */ const ACTIVE = 1;
    /* public */ const INACTIVE = 2;
    /* public */ const TIMEOUT = 3;
    /* public */ const BANNED = 4;
}
