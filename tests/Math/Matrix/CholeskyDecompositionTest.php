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

use phpOMS\Math\Matrix\CholeskyDecomposition;
use phpOMS\Math\Matrix\Matrix;
use phpOMS\Math\Matrix\Vector;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Math\Matrix\CholeskyDecomposition::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Math\Matrix\CholeskyDecompositionTest: Cholesky decomposition')]
final class CholeskyDecompositionTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The decomposition can be created and the original matrix can be computed')]
    public function testDecomposition() : void
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

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The decomposition matrix has the expected values')]
    public function testL() : void
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
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A matrix can be checked for symmetric positivity')]
    public function testSymmetricPositive() : void
    {
        $A = new Matrix();
        $A->setMatrix([
            [25, 15, -5],
            [15, 17, 0],
            [-5, 0, 11],
        ]);

        $cholesky = new CholeskyDecomposition($A);

        self::assertTrue($cholesky->isSpd());

        $B = new Matrix();
        $B->setMatrix([
            [25, 15, 5],
            [15, 17, 0],
            [-5, 0, 11],
        ]);

        $choleskyB = new CholeskyDecomposition($B);

        self::assertTrue($choleskyB->isSpd());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The equation Ax = b can be solved')]
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

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A invalid vector throws a InvalidDimensionException')]
    public function testInvalidDimension() : void
    {
        $this->expectException(\phpOMS\Math\Matrix\Exception\InvalidDimensionException::class);

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
