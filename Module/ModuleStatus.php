<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   phpOMS\Module
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);
namespace phpOMS\Module;

use phpOMS\Stdlib\Base\Enum;

/**
 * Module status enum.
 *
 * @package phpOMS\Module
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
abstract class ModuleStatus extends Enum
{
    public const ACTIVE    = 1;

    public const INACTIVE  = 2;

    public const AVAILABLE = 3;
}
