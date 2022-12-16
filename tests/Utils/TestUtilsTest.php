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

namespace phpOMS\tests\Utils;

require_once __DIR__ . '/../Autoloader.php';

use phpOMS\Utils\TestUtils;

/**
 * @testdox phpOMS\tests\Utils\TestUtilsTest: Test utilities
 *
 * @internal
 */
final class TestUtilsTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox A member value can be returned
     * @covers phpOMS\Utils\TestUtils
     * @group framework
     */
    public function testGet() : void
    {
        $class = new TestUtilsClass();

        self::assertEquals(1, TestUtils::getMember($class, 'a'));
        self::assertEquals(2, TestUtils::getMember($class, 'b'));
        self::assertEquals(3, TestUtils::getMember($class, 'c'));
        self::assertEquals('4', TestUtils::getMember($class, 'd'));
    }

    /**
     * @testdox Invalid member variable returns null
     * @covers phpOMS\Utils\TestUtils
     * @group framework
     */
    public function testInvalidGet() : void
    {
        $class = new TestUtilsClass();

        self::assertNull(TestUtils::getMember($class, 'e'));
    }

    /**
     * @testdox A member value can be set and returned
     * @covers phpOMS\Utils\TestUtils
     * @group framework
     */
    public function testInputOutput() : void
    {
        $class = new TestUtilsClass();

        self::assertTrue(TestUtils::setMember($class, 'a', 4));
        self::assertTrue(TestUtils::setMember($class, 'b', 5));
        self::assertTrue(TestUtils::setMember($class, 'c', 6));
        self::assertTrue(TestUtils::setMember($class, 'd', '7'));

        self::assertEquals(4, TestUtils::getMember($class, 'a'));
        self::assertEquals(5, TestUtils::getMember($class, 'b'));
        self::assertEquals(6, TestUtils::getMember($class, 'c'));
        self::assertEquals('7', TestUtils::getMember($class, 'd'));
    }

    /**
     * @testdox A none-existing member variable cannot be set
     * @covers phpOMS\Utils\TestUtils
     * @group framework
     */
    public function testInputInputOutput() : void
    {
        $class = new TestUtilsClass();

        self::assertFalse(TestUtils::setMember($class, 'e', 8));
    }
}
