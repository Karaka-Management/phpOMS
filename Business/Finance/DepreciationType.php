<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Business\Finance
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
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
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
abstract class DepreciationType extends Enum
{
    public const STAIGHT_LINE = 1;

    public const DECLINING_BALANCE = 2;

    public const SUM_OF_THE_YEAR = 3;

    public const UNITS_OF_PRODUCTION = 4;
}
