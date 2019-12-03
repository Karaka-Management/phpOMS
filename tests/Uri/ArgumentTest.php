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

namespace phpOMS\tests\Uri;

require_once __DIR__ . '/../Autoloader.php';

use phpOMS\Uri\Argument;

/**
 * @testdox phpOMS\tests\Uri\ArgumentTest: Argument uri / uri
 *
 * @internal
 */
class ArgumentTest extends \PHPUnit\Framework\TestCase
{

    /**
     * @testdox A uri can be validated
     * @covers phpOMS\Uri\Argument
     * @group framework
     */
    public function testValidator() : void
    {
        self::assertTrue(Argument::isValid('http://www.google.de'));
        self::assertTrue(Argument::isValid('http://google.de'));
        self::assertTrue(Argument::isValid('https://google.de'));
        self::assertTrue(Argument::isValid('skladf klsdee; eleklt ,- -sdf er'));
        self::assertTrue(Argument::isValid('https:/google.de'));
    }

    /**
     * @testdox The argument uri has the expected default values after initialization
     * @covers phpOMS\Uri\Argument
     * @group framework
     */
    public function testDefault() : void
    {
        $obj = new Argument(':modules/admin/test/path.php ?para1=abc ?para2=2 #frag');

        self::assertEquals('/', $obj->getRootPath());
        self::assertEquals(0, $obj->getPathOffset());
        self::assertEquals('', $obj->getScheme());
        self::assertEquals('', $obj->getHost());
        self::assertEquals(0, $obj->getPort());
        self::assertEquals('', $obj->getPass());
        self::assertEquals('', $obj->getUser());
        self::assertEquals('', $obj->getAuthority());
        self::assertEquals('', $obj->getUserInfo());
        self::assertEquals('', $obj->getBase());
    }

    /**
     * @testdox The path can be parsed correctly from a uri
     * @covers phpOMS\Uri\Argument
     * @group framework
     */
    public function testPathInputOutput() : void
    {
        $obj = new Argument(':modules/admin/test/path.php ?para1=abc ?para2=2 #frag');

        self::assertEquals('modules/admin/test/path', $obj->getPath());
        self::assertEquals('modules', $obj->getPathElement(0));
    }

    /**
     * @testdox The route can be parsed correctly from a uri
     * @covers phpOMS\Uri\Argument
     * @group framework
     */
    public function testRouteInputOutput() : void
    {
        $obj = new Argument(':modules/admin/test/path.php ?para1=abc ?para2=2 #frag');

        self::assertEquals('modules/admin/test/path ?para1=abc ?para2=2', $obj->getRoute());
    }

    /**
     * @testdox The query data can be parsed correctly from a uri
     * @covers phpOMS\Uri\Argument
     * @group framework
     */
    public function testQueryInputOutput() : void
    {
        $obj = new Argument(':modules/admin/test/path.php ?para1=abc ?para2=2 #frag');

        self::assertEquals('?para1=abc ?para2=2', $obj->getQuery());
        self::assertEquals(['para1' => 'abc', 'para2' => '2'], $obj->getQueryArray());
        self::assertEquals('2', $obj->getQuery('para2'));

    }

    /**
     * @testdox The fragment can be parsed correctly from a uri
     * @covers phpOMS\Uri\Argument
     * @group framework
     */
    public function testFragmentInputOutput() : void
    {
        $obj = new Argument(':modules/admin/test/path.php ?para1=abc ?para2=2 #frag');

        self::assertEquals('frag', $obj->getFragment());
    }

    /**
     * @testdox The uri can be turned into a string
     * @covers phpOMS\Uri\Argument
     * @group framework
     */
    public function testStringify() : void
    {
        $obj = new Argument($uri = ':modules/admin/test/path.php ?para1=abc ?para2=2 #frag');

        self::assertEquals($uri, $obj->__toString());
    }

    /**
     * @testdox The root path can be set and returned
     * @covers phpOMS\Uri\Argument
     * @group framework
     */
    public function testRootPathInputOutput() : void
    {
        $obj = new Argument(':modules/admin/test/path.php ?para1=abc ?para2=2 #frag');

        $obj->setRootPath('a');
        self::assertEquals('a', $obj->getRootPath());
    }
}
