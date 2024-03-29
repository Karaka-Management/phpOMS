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

use phpOMS\Math\Matrix\IdentityMatrix;

/**
 * @testdox phpOMS\tests\Math\Matrix\IdentityMatrixTest: Identity matrix
 *
 * @internal
 */
final class IdentityMatrixTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox The identity matrix is the identity
     * @covers phpOMS\Math\Matrix\IdentityMatrix
     * @group framework
     */
    public function testIdentity() : void
    {
        $id = new IdentityMatrix(5);
        self::assertEquals(
            [
                [1, 0, 0, 0, 0],
                [0, 1, 0, 0, 0],
                [0, 0, 1, 0, 0],
                [0, 0, 0, 1, 0],
                [0, 0, 0, 0, 1],
            ],
            $id->toArray()
        );
    }
}
