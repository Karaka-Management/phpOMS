<?php
/**
 * Orange Management
 *
 * PHP Version 8.0
 *
 * @package   phpOMS\Math\Numerics\Interpolation
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Math\Numerics\Interpolation;

use phpOMS\Math\Matrix\Vector;

/**
 * Linear spline interpolation.
 *
 * @package phpOMS\Math\Numerics\Interpolation
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
final class LinearInterpolation implements InterpolationInterface
{
    /**
     * Points for spline interpolation
     *
     * @var array<int, array{x:int|float, y:int|float}>
     * @since 1.0.0
     */
    private array $points = [];

    /**
     * Parameter a of cubic spline
     *
     * @var Vector
     * @since 1.0.0
     */
    private Vector $solveA;

    /**
     * Parameter b of cubic spline
     *
     * @var Vector
     * @since 1.0.0
     */
    private Vector $solveB;

    /**
     * Parameter c of cubic spline
     *
     * @var Vector
     * @since 1.0.0
     */
    private Vector $solveC;

    /**
     * Constructor.
     *
     * @param array<int, array{x:int|float, y:int|float}> $points Points to create the interpolation with
     *
     * @since 1.0.0
     */
    public function __construct(array $points) {
        $this->points = $points;

        $n = \count($this->points);

        $this->solveA = new Vector($n);
        $this->solveB = new Vector($n);
        $this->solveC = new Vector($n);

        for ($i = 0; $i < $n - 1; ++$i) {
            $this->solveA->setV($i, 0.0);
            $this->solveB->setV($i, 0.0);
            $this->solveC->setV($i, ($this->points[$i + 1]['y'] - $this->points[$i]['y']) / ($this->points[$i + 1]['x'] - $this->points[$i]['x']));
        }

        for ($i = 0; $i < $n - 1; ++$i) {
            $this->solveA->setV($i, 1.0 / 3.0 * ($this->solveB->getV($i + 1) - $this->solveB->getV($i)) / ($this->points[$i + 1]['x'] - $this->points[$i]['x']));
            $this->solveC->setV($i,
                ($this->points[$i + 1]['y'] - $this->points[$i]['y']) / ($this->points[$i + 1]['x'] - $this->points[$i]['x'])
                - 1.0 / 3.0 * (2 * $this->solveB->getV($i) + $this->solveB->getV($i + 1)) * ($this->points[$i + 1]['x'] - $this->points[$i]['x']));
        }

        $h = $this->points[$n - 1]['x'] - $this->points[$n - 2]['x'];

        $this->solveA->setV($n - 1, 0.0);
        $this->solveC->setV($n - 1, 3.0 * $this->solveA->getV($n - 2) * $h ** 2 + 2.0 * $this->solveB->getV($n - 2) * $h + $this->solveC->getV($n - 2));
    }

    /**
     * {@inheritdoc}
     */
    public function interpolate(int | float $x) : float
    {
        $n    = \count($this->points);
        $xPos = $n - 1;

        foreach ($this->points as $key => $point) {
            if ($x <= $point['x']) {
                $xPos = $key;

                break;
            }
        }

        $xPos = max($xPos - 1, 0);
        $h    = $x - $this->points[$xPos]['x'];

        if ($x < $this->points[0]['x']) {
            return ($this->solveB->getV(0) * $h + $this->solveC->getV(0)) * $h + $this->points[0]['y'];
        } elseif ($x > $this->points[$n - 1]['x']) {
            return ($this->solveB->getV($n - 1) * $h + $this->solveC->getV($n - 1) * $h + $this->points[$n - 1]['y']);
        }

        return (($this->solveA->getV($xPos) * $h + $this->solveB->getV($xPos)) * $h + $this->solveC->getV($xPos)) * $h + $this->points[$xPos]['y'];
    }
}
