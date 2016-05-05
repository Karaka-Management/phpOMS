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
namespace phpOMS\Auth;

use phpOMS\Datatypes\Enum;

/**
 * Login return types enum.
 *
 * These are possible answers to authentications.
 *
 * @category   Framework
 * @package    phpOMS\Auth
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
abstract class LoginReturnType extends Enum
{
    const OK = 0; /* Everything is ok and the user got authed */
    const FAILURE = 1; /* Authentication resulted in a unexpected failure */
    const WRONG_PASSWORD = 2; /* Authentication with wrong password */
    const WRONG_USERNAME = 3; /* Authentication with unknown user */
    const WRONG_PERMISSION = 4; /* User doesn't have permission to authenticate */
    const NOT_ACTIVATED = 5; /* The user is not activated yet */
    const WRONG_INPUT_EXCEEDED = 6; /* Too many wrong logins recently */
    const TIMEOUTED = 7; /* User received a timeout and can not log in until a certain date */
    const BANNED = 8; /* User is banned */
    const INACTIVE = 9; /* User is inactive */
}
