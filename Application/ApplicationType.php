<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Application
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Application;

use phpOMS\Stdlib\Base\Enum;

/**
 * App status enum.
 *
 * @package phpOMS\Application
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
abstract class ApplicationType extends Enum
{
    public const WEB = 1;

    public const CONSOLE = 2;

    public const SOCKET = 3;
}
