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
abstract class ApplicationStatus extends Enum
{
    public const NORMAL = 1;

    public const READ_ONLY = 2;

    public const DISABLED = 3;
}
