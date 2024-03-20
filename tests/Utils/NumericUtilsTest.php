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

namespace phpOMS\tests\Utils;

use phpOMS\Utils\NumericUtils;

require_once __DIR__ . '/../Autoloader.php';

/**
 * @testdox phpOMS\tests\Utils\NumericUtilsTest: Numeric utilities
 *
 * @internal
 */
final class NumericUtilsTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox Integers can be unsigned right shifted
     * @covers \phpOMS\Utils\NumericUtils
     * @group framework
     */
    public function testShift() : void
    {
        self::assertEquals(10, NumericUtils::uRightShift(10, 0));
        self::assertEquals(3858, NumericUtils::uRightShift(123456, 5));
    }
}
