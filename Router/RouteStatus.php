<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   phpOMS\Router
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Router;

use phpOMS\Stdlib\Base\Enum;

/**
 * Route Status
 *
 * @package phpOMS\Router
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
abstract class RouteStatus extends Enum
{
    public const INVALID_CSRF = -1;

    public const NOT_LOGGED_IN = -2;

    public const INVALID_PERMISSIONS = -3;

    public const INVALID_DATA = -4;
}
