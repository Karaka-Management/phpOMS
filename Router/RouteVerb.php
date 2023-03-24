<?php
/**
 * Karaka
 *
 * PHP Version 8.1
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
 * Route verb enum.
 *
 * @package phpOMS\Router
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
abstract class RouteVerb extends Enum
{
    public const GET = 1;

    public const PUT = 2;

    public const SET = 4;

    public const DELETE = 8;

    public const ANY = 16;
}
