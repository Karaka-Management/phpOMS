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

use phpOMS\Math\Matrix\Vector;

/**
 * @internal
 */
class VectorTest extends \PHPUnit\Framework\TestCase
{
    public function testDefault() : void
    {
        self::assertInstanceOf('\phpOMS\Math\Matrix\Vector', new Vector());
        self::assertEquals(1, (new Vector())->getM());

        $vec = new Vector(5);
        self::assertCount(5, $vec->toArray());
    }

    public function testGetSet() : void
    {
        $vec = new Vector(5);
        $vec->setMatrixV([1, 2, 3, 4, 5]);

        self::assertEquals(5, $vec->getM());
        self::assertEquals(2, $vec->getV(1));

        $vec->setV(3, 9);
        self::assertEquals(9, $vec->getV(3));
    }
}
