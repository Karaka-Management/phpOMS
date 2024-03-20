<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   TBD
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Math\Geometry\Shape\D2;

/**
 * Quadrilateral shape.
 *
 * @package phpOMS\Math\Geometry\Shape\D2
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class Quadrilateral implements D2ShapeInterface
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
     * Calculate the surface area from the length of all sides and the angle between a and b
     *
     * @param float $a     Side a length (DA)
     * @param float $b     Side b length (AB)
     * @param float $c     Side c length (BC)
     * @param float $d     Side d length (CD)
     * @param float $alpha Angle between side a and b
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getSurfaceFromSidesAndAngle(float $a, float $b, float $c, float $d, float $alpha) : float
    {
        $s     = ($a + $b + $c + $d) / 2;
        $gamma = \acos(($c ** 2 + $d ** 2 - $a ** 2 - $b ** 2 + 2 * $a * $b * \cos(\deg2rad($alpha))) / (2 * $c * $d));

        return \sqrt(($s - $a) * ($s - $b) * ($s - $c) * ($s - $d) - $a * $b * $c * $d * \cos((\deg2rad($alpha) + $gamma) / 2) ** 2);
    }
}
