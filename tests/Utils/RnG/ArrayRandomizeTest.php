<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\tests\Utils\RnG;

use phpOMS\Utils\RnG\ArrayRandomize;

/**
 * @testdox phpOMS\tests\Utils\RnG\ArrayRandomizeTest: Array randomizer
 *
 * @internal
 */
final class ArrayRandomizeTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox An array can be randomized using the yates algorithm
     * @covers phpOMS\Utils\RnG\ArrayRandomize
     * @group framework
     */
    public function testYates() : void
    {
        $orig = [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20];

        for ($i = 0; $i < 10; ++$i) {
            if ($orig !== ArrayRandomize::yates($orig)) {
                self::assertTrue(true);
                return;
            }
        }

        self::assertTrue(false);
    }

    /**
     * @testdox An array can be randomized using the knuth algorithm
     * @covers phpOMS\Utils\RnG\ArrayRandomize
     * @group framework
     */
    public function testKnuth() : void
    {
        $orig = [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20];

        for ($i = 0; $i < 10; ++$i) {
            if ($orig !== ArrayRandomize::knuth($orig)) {
                self::assertTrue(true);
                return;
            }
        }

        self::assertTrue(false);
    }
}
