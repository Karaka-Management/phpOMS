<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\tests\Math\Matrix;

use phpOMS\Math\Matrix\Matrix;
use phpOMS\Math\Matrix\SingularValueDecomposition;

/**
 * @testdox phpOMS\tests\Math\Matrix\SingularValueDecompositionTest: Singular Value Decomposition
 *
 * @internal
 */
class SingularValueDecompositionTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox Test the correct rank calculation
     */
    public function testRankCalculation() : void
    {
        $A = new Matrix();
        $A->setMatrix([
            [1, 2, 3],
            [-2, 3, 8],
            [5, 1, -3],
        ]);

        self::markTestIncomplete();
        return;

        $svd = new SingularValueDecomposition($A);

        self::assertEquals(3, $svd->rank());
    }

    /**
     * @testdox Test the correct calculation of U, S and V
     */
    public function testSUVCalculation() : void
    {
        $A = new Matrix();
        $A->setMatrix([
            [1, 2, 3],
            [-2, 3, 8],
            [5, 1, -3],
        ]);

        self::markTestIncomplete();
        return;

        $svd = new SingularValueDecomposition($A);

        self::assertEquals([
            [0.2871, -0.4773, -0.8305],
            [0.8640, -0.2453, 0.4397],
            [-0.4136, -0.8438, 0.3420],
        ], $svd->getU()->toArray(), '', 0.2);

        self::assertEquals([
            [10.0571, 0, 0],
            [0, 4.9855, 0],
            [0, 0, 0],
        ], $svd->getS()->toArray(), '', 0.2);

        self::assertEquals([
            [-0.3489, -0.8436, -0.4082],
            [0.2737, -0.5084, 0.8165],
            [0.8963, -0.1731, -0.4082],
        ], $svd->getV()->toArray(), '', 0.2);
    }

    /**
     * @testdox Test A = S * U * V'
     */
    public function testComposition() : void
    {
        $A = new Matrix();
        $A->setMatrix([
            [1, 2, 3],
            [-2, 3, 8],
            [5, 1, -3],
        ]);

        self::markTestIncomplete();
        return;

        $svd = new SingularValueDecomposition($A);

        self::assertEquals(
            $A->toArray(),
            $svd->getU()
                ->mult($svd->getS())
                ->mult($svd->getV()->transpose())
                ->toArray(),
            '', 0.2
        );
    }
}
