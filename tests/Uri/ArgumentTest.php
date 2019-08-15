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

namespace phpOMS\tests\Uri;

require_once __DIR__ . '/../Autoloader.php';

use phpOMS\Uri\Argument;

/**
 * @internal
 */
class ArgumentTest extends \PHPUnit\Framework\TestCase
{
    public function testAttributes() : void
    {
        $obj = new Argument('');

        /* Testing members */
        self::assertObjectHasAttribute('rootPath', $obj);
        self::assertObjectHasAttribute('uri', $obj);
        self::assertObjectHasAttribute('scheme', $obj);
        self::assertObjectHasAttribute('host', $obj);
        self::assertObjectHasAttribute('port', $obj);
        self::assertObjectHasAttribute('user', $obj);
        self::assertObjectHasAttribute('pass', $obj);
        self::assertObjectHasAttribute('path', $obj);
        self::assertObjectHasAttribute('query', $obj);
        self::assertObjectHasAttribute('queryString', $obj);
        self::assertObjectHasAttribute('fragment', $obj);
        self::assertObjectHasAttribute('base', $obj);
    }

    public function testHelper() : void
    {
        self::assertTrue(Argument::isValid('http://www.google.de'));
        self::assertTrue(Argument::isValid('http://google.de'));
        self::assertTrue(Argument::isValid('https://google.de'));
        self::assertTrue(Argument::isValid('skladf klsdee; eleklt ,- -sdf er'));
        self::assertTrue(Argument::isValid('https:/google.de'));
    }

    public function testSetGet() : void
    {
        $obj = new Argument($uri = ':modules/admin/test/path.php ?para1=abc ?para2=2 #frag');

        self::assertEquals('/', $obj->getRootPath());
        self::assertEquals(0, $obj->getPathOffset());
        self::assertEquals('', $obj->getScheme());
        self::assertEquals('', $obj->getHost());
        self::assertEquals(0, $obj->getPort());
        self::assertEquals('', $obj->getPass());
        self::assertEquals('', $obj->getUser());
        self::assertEquals('modules/admin/test/path', $obj->getPath());
        self::assertEquals('modules/admin/test/path ?para1=abc ?para2=2', $obj->getRoute());
        self::assertEquals('modules', $obj->getPathElement(0));
        self::assertEquals('?para1=abc ?para2=2', $obj->getQuery());
        self::assertEquals(['para1' => 'abc', 'para2' => '2'], $obj->getQueryArray());
        self::assertEquals('2', $obj->getQuery('para2'));
        self::assertEquals('frag', $obj->getFragment());
        self::assertEquals('', $obj->getBase());
        self::assertEquals($uri, $obj->__toString());
        self::assertEquals('', $obj->getAuthority());
        self::assertEquals('', $obj->getUserInfo());

        $obj->setRootPath('a');
        self::assertEquals('a', $obj->getRootPath());
    }
}
