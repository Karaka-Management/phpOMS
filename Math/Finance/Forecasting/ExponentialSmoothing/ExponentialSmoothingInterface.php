<?php
/**
 * Orange Management
 *
 * PHP Version 7.1
 *
 * @category   TBD
 * @package    TBD
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @copyright  2013 Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://orange-management.com
 */
 namespace phpOMS\Math\Finance\Forecasting\ExponentialSmoothing;

use phpOMS\Math\Finance\Forecasting\SmoothingType;
use phpOMS\Math\Statistic\Average;
use phpOMS\Math\Statistic\Forecast\Error;

interface ExponentialSmoothingInterface
{
    public function setCycle(int $cycle) /* : void */;

    public function getRMSE() : float;

    public function getMSE() : float;

    public function getMAE() : float;

    public function getSSE() : float;

    public function getErrors() : array;

    public function getForecast(int $future = 1, int $smoothing = SmoothingType::CENTERED_MOVING_AVERAGE) : array;
}
