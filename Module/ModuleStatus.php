<?php
/**
 * Karaka
 *
 * PHP Version 8.0
 *
 * @package   phpOMS\Module
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://karaka.app
 */
declare(strict_types=1);
namespace phpOMS\Module;

use phpOMS\Stdlib\Base\Enum;

/**
 * Module status enum.
 *
 * @package phpOMS\Module
 * @license OMS License 1.0
 * @link    https://karaka.app
 * @since   1.0.0
 */
abstract class ModuleStatus extends Enum
{
    public const ACTIVE = 1;

    public const INACTIVE = 2;

    public const AVAILABLE = 3;
}
