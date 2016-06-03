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
     * @todo: implement more
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function convertTemperature(float $value, string $from = TemperatureType::FAHRENHEIT, string $to = TemperatureType::CELSIUS) : float
    {
        // to celving
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

    /**
     * Convert weight.
     *
     * @param float  $value Value to convert
     * @param string $from  Input weight
     * @param string $to    Output weight
     *
     * @return float
     *
     * @todo: implement more
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function convertWeight(float $value, string $from = WeightType::GRAM, string $to = WeightType::KILOGRAM) : float
    {
        // to gram
        switch ($from) {
            case WeightType::MILLIGRAM:
                $value /= 1000;
                break;
            case WeightType::GRAM:
                break;
            case WeightType::KILOGRAM:
                $value *= 1000;
                break;
            case WeightType::TONS:
                $value *= 1000000;
                break;
            case WeightType::STONES:
                $value /= 0.15747 * 1000;
                break;
            case WeightType::OUNCES:
                $value /= 0.035274;
                break;
            default:
                throw new \InvalidArgumentException('Weight not supported');
        }

        switch ($to) {
            case WeightType::MILLIGRAM:
                $value *= 1000;
                break;
            case WeightType::GRAM:
                break;
            case WeightType::KILOGRAM:
                $value /= 1000;
                break;
            case WeightType::TONS:
                $value /= 1000000;
                break;
            case WeightType::STONES:
                $value *= 0.15747 * 1000;
                break;
            case WeightType::OUNCES:
                $value *= 0.035274;
                break;
            default:
                throw new \InvalidArgumentException('Weight not supported');
        }

        return $value;
    }

    /**
     * Convert length.
     *
     * @param float  $value Value to convert
     * @param string $from  Input weight
     * @param string $to    Output weight
     *
     * @return float
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function convertLength(float $value, string $from = WeightType::GRAM, string $to = WeightType::KILOGRAM) : float
    {
        return $value;
    }

    /**
     * Convert area.
     *
     * @param float  $value Value to convert
     * @param string $from  Input weight
     * @param string $to    Output weight
     *
     * @return float
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function convertArea(float $value, string $from = WeightType::GRAM, string $to = WeightType::KILOGRAM) : float
    {
        return $value;
    }

    /**
     * Convert volume.
     *
     * @param float  $value Value to convert
     * @param string $from  Input weight
     * @param string $to    Output weight
     *
     * @return float
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function convertVolume(float $value, string $from = WeightType::GRAM, string $to = WeightType::KILOGRAM) : float
    {
        return $value;
    }

    /**
     * Convert speed.
     *
     * @param float  $value Value to convert
     * @param string $from  Input weight
     * @param string $to    Output weight
     *
     * @return float
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function convertSpeed(float $value, string $from = WeightType::GRAM, string $to = WeightType::KILOGRAM) : float
    {
        return $value;
    }

    /**
     * Convert speed.
     *
     * @param float  $value Value to convert
     * @param string $from  Input weight
     * @param string $to    Output weight
     *
     * @return float
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function convertTime(float $value, string $from = WeightType::GRAM, string $to = WeightType::KILOGRAM) : float
    {
        return $value;
    }

    /**
     * Convert speed.
     *
     * @param float  $value Value to convert
     * @param string $from  Input weight
     * @param string $to    Output weight
     *
     * @return float
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function convertAngle(float $value, string $from = WeightType::GRAM, string $to = WeightType::KILOGRAM) : float
    {
        return $value;
    }

    /**
     * Convert speed.
     *
     * @param float  $value Value to convert
     * @param string $from  Input weight
     * @param string $to    Output weight
     *
     * @return float
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function convertPressure(float $value, string $from = WeightType::GRAM, string $to = WeightType::KILOGRAM) : float
    {
        return $value;
    }

    /**
     * Convert speed.
     *
     * @param float  $value Value to convert
     * @param string $from  Input weight
     * @param string $to    Output weight
     *
     * @return float
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function convertEnergie(float $value, string $from = WeightType::GRAM, string $to = WeightType::KILOGRAM) : float
    {
        return $value;
    }
}
