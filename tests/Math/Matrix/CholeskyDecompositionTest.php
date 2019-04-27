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
 declare(strict_types=1);

namespace phpOMS\tests\Math\Matrix;

use phpOMS\Math\Matrix\CholeskyDecomposition;
use phpOMS\Math\Matrix\Matrix;
use phpOMS\Math\Matrix\Vector;

/**
 * @internal
 */
class CholeskyDecompositionTest extends \PHPUnit\Framework\TestCase
{
    public function testComposition() : void
    {
        $A = new Matrix();
        $A->setMatrix([
            [25, 15, -5],
            [15, 17, 0],
            [-5, 0, 11],
        ]);

        $cholesky = new CholeskyDecomposition($A);

        self::assertEqualsWithDelta(
            $A->toArray(),
            $cholesky->getL()
                ->mult($cholesky->getL()->transpose())
                ->toArray(),
            0.2
        );
    }

    public function testDecomposition() : void
    {
        $A = new Matrix();
        $A->setMatrix([
            [25, 15, -5],
            [15, 17, 0],
            [-5, 0, 11],
        ]);

        $cholesky = new CholeskyDecomposition($A);

        self::assertEqualsWithDelta([
            [5, 0, 0],
            [3, 3, 0],
            [-1, 1, 3],
        ], $cholesky->getL()->toArray(), 0.2);

        self::assertTrue($cholesky->isSpd());
    }

    public function testSolve() : void
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
        self::assertEqualsWithDelta([[1], [2], [3]], $cholesky->solve($vec)->toArray(), 0.2);
    }

    public function testInvalidDimension() : void
    {
        self::expectException(\phpOMS\Math\Matrix\Exception\InvalidDimensionException::class);

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
