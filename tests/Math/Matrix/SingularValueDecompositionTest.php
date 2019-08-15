<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package    tests
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       https://orange-management.org
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
            [2, -2, 1],
            [5, 1, 4],
        ]);

        $svd = new SingularValueDecomposition($A);

        self::assertEquals(2, $svd->rank());
    }

    /**
     * @testdox Test the correct calculation of U, S and V
     */
    public function testSUVCalculation() : void
    {
        $A = new Matrix();
        $A->setMatrix([
            [2, -2, 1],
            [5, 1, 4],
        ]);

        $svd = new SingularValueDecomposition($A);

        self::assertEquals([
            [-0.3092, -0.9510],
            [-0.9510, 0.3092],
        ], $svd->getU()->toArray(), '', 0.2);

        self::assertEquals([
            [6.7751, 0, 0],
            [0, 2.2578, 0],
            [0, 0, 0],
        ], $svd->getS()->toArray(), '', 0.2);

        self::assertEquals([
            [-0.7931, -0.1576, -0.5883],
            [-0.0491, 0.9794, -0.1961],
            [-0.6071, 0.1267, 0.7845],
        ], $svd->getV()->toArray(), '', 0.2);
    }

    /**
     * @testdox Test A = S * U * V'
     */
    public function testComposition() : void
    {
        $A = new Matrix();
        $A->setMatrix([
            [2, -2, 1],
            [5, 1, 4],
        ]);

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
