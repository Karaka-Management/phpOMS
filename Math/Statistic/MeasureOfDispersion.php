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

namespace phpOMS\Math\Statistic;

/**
 * Measure of dispersion.
 *
 * @category   Framework
 * @package    phpOMS\DataStorage\Database
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
class MeasureOfDispersion
{

    /**
     * Get range.
     *
     * Example: ([4, 5, 9, 1, 3])
     *
     * @param array $values Values
     *
     * @return float
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function range(array $values) : \float
    {
        sort($values);
        $end   = end($values);
        $start = reset($values);

        return $start - $end;
    }

    /**
     * Calculage empirical variance.
     *
     * Example: ([4, 5, 9, 1, 3])
     *
     * @param array $values Values
     *
     * @return float
     *
     * @throws
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function empiricalVariance(array $values) : \float
    {
        $count = count($values);

        if ($count === 0) {
            throw new \Exception('Division zero');
        }

        $mean = Average::arithmeticMean($values);
        $sum  = 0;

        foreach ($values as $value) {
            $sum += $value - $mean;
        }

        return $sum / $count;
    }

    /**
     * Calculage sample variance.
     *
     * Example: ([4, 5, 9, 1, 3])
     *
     * @param array $values Values
     *
     * @return float
     *
     * @throws
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function sampleVariance(array $values) : \float
    {
        $count = count($values);

        if ($count < 2) {
            throw new \Exception('Division zero');
        }

        return $count * self::empiricalVariance($values) / ($count - 1);
    }

    /**
     * Calculage standard deviation.
     *
     * Example: ([4, 5, 9, 1, 3])
     *
     * @param array $values Values
     *
     * @return float
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function standardDeviation(array $values) : \float
    {
        return sqrt(self::sampleVariance($values));
    }

    /**
     * Calculage empirical variation coefficient.
     *
     * Example: ([4, 5, 9, 1, 3])
     *
     * @param array $values Values
     *
     * @return float
     *
     * @throws
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function empiricalVariationcoefficient(array $values) : \float
    {
        $mean = Average::arithmeticMean($values);

        if ($mean === 0) {
            throw new \Exception('Division zero');
        }

        return self::standardDeviation($values) / $mean;
    }

    /**
     * Calculage empirical covariance.
     *
     * Example: ([4, 5, 9, 1, 3], [4, 5, 9, 1, 3])
     *
     * @param array $x Values
     * @param array $y Values
     *
     * @return float
     *
     * @throws
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function empiricalCovariance(array $x, array $y) : \float
    {
        $count = count($x);

        if ($count < 2) {
            throw new \Exception('Division zero');
        }

        if ($count !== count($y)) {
            throw new \Exception('Dimensions');
        }

        $xMean = Average::arithmeticMean($x);
        $yMean = Average::arithmeticMean($y);

        $sum = 0.0;

        for ($i = 0; $i < $count; $i++) {
            $sum += ($x[$i] - $xMean) * ($y[$i] - $yMean);
        }

        return $sum / ($count - 1);
    }

    /**
     * Calculage bravais person correlation coefficient.
     *
     * Example: ([4, 5, 9, 1, 3], [4, 5, 9, 1, 3])
     *
     * @param array $x Values
     * @param array $y Values
     *
     * @return float
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function bravaisPersonCorrelationcoefficient(array $x, array $y) : \float
    {
        return self::empiricalCovariance($x, $y) / sqrt(self::empiricalCovariance($x, $x) * self::empiricalCovariance($y, $y));
    }
}
