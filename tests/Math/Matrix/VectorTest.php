<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\tests\Math\Matrix;

use phpOMS\Math\Matrix\Vector;

/**
 * @testdox phpOMS\tests\Math\Matrix\VectorTest: Vector operations
 *
 * @internal
 */
final class VectorTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox The vector has the expected default values after initialization
     * @covers \phpOMS\Math\Matrix\Vector
     * @group framework
     */
    public function testDefault() : void
    {
        self::assertInstanceOf('\phpOMS\Math\Matrix\Vector', new Vector());
        self::assertEquals(1, (new Vector())->getM());

        $vec = new Vector(5);
        self::assertCount(5, $vec->toArray());
    }

    /**
     * @testdox The vector values can be set and returned
     * @covers \phpOMS\Math\Matrix\Vector
     * @group framework
     */
    public function testValueInputOutput() : void
    {
        $vec = new Vector(5);
        $vec->setMatrixV([1, 2, 3, 4, 5]);

        self::assertEquals(2, $vec->getV(1));

        $vec->setV(3, 9);
        self::assertEquals(9, $vec->getV(3));
    }

    /**
     * @testdox The vector dimension can be returned
     * @covers \phpOMS\Math\Matrix\Vector
     * @group framework
     */
    public function testDim() : void
    {
        $vec = new Vector(5);
        $vec->setMatrixV([1, 2, 3, 4, 5]);

        self::assertEquals(5, $vec->getM());
    }

    public function testCosine() : void
    {
        $v1 = Vector::fromArray([3, 4, 0]);
        $v2 = Vector::fromArray([4, 4, 2]);

        self::assertEqualsWithDelta(14 / 15, $v1->cosine($v2), 0.1);
    }

    public function testCross3() : void
    {
        self::assertEquals(
            [-15, -2, 39],
            Vector::fromArray([3, -3, 1])->cross3(Vector::fromArray([4, 9, 2]))->toVectorArray()
        );
    }
}
