<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   phpOMS\System
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.2
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\System;

use phpOMS\Stdlib\Base\Enum;

/**
 * Operating system type enum.
 *
 * @package phpOMS\System
 * @license OMS License 2.2
 * @link    https://jingga.app
 * @since   1.0.0
 */
abstract class SystemType extends Enum
{
    public const UNKNOWN = 1;

    public const WIN = 2;

    public const LINUX = 3;

    public const OSX = 4;
}
