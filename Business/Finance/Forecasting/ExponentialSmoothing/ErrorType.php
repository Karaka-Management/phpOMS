<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    Framework
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */
declare(strict_types=1);

namespace phpOMS\Business\Finance\Forecasting\ExponentialSmoothing;

use phpOMS\Stdlib\Base\Enum;

/**
 * Smoothing enum.
 *
 * @package    Framework
 * @license    OMS License 1.0
 * @link       http://website.orange-management.de
 * @since      1.0.0
 */
abstract class ErrorType extends Enum
{
    public const ALL            = 0;
    public const NONE           = 1;
    public const ADDITIVE       = 2;
    public const MULTIPLICATIVE = 4;
}