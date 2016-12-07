<?php
/**
 * Orange Management
 *
 * PHP Version 7.0
 *
 * @category   TBD
 * @package    TBD
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @copyright  2013 Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://orange-management.com
 */
namespace phpOMS\Account;

use phpOMS\Datatypes\Enum;

/**
 * Account status enum.
 *
 * @category   Framework
 * @package    phpOMS\Account
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
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
