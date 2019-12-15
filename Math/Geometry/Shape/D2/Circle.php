<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   phpOMS\Math\Geometry\Shape\D2
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Math\Geometry\Shape\D2;

/**
 * Circle shape.
 *
 * @package phpOMS\Math\Geometry\Shape\D2
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
final class Circle implements D2ShapeInterface
{
    /**
     * Area
     *
     * @param float $r Radius
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getSurface(float $r) : float
    {
        return \M_PI * $r ** 2;
    }

    /**
     * Circumference
     *
     * @param float $r Radius
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getPerimeter(float $r) : float
    {
        return 2 * \M_PI * $r;
    }

    /**
     * Radius
     *
     * @param float $surface Surface
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getRadiusBySurface(float $surface) : float
    {
        return \sqrt($surface / \M_PI);
    }

    /**
     * Radius
     *
     * @param float $C Circumference
     *
     * @return float
     *
     * @since  1.0.0
     *
     * @SuppressWarnings(PHPMD.CamelCaseVariableName)
     * @SuppressWarnings(PHPMD.CamelCaseParameterName)
     */
    public static function getRadiusByPerimeter(float $C) : float
    {
        return $C / (2 * \M_PI);
    }
}
