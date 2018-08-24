<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    phpOMS\Business\Finance\Forecasting
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */
declare(strict_types=1);

namespace phpOMS\Business\Finance\Forecasting;

use phpOMS\Stdlib\Base\Enum;

/**
 * Smoothing enum.
 *
 * @package    phpOMS\Business\Finance\Forecasting
 * @license    OMS License 1.0
 * @link       http://website.orange-management.de
 * @since      1.0.0
 */
abstract class SmoothingType extends Enum
{
    public const CENTERED_MOVING_AVERAGE = 1;
}
