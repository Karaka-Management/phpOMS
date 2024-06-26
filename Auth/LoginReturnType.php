<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   phpOMS\Auth
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Auth;

use phpOMS\Stdlib\Base\Enum;

/**
 * Login return types enum.
 *
 * These are possible answers to authentications.
 *
 * @package phpOMS\Auth
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
abstract class LoginReturnType extends Enum
{
    public const OK = 0; /* Everything is ok and the user got authenticated */

    public const FAILURE = -1; /* Authentication resulted in a unexpected failure */

    public const WRONG_PASSWORD = -2; /* Authentication with wrong password */

    public const WRONG_USERNAME = -3; /* Authentication with unknown user */

    public const WRONG_PERMISSION = -4; /* User doesn't have permission to authenticate */

    public const NOT_ACTIVATED = -5; /* The user is not activated yet */

    public const WRONG_INPUT_EXCEEDED = -6; /* Too many wrong logins recently */

    public const TIMEOUTED = -7; /* User received a timeout and can not log in until a certain date */

    public const BANNED = -8; /* User is banned */

    public const INACTIVE = -9; /* User is inactive */

    public const EMPTY_PASSWORD = -10; /* Empty password in database */
}
