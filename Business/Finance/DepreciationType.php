<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   phpOMS\Business\Finance
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.2
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Business\Finance;

use phpOMS\Stdlib\Base\Enum;

/**
 * Depreciation type enum.
 *
 * @package phpOMS\Business\Finance
 * @license OMS License 2.2
 * @link    https://jingga.app
 * @since   1.0.0
 */
abstract class DepreciationType extends Enum
{
    public const NONE = 1;

    public const STRAIGHT_LINE = 2;

    public const DECLINING_BALANCE = 3;

    public const SUM_OF_THE_YEAR = 4;

    public const UNITS_OF_PRODUCTION = 5;

    public const MANUAL = 6;
}
