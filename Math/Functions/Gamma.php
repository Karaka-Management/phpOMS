<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    phpOMS\Math\Functions
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */
declare(strict_types=1);

namespace phpOMS\Math\Functions;

/**
 * Gamma function
 *
 * @package    phpOMS\Math\Functions
 * @license    OMS License 1.0
 * @link       http://website.orange-management.de
 * @since      1.0.0
 */
final class Gamma
{
    /**
     * Constructor.
     *
     * @since  1.0.0
     * @codeCoverageIgnore
     */
    private function __construct()
    {

    }

    /**
     * approximation values.
     *
     * @var float[]
     * @since 1.0.0
     */
    private const LANCZOSAPPROXIMATION = [
        0.99999999999980993, 676.5203681218851, -1259.1392167224028, 771.32342877765313, -176.61502916214059,
        12.507343278686905, -0.13857109526572012, 9.9843695780195716e-6, 1.5056327351493116e-7
    ];

    /**
     * Calculate gamma with Lanczos approximation
     *
     * @param mixed $z Value
     *
     * @return float
     *
     * @since  1.0.0
     */
    public static function lanczosApproximationReal($z) : float
    {
        if ($z < 0.5) {
            return M_PI / (\sin(M_PI * $z) * self::lanczosApproximationReal(1 - $z));
        }

        $z -= 1;
        $a  = self::LANCZOSAPPROXIMATION[0];
        $t  = $z + 7.5;

        for ($i = 1; $i < 9; ++$i) {
            $a += self::LANCZOSAPPROXIMATION[$i] / ($z + $i);
        }

        return \sqrt(2 * M_PI) * \pow($t, $z + 0.5) * \exp(-$t) * $a;
    }

    /**
     * Calculate gamma with Stirling approximation
     *
     * @param mixed $x Value
     *
     * @return float
     *
     * @since  1.0.0
     */
    public static function stirlingApproximation($x) : float
    {
        return \sqrt(2.0 * M_PI / $x) * \pow($x / M_E, $x);
    }

    /**
     * Calculate gamma with Spouge approximation
     *
     * @param mixed $z Value
     *
     * @return float
     *
     * @since  1.0.0
     */
    public static function spougeApproximation($z) : float
    {
        $k1_fact = 1.0;
        $c       = [\sqrt(2.0 * M_PI)];

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
     * Calculate gamma function value.
     *
     * Example: (7)
     *
     * @param int $k Variable
     *
     * @return int
     *
     * @since  1.0.0
     */
    public static function getGammaInteger(int $k) : int
    {
        return Functions::fact($k - 1);
    }
}
