<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    tests
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */

namespace phpOMS\tests\Utils;

require_once __DIR__ . '/../Autoloader.php';

use phpOMS\Utils\TestUtils;

class TestUtilsTest extends \PHPUnit\Framework\TestCase
{
    public function testGet()
    {
        $class = new TestUtilsClass();

        self::assertEquals(1, TestUtils::getMember($class, 'a'));
        self::assertEquals(2, TestUtils::getMember($class, 'b'));
        self::assertEquals(3, TestUtils::getMember($class, 'c'));
        self::assertEquals('4', TestUtils::getMember($class, 'd'));

        self::assertNull(TestUtils::getMember($class, 'e'));
    }

    public function testSet()
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

        self::assertFalse(TestUtils::setMember($class, 'e', 8));
    }
}
