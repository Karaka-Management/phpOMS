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
namespace phpOMS\Math\Optimization;

use phpOMS\Math\Matrix\Matrix;

/**
 * Gaussian elimination class
 *
 * @category   Framework
 * @package    phpOMS\Math\Matrix
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
class GaussianElimination
{
    /**
     * Solve equation with gaussian elimination.
     *
     * @param Matrix $A Matrix A
     * @param Matrix $b Vector b
     *
     * @return Matrix
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function solve(Matrix $A, Matrix $b) : Matrix
    {
        $limit = min($A->getM(), $A->getN());
        $A     = $A->getMatrix();
        $b     = $b->getMatrix();

        for ($col = 0; $col < $limit; $col++) {
            $j   = $col;
            $max = $A[$j][$j];

            for ($i = $col + 1; $i < $limit; $i++) {
                $tmp = abs($A[$i][$col]);

                if ($tmp > $max) {
                    $j   = $i;
                    $max = $tmp;
                }
            }

            self::swapRows($A, $b, $col, $j);

            for ($i = $col + 1; $i < $limit; $i++) {
                $tmp = $A[$i][$col] / $A[$col][$col];

                for ($j = $col + 1; $j < $limit; $j++) {
                    $A[$i][$j] -= $tmp * $A[$col][$j];
                }

                $A[$i][$col] = 0;
                $b[$i] -= $tmp * $b[$col];
            }
        }

        $x = [];
        for ($col = $limit - 1; $col >= 0; $col--) {
            $tmp = $b[$col];

            for ($j = $limit - 1; $j > $col; $j--) {
                $tmp -= $x[$j] * $A[$col][$j];
            }

            $x[$col] = $tmp / $A[$col][$col];
        }

        $Y = new Matrix(count($x), 1);
        $Y->setMatrix($x);

        return $Y;
    }

    /**
     * Swap rows.
     *
     * @param array $a  Matrix A
     * @param array $b  Vector b
     * @param int   $r1 Row 1
     * @param int   $r2 Row 2
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    private static function swapRows(&$a, &$b, int $r1, int $r2)
    {
        if ($r1 == $r2) {
            return;
        }

        $tmp    = $a[$r1];
        $a[$r1] = $a[$r2];
        $a[$r2] = $tmp;

        $tmp    = $b[$r1];
        $b[$r1] = $b[$r2];
        $b[$r2] = $tmp;
    }
}