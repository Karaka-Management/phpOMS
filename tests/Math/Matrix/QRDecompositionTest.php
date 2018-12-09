<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    tests
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */

namespace phpOMS\tests\Math\Matrix;

use phpOMS\Math\Matrix\Matrix;
use phpOMS\Math\Matrix\QRDecomposition;
use phpOMS\Math\Matrix\Vector;

class QRDecompositionTest extends \PHPUnit\Framework\TestCase
{
    public function testDecomposition()
    {
        $A = new Matrix();
        $A->setMatrix([
            [12, -51, 4],
            [6, 167, -68],
            [-4, 24, -41],
        ]);

        $QR = new QRDecomposition($A);

        self::assertTrue($QR->isFullRank());

        self::assertEquals([
            [-6 / 7, 69 / 175, -58 / 175],
            [-3 / 7, -158 / 175, -6 / 175],
            [2 / 7, -6 / 35, -33 / 35],
        ], $QR->getQ()->toArray(), '', 0.2);

        self::assertEquals([
            [-14, -21, 14],
            [0, -175, 70],
            [0, 0, 35],
        ], $QR->getR()->toArray(), '', 0.2);
    }

    public function testComposition()
    {
        $A = new Matrix();
        $A->setMatrix([
            [12, -51, 4],
            [6, 167, -68],
            [-4, 24, -41],
        ]);

        $QR = new QRDecomposition($A);

        self::assertEquals(
            $A->toArray(),
            $QR->getQ()
                ->mult($QR->getR())
                ->toArray(),
            '', 0.2
        );
    }

    public function testSolve()
    {
        $A = new Matrix();
        $A->setMatrix([
            [25, 15, -5],
            [15, 17, 0],
            [-5, 0, 11],
        ]);

        $QR = new QRDecomposition($A);

        $vec = new Vector();
        $vec->setMatrix([[40], [49], [28]]);
        self::assertEquals([[1], [2], [3]], $QR->solve($vec)->toArray(), '', 0.2);
    }
}
