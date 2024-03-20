<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\tests\Math\Matrix;

use phpOMS\Math\Matrix\LUDecomposition;
use phpOMS\Math\Matrix\Matrix;
use phpOMS\Math\Matrix\Vector;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Math\Matrix\LUDecomposition::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Math\Matrix\LUDecompositionTest: LU decomposition')]
final class LUDecompositionTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The L matrix of the decomposition can be calculated')]
    public function testL() : void
    {
        $B = new Matrix();
        $B->setMatrix([
            [25, 15, -5],
            [15, 17, 0],
            [-5, 0, 11],
        ]);

        $lu = new LUDecomposition($B);

        self::assertEqualsWithDelta([
            [1, 0, 0],
            [0.6, 1, 0],
            [-0.2, 0.375, 1],
        ], $lu->getL()->toArray(), 0.2);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The U matrix of the decomposition can be calculated')]
    public function testU() : void
    {
        $B = new Matrix();
        $B->setMatrix([
            [25, 15, -5],
            [15, 17, 0],
            [-5, 0, 11],
        ]);

        $lu = new LUDecomposition($B);

        self::assertEqualsWithDelta([
            [25, 15, -5],
            [0, 8, 3],
            [0, 0, 8.875],
        ], $lu->getU()->toArray(), 0.2);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The matrix can be checked for singularity')]
    public function testSingularity() : void
    {
        $A = new Matrix();
        $A->setMatrix([
            [25, 15, -5],
            [15, 17, 0],
            [-5, 0, 11],
        ]);

        $lu = new LUDecomposition($A);

        self::assertTrue($lu->isNonSingular());

        $B = new Matrix();
        $B->setMatrix([
            [25, 15, -5],
            [0, 0, 1],
            [0, 0, 2],
        ]);

        $luB = new LUDecomposition($B);

        self::assertFalse($luB->isNonSingular());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The equation Ax = b can be solved for a none-singular matrix')]
    public function testSolve() : void
    {
        $B = new Matrix();
        $B->setMatrix([
            [25, 15, -5],
            [15, 17, 0],
            [-5, 0, 11],
        ]);

        $lu = new LUDecomposition($B);

        $vec = new Vector();
        $vec->setMatrix([[40], [49], [28]]);
        self::assertEqualsWithDelta([[1], [2], [3]], $lu->solve($vec)->toArray(), 0.2);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The pivots of the decomposition can be calculated')]
    public function testPivot() : void
    {
        $B = new Matrix();
        $B->setMatrix([
            [25, 15, -5],
            [15, 17, 0],
            [-5, 0, 11],
        ]);

        $lu = new LUDecomposition($B);

        self::assertEquals([0, 1, 2], $lu->getPivot());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The equation Ax = b can be solved for a singular matrix')]
    public function testSolveOfSingularMatrix() : void
    {
        $this->expectException(\Exception::class);

        $B = new Matrix();
        $B->setMatrix([
            [25, 15, -5],
            [0, 0, 1],
            [0, 0, 2],
        ]);

        $lu = new LUDecomposition($B);

        $vec = new Vector();
        $vec->setMatrix([[40], [49], [28]]);

        $lu->solve($vec);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The decomposition can be created and the original matrix can be computed')]
    public function testComposition() : void
    {
        $A = new Matrix();
        $A->setMatrix([
            [25, 15, -5],
            [15, 17, 0],
            [-5, 0, 11],
        ]);

        $lu = new LUDecomposition($A);

        self::assertEqualsWithDelta(
            $A->toArray(),
            $lu->getL()
                ->mult($lu->getU())
                ->toArray(),
            0.2
        );
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The determinat can be calculated')]
    public function testDet() : void
    {
        $B = new Matrix();
        $B->setMatrix([
            [25, 15, -5],
            [15, 17, 0],
            [-5, 0, 11],
        ]);

        $lu = new LUDecomposition($B);
        self::assertEqualsWithDelta(1775.0, $lu->det(), 0.1);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A invalid vector throws a InvalidDimensionException')]
    public function testInvalidDimension() : void
    {
        $this->expectException(\phpOMS\Math\Matrix\Exception\InvalidDimensionException::class);

        $B = new Matrix();
        $B->setMatrix([
            [25, 15, -5],
            [15, 17, 0],
            [-5, 0, 11],
        ]);

        $lu  = new LUDecomposition($B);
        $vec = new Vector();
        $vec->setMatrix([[40], [49]]);

        $lu->solve($vec);
    }
}
