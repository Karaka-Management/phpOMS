<?php
/**
 * Orange Management
 *
 * PHP Version 7.0
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
namespace phpOMS\Utils\Converter;

/**
 * Measurement converter.
 *
 * @category   Framework
 * @package    phpOMS\Utils\Converter
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
class Measurement
{

    /**
     * Convert temperature.
     *
     * @param float  $value Value to convert
     * @param string $from  Input temperature
     * @param string $to    Output temperature
     *
     * @return float
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function convertTemperature(float $value, string $from, string $to) : float
    {
        switch ($from) {
            case TemperatureType::CELSIUS:
                $value += 273.15;
                break;
            case TemperatureType::FAHRENHEIT:
                $value = ($value - 32) / 1.8 + 273.5;
                break;
            case TemperatureType::KELVIN:
                break;
            case TemperatureType::REAUMUR:
                $value = $value / 0.8 + 273.15;
                break;
            default:
                throw new \InvalidArgumentException('Temperature not supported');
        }

        switch ($to) {
            case TemperatureType::CELSIUS:
                $value -= 273.15;
                break;
            case TemperatureType::FAHRENHEIT:
                $value = (($value - 273.15) * 1.8) + 32;
                break;
            case TemperatureType::KELVIN:
                break;
            case TemperatureType::REAUMUR:
                $value = ($value - 273.15) * 0.8;
                break;
            default:
                throw new \InvalidArgumentException('Temperature not supported');
        }

        return $value;
    }
}
