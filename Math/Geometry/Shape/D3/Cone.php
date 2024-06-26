<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   phpOMS\Math\Geometry\Shape\D3
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Math\Geometry\Shape\D3;

/**
 * Cone shape.
 *
 * @package phpOMS\Math\Geometry\Shape\D3
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class Cone implements D3ShapeInterface
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
     * Volume
     *
     * @param float $r Radius
     * @param float $h Height
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getVolume(float $r, float $h) : float
    {
        return \M_PI * $r ** 2 * $h / 3;
    }

    /**
     * Surface area
     *
     * @param float $r Radius
     * @param float $h Height
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getSurface(float $r, float $h) : float
    {
        return \M_PI * $r * ($r + \sqrt($h ** 2 + $r ** 2));
    }

    /**
     * Slant height
     *
     * @param float $r Radius
     * @param float $h Height
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getSlantHeight(float $r, float $h) : float
    {
        return \sqrt($h ** 2 + $r ** 2);
    }

    /**
     * Height
     *
     * @param float $V Volume
     * @param float $r Radius
     *
     * @return float
     *
     * @since  1.0.0
     *
     * @SuppressWarnings(PHPMD.CamelCaseVariableName)
     * @SuppressWarnings(PHPMD.CamelCaseParameterName)
     */
    public static function getHeightFromVolume(float $V, float $r) : float
    {
        return 3 * $V / (\M_PI * $r ** 2);
    }
}
