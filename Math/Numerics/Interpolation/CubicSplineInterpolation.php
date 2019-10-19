<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   phpOMS\Math\Numerics\Interpolation
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Math\Numerics\Interpolation;

use phpOMS\Math\Matrix\Matrix;
use phpOMS\Math\Matrix\Vector;

/**
 * Cubic spline interpolation.
 *
 * @package phpOMS\Math\Numerics\Interpolation
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
final class CubicSplineInterpolation implements InterpolationInterface
{
    /**
     * Points for spline interpolation
     *
     * @var   array
     * @since 1.0.0
     */
    private array $points = [];

    /**
     * Parameter a of cubic spline
     *
     * @var   Vector
     * @since 1.0.0
     */
    private Vector $solveA;

    /**
     * Parameter b of cubic spline
     *
     * @var   Matrix
     * @since 1.0.0
     */
    private Matrix $solveB;

    /**
     * Parameter c of cubic spline
     *
     * @var   Vector
     * @since 1.0.0
     */
    private Vector $solveC;

    /**
     * Constructor.
     *
     * @param array $points              Points to create the interpolation with
     * @param float $leftCurvature       Left point curvature
     * @param float $leftDerivativeType  Derivative type for the left point
     * @param float $rightCurvature      Right point curvature
     * @param float $rightDerivativeType Derivative type for the right point
     *
     * @since 1.0.0
     */
    public function __construct(
        array $points,
        float $leftCurvature = 0.0,
        int $leftDerivativeType = DerivativeType::FIRST,
        float $rightCurvature = 0.0,
        int $rightDerivativeType = DerivativeType::FIRST
    ) {
        $this->points = $points;

        $n      = \count($this->points);
        $b      = [];
        $matrix = new Matrix($n, $n);

        for ($i = 1; $i < $n - 1; ++$i) {
            $matrix->set($i, $i - 1, 1.0 / 3.0 * ($this->points[$i]['x'] - $this->points[$i - 1]['x']));
            $matrix->set($i, $i, 2.0 / 3.0 * ($this->points[$i + 1]['x'] - $this->points[$i - 1]['x']));
            $matrix->set($i, $i + 1, 1.0 / 3.0 * ($this->points[$i + 1]['x'] - $this->points[$i]['x']));

            $b[$i] = ($this->points[$i + 1]['y'] - $this->points[$i]['y']) / ($this->points[$i + 1]['x'] - $this->points[$i]['x'])
                - ($this->points[$i]['y'] - $this->points[$i - 1]['y']) / ($this->points[$i]['x'] - $this->points[$i - 1]['x']);
        }

        if ($leftDerivativeType === DerivativeType::FIRST) {
            $matrix->set(0, 0, 2.0 * ($this->points[1]['x'] - $this->points[0]['x']));
            $matrix->set(0, 1, 1.0 * ($this->points[1]['x'] - $this->points[0]['x']));

            $b[0] = 3.0 * (($this->points[1]['y'] - $this->points[0]['y']) / ($this->points[1]['x'] - $this->points[0]['x']) - $rightCurvature);
        } else {
            $matrix->set(0, 0, 2.0);
            $matrix->set(0, 1, 0.0);

            $b[0] = $leftCurvature;
        }

        if ($rightDerivativeType === DerivativeType::FIRST) {
            $matrix->set($n - 1, $n - 1, 2.0 * ($this->points[$n - 1]['x'] - $this->points[$n - 2]['x']));
            $matrix->set($n - 1, $n - 2, 1.0 * ($this->points[$n - 1]['x'] - $this->points[$n - 2]['x']));

            $b[$n - 1] = 3.0 * ($rightCurvature - ($this->points[$n - 1]['y'] - $this->points[$n - 2]['y']) / ($this->points[$n - 1]['x'] - $this->points[$n - 2]['x']));
        } else {
            $matrix->set($n - 1, $n - 1, 2.0);
            $matrix->set($n - 1, $n - 2, 0.0);

            $b[$n - 1] = $rightCurvature;
        }

        $bVector = new Vector($n);
        $bVector->setMatrixV($b);

        $this->solveB = $matrix->solve($bVector);
        $this->solveA = new Vector($n);
        $this->solveC = new Vector($n);

        for ($i = 0; $i < $n - 1; ++$i) {
            $this->solveA->setV($i, 1.0 / 3.0 * ($this->solveB->get($i + 1) - $this->solveB->get($i)) / ($this->points[$i + 1]['x'] - $this->points[$i]['x']));
            $this->solveC->setV($i,
                ($this->points[$i + 1]['y'] - $this->points[$i]['y']) / ($this->points[$i + 1]['x'] - $this->points[$i]['x'])
                - 1.0 / 3.0 * (2 * $this->solveB->get($i) + $this->solveB->get($i + 1)) * ($this->points[$i + 1]['x'] - $this->points[$i]['x']));
        }

        $h = $this->points[$n - 1]['x'] - $this->points[$n - 2]['x'];

        $this->solveA->setV($n - 1, 0.0);
        $this->solveC->setV($n - 1, 3.0 * $this->solveA->getV($n - 2) * $h ** 2 + 2.0 * $this->solveB->get($n - 2) * $h + $this->solveC->getV($n - 2));

        $a = 2;

        /**
         * @todo: consider linear extrapolation at start and end point
         *
         * $this->solveB->setV($n - 1, 0.0)
         */
    }

    /**
     * {@inheritdoc}
     */
    public function interpolate($x) : float
    {
        $n    = \count($this->points);
        $xPos = $n - 1;

        foreach ($this->points as $key => $point) {
            if ($x <= $point['x']) {
                $xPos = $key;

                break;
            }
        }

        $xPos = \max($xPos - 1, 0);
        $h    = $x - $this->points[$xPos]['x'];

        if ($x < $this->points[0]['x']) {
            return ($this->solveB->get(0) * $h + $this->solveC->getV(0)) * $h + $this->points[0]['y'];
            /**
             * @todo: consider linear extrapolation at start and end point
             *
             * ($this->solveC->getV(0)) * $h + $this->points[0]['y'];
             */
        } elseif ($x > $this->points[$n - 1]['x']) {
            return ($this->solveB->get($n - 1) * $h + $this->solveC->getV($n - 1) * $h + $this->points[$n - 1]['y']);
        }

        return (($this->solveA->getV($xPos) * $h + $this->solveB->get($xPos)) * $h + $this->solveC->getV($xPos)) * $h + $this->points[$xPos]['y'];
    }
}
