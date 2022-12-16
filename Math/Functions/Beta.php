<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Math\Functions
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Math\Functions;

/**
 * Beta function
 *
 * @package phpOMS\Math\Functions
 * @license OMS License 1.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class Beta
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
     * Incomplete beta function
     *
     * @param float $x Value
     * @param float $p p
     * @param float $q q
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function incompleteBeta(float $x, float $p, float $q) : float
    {
        return self::regularizedBeta($x, $p, $q) * self::beta($p, $q);
    }

    /**
     * Incomplete beta function
     *
     * @param float $x Value
     * @param float $p p
     * @param float $q q
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function regularizedBeta(float $x, float $p, float $q) : float
    {
        if ($x <= 0.0) {
            return 0.0;
        } elseif ($x >= 1.0) {
            return 1.0;
        } elseif ($p <= 0.0 || $q <= 0.0 || $p + $q > 10000000000.0) {
            return 0.0;
        }

        $bGamma = \exp(-self::logBeta($p, $q) + $p * \log($x) + $q * \log(1.0 - $x));

        // this uses the symmetry of the beta function
        return ($x < ($p + 1.0) / ($p + $q + 2.0)
            ? $bGamma * self::betaFraction($x, $p, $q) / $p
            : 1.0 - $bGamma * self::betaFraction(1.0 - $x, $q, $p) / $q);
    }

    /**
     * Fraction of the beta function
     *
     * @param float $x Value
     * @param float $p p
     * @param float $q q
     *
     * @see JSci
     * @author Jaco van Kooten
     * @license LGPL 2.1
     * @since 1.0.0
     */
    private static function betaFraction(float $x, float $p, float $q) : float
    {
        $c      = 1.0;
        $pqSum  = $p + $q;
        $pPlus  = $p + 1.0;
        $pMinus = $p - 1.0;
        $h      = 1.0 - $pqSum * $x / $pPlus;

        if (\abs($h) < 1.18e-37) {
            $h = 1.18e-37;
        }

        $h     = 1.0 / $h;
        $frac  = $h;
        $m     = 1;
        $delta = 0.0;

        do {
            $m2 = 2 * $m;
            $d  = $m * ($q - $m) * $x / (($pMinus + $m2) * ($p + $m2));
            $h  = 1.0 + $d * $h;
            if (\abs($h) < 1.18e-37) {
                $h = 1.18e-37;
            }

            $h = 1.0 / $h;
            $c = 1.0 + $d / $c;
            if (\abs($c) < 1.18e-37) {
                $c = 1.18e-37;
            }

            $frac *= $h * $c;
            $d     = -($p + $m) * ($pqSum + $m) * $x / (($p + $m2) * ($pPlus + $m2));
            $h     = 1.0 + $d * $h;
            if (\abs($h) < 1.18e-37) {
                $h = 1.18e-37;
            }

            $h = 1.0 / $h;
            $c = 1.0 + $d / $c;
            if (\abs($c) < 1.18e-37) {
                $c = 1.18e-37;
            }

            $delta = $h * $c;
            $frac *= $delta;
            ++$m;
        } while ($m < 1000000 && \abs($delta - 1.0) > 8.88e-16);

        return $frac;
    }

    /**
     * Log of Beta
     *
     * @param float $p p
     * @param float $q q
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function logBeta(float $p, float $q) : float
    {
        return $p <= 0.0 || $q <= 0.0 || $p + $q > 10000000000.0
            ? 0.0
            : Gamma::logGamma($p) + Gamma::logGamma($q) - Gamma::logGamma($p + $q);
    }

    /**
     * Beta
     *
     * @param float $p p
     * @param float $q q
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function beta(float $p, float $q) : float
    {
        return \exp(self::logBeta($p, $q));
    }
}
