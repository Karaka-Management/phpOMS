<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package    phpOMS\Router
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Router;

use phpOMS\Stdlib\Base\Enum;

/**
 * Route verb enum.
 *
 * @package    phpOMS\Router
 * @license    OMS License 1.0
 * @link       https://orange-management.org
 * @since      1.0.0
 */
abstract class RouteVerb extends Enum
{
    public const GET    = 1;
    public const PUT    = 2;
    public const SET    = 4;
    public const DELETE = 8;
    public const ANY    = 16;
}
