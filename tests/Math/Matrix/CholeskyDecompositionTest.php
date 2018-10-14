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
use phpOMS\Math\Matrix\Vector;
use phpOMS\Math\Matrix\CholeskyDecomposition;

class CholeskyDecompositionTest extends \PHPUnit\Framework\TestCase
{
    public function testComposition()
    {
        $A = new Matrix();
        $A->setMatrix([
            [25, 15, -5],
            [15, 17, 0],
            [-5, 0, 11],
        ]);

        $cholesky = new CholeskyDecomposition($A);

        self::assertEquals(
            $A->toArray(),
            $cholesky->getL()
                ->mult($cholesky->getL()->transpose())
                ->toArray(),
            '', 0.2
        );
    }

    public function testDecomposition()
    {
        $A = new Matrix();
        $A->setMatrix([
            [25, 15, -5],
            [15, 17, 0],
            [-5, 0, 11],
        ]);

        $cholesky = new CholeskyDecomposition($A);

        self::assertEquals([
            [5, 0, 0],
            [3, 3, 0],
            [-1, 1, 3],
        ], $cholesky->getL()->toArray(), '', 0.2);

        self::assertTrue($cholesky->isSpd());
    }

    public function testSolve()
    {
        $A = new Matrix();
        $A->setMatrix([
            [25, 15, -5],
            [15, 17, 0],
            [-5, 0, 11],
        ]);

        $cholesky = new CholeskyDecomposition($A);

        $vec = new Vector();
        $vec->setMatrix([[40], [49], [28]]);
        self::assertEquals([[1], [2], [3]], $cholesky->solve($vec)->toArray(), '', 0.2);
    }

    /**
     * @expectedException \phpOMS\Math\Matrix\Exception\InvalidDimensionException
     */
    public function testInvalidDimension()
    {
        $A = new Matrix();
        $A->setMatrix([
            [25, 15, -5],
            [15, 17, 0],
            [-5, 0, 11],
        ]);

        $cholesky = new CholeskyDecomposition($A);

        $vec = new Vector();
        $vec->setMatrix([[40], [49]]);
        $cholesky->solve($vec);
    }
}
