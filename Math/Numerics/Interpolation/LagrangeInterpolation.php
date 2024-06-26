<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   phpOMS\Math\Numerics\Interpolation
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Math\Numerics\Interpolation;

/**
 * Lagrange spline interpolation.
 *
 * @package phpOMS\Math\Numerics\Interpolation
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class LagrangeInterpolation implements InterpolationInterface
{
    /**
     * Points for spline interpolation
     *
     * @var array<int, array{x:int|float, y:int|float}>
     * @since 1.0.0
     */
    private array $points = [];

    /**
     * Constructor.
     *
     * @param array<int, array{x:int|float, y:int|float}> $points Points to create the interpolation with
     *
     * @since 1.0.0
     */
    public function __construct(array $points)
    {
        $this->points = $points;
    }

    /**
     * {@inheritdoc}
     */
    public function interpolate(int | float $x) : float
    {
        $n      = \count($this->points);
        $result = 0.0;

        for ($i = 0; $i < $n; ++$i) {
            $solve = $this->points[$i]['y'];
            for ($j = 0; $j < $n; ++$j) {
                if ($j !== $i) {
                    $solve *= ($x - $this->points[$j]['x']) / ($this->points[$i]['x'] - $this->points[$j]['x']);
                }
            }

            $result += $solve;
        }

        return $result;
    }
}
