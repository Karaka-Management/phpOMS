<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   phpOMS\Math\Functions
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Math\Functions;

/**
 * Gamma function
 *
 * @package phpOMS\Math\Functions
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
final class Gamma
{
    /**
     * Constructor.
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    private function __construct()
    {
    }

    /**
     * Gamma function
     *
     * @param int|float $z Value
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function gamma($z) : float
    {
        return \exp(self::logGamma($z));
    }

    /**
     * approximation values.
     *
     * @var float[]
     * @since 1.0.0
     */
    private const LANCZOSAPPROXIMATION = [
        0.99999999999980993, 676.5203681218851, -1259.1392167224028, 771.32342877765313, -176.61502916214059,
        12.507343278686905, -0.13857109526572012, 9.9843695780195716e-6, 1.5056327351493116e-7,
    ];

    /**
     * Calculate gamma with Lanczos approximation
     *
     * @param int|float $z Value
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function lanczosApproximationReal($z) : float
    {
        if ($z < 0.5) {
            return \M_PI / (\sin(\M_PI * $z) * self::lanczosApproximationReal(1 - $z));
        }

        --$z;
        $a = self::LANCZOSAPPROXIMATION[0];
        $t = $z + 7.5;

        for ($i = 1; $i < 9; ++$i) {
            $a += self::LANCZOSAPPROXIMATION[$i] / ($z + $i);
        }

        return \sqrt(2 * \M_PI) * \pow($t, $z + 0.5) * \exp(-$t) * $a;
    }

    /**
     * Calculate gamma with Stirling approximation
     *
     * @param int|float $x Value
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function stirlingApproximation($x) : float
    {
        return \sqrt(2.0 * \M_PI / $x) * \pow($x / \M_E, $x);
    }

    /**
     * Calculate gamma with Spouge approximation
     *
     * @param int|float $z Value
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function spougeApproximation($z) : float
    {
        $k1_fact = 1.0;
        $c       = [\sqrt(2.0 * \M_PI)];

        for ($k = 1; $k < 12; ++$k) {
            $c[$k]    = \exp(12 - $k) * \pow(12 - $k, $k - 0.5) / $k1_fact;
            $k1_fact *= -$k;
        }

        $accm = $c[0];
        for ($k = 1; $k < 12; ++$k) {
            $accm += $c[$k] / ($z + $k);
        }

        $accm *= \exp(-$z - 12) * \pow($z + 12, $z + 0.5);

        return $accm / $z;
    }

    /**
     * Log of the gamma function
     *
     * @param int|float $z Value
     *
     * @return float
     *
     * @see Book: Numerical Recipes - 9780521406895
     *
     * @since 1.0.0
     */
    public static function logGamma($z) : float
    {
        static $approx = [
            76.18009172947146,-86.50532032941677,
            24.01409824083091,-1.231739572450155,
            0.1208650973866179e-2,-0.5395239384953e-5,
        ];

        $y = $z;

        $temp = $z + 5.5 - ($z + 0.5) * \log($z + 5.5);
        $sum  = 1.000000000190015;

        for ($i = 0; $i < 6; ++$i) {
            $sum += $approx[$i] / ++$y;
        }

        return -$temp + \log(\sqrt(2 * \M_PI) * $sum / $z);
    }

    /**
     * Calculate gamma function value.
     *
     * Example: (7)
     *
     * @param int $k Variable
     *
     * @return int
     *
     * @since 1.0.0
     */
    public static function getGammaInteger(int $k) : int
    {
        return Functions::fact($k - 1);
    }

    /**
     * First or lower incomplete gamma function
     *
     * @param float $a a
     * @param float $x Value
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function incompleteGammaFirst(float $a, float $x) : float
    {
        return self::regularizedGamma($a, $x) * \exp(self::logGamma($a));
    }

    /**
     * Second or upper incomplete gamma function
     *
     * @param float $a a
     * @param float $x Value
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function incompleteGammaSecond(float $a, float $x) : float
    {
        return \exp(self::logGamma($a)) - self::regularizedGamma($a, $x) * \exp(self::logGamma($a));
    }

    /**
     * Incomplete gamma function
     *
     * @param float $a a
     * @param float $x Value
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function regularizedGamma(float $a, float $x) : float
    {
        if ($x <= 0.0 || $a <= 0.0 || $a > 10000000000.0) {
            return 0.0;
        } elseif ($x < $a + 1.0) {
            return self::gammaSeriesExpansion($a, $x);
        }

        return 1.0 - self::gammaFraction($a, $x);
    }

    /**
     * Gamma series expansion
     *
     * @param float $a a
     * @param float $x Value
     *
     * @return float
     *
     * @see JSci
     * @author Jaco van Kooten
     * @license LGPL 2.1
     * @since 1.0.0
     */
    private static function gammaSeriesExpansion(float $a, float $x) : float
    {
        $ap  = $a;
        $del = 1.0 / $a;
        $sum = $del;

        for ($i = 1; $i < 150; ++$i) {
            ++$ap;

            $del *= $x / $ap;
            $sum += $del;

            if ($del < $sum * 2.22e-16) {
                return $sum * \exp(-$x + $a * \log($x) - self::logGamma($a));
            }
        }

        return 0.0;
    }

    /**
     * Gamma fraction
     *
     * @param float $a a
     * @param float $x Value
     *
     * @return float
     *
     * @see JSci
     * @author Jaco van Kooten
     * @license LGPL 2.1
     * @since 1.0.0
     */
    private static function gammaFraction(float $a, float $x) : float
    {
        $b   = $x + 1.0 - $a;
        $c   = 1.0 / 1.18e-37;
        $d   = 1.0 / $b;
        $h   = $d;
        $del = 0.0;

        for ($i = 1; $i < 150 && \abs($del - 1.0) > 2.22e-16; ++$i) {
            $an = - $i * ($i - $a);
            $b += 2.0;
            $d  = $an * $d + $b;
            $c  = $b + $an / $c;

            if (\abs($c) < 1.18e-37) {
                $c = 1.18e-37;
            }

            if (\abs($d) < 1.18e-37) {
                $d = 1.18e-37;
            }

            $d   = 1.0 / $d;
            $del = $d * $c;
            $h  *= $del;
        }

        return \exp(-$x + $a * \log($x) - self::logGamma($a)) * $h;
    }
}
