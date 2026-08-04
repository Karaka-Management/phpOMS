<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.2
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\tests\Uri;

require_once __DIR__ . '/../Autoloader.php';

use phpOMS\Uri\Argument;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Uri\Argument::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Uri\ArgumentTest: Argument uri / uri')]
final class ArgumentTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A uri can be validated')]
    public function testValidator() : void
    {
        self::assertTrue(Argument::isValid('http://www.google.de'));
        self::assertTrue(Argument::isValid('http://google.de'));
        self::assertTrue(Argument::isValid('https://google.de'));
        self::assertTrue(Argument::isValid('skladf klsdee; eleklt ,- -sdf er'));
        self::assertTrue(Argument::isValid('https:/google.de'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The argument uri has the expected default values after initialization')]
    public function testDefault() : void
    {
        $obj = new Argument(':modules/admin/test/path.php ?para1=abc ?para2=2 #frag');

        self::assertEquals('/', $obj->getRootPath());
        self::assertEquals(0, $obj->getPathOffset());
        self::assertEquals('', $obj->scheme);
        self::assertEquals('', $obj->host);
        self::assertEquals(0, $obj->port);
        self::assertEquals('', $obj->pass);
        self::assertEquals('', $obj->user);
        self::assertEquals('', $obj->getAuthority());
        self::assertEquals('', $obj->getUserInfo());
        self::assertEquals('', $obj->getBase());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The path can be parsed correctly from a uri')]
    public function testParsePathInputOutput() : void
    {
        $obj = new Argument(':modules/admin/test/path.php ?para1=abc ?para2=2 #frag');

        self::assertEquals(':modules/admin/test/path.php', $obj->getPath());
        self::assertEquals(':modules', $obj->getPathElement(0));
        self::assertEquals(
            [':modules', 'admin', 'test', 'path.php'],
            $obj->getPathElements()
        );
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The path can be set and returned')]
    public function testPathInputOutput() : void
    {
        $obj = new Argument(':modules/admin/test/path.php ?para1=abc ?para2=2 #frag');

        $obj->setPath('modules/admin/new/path');
        self::assertEquals('modules/admin/new/path', $obj->getPath());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The path offset can be set and returned')]
    public function testPathOffsetInputOutput() : void
    {
        $obj = new Argument();
        $obj->setPathOffset(2);

        self::assertEquals(2, $obj->getPathOffset());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The route can be parsed correctly from a uri')]
    public function testRouteInputOutput() : void
    {
        $obj = new Argument(':modules/admin/test/path.php ?para1=abc ?para2=2 #frag');

        self::assertEquals('/:modules/admin/test/path.php ?para1=abc ?para2=2 #frag', $obj->getRoute());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The query data can be parsed correctly from a uri')]
    public function testQueryInputOutput() : void
    {
        $obj = new Argument(':modules/admin/test/path.php ?para1=abc ?para2=2 #frag');

        self::assertEquals('?para1=abc ?para2=2 #frag', $obj->getQuery());
        self::assertEquals(['?para1=abc', '?para2=2', '#frag'], $obj->getQueryArray());
        self::assertEquals('?para2=2', $obj->getQuery('1'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The fragment can be parsed correctly from a uri')]
    public function testFragmentInputOutput() : void
    {
        $obj = new Argument(':modules/admin/test/path.php ?para1=abc ?para2=2 #frag');

        self::assertEquals('', $obj->fragment);

        $obj->fragment = 'frag2';
        self::assertEquals('frag2', $obj->fragment);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The uri can be turned into a string')]
    public function testStringify() : void
    {
        $obj = new Argument($uri = ':modules/admin/test/path.php ?para1=abc ?para2=2 #frag');

        self::assertEquals($uri, $obj->__toString());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The root path can be set and returned')]
    public function testRootPathInputOutput() : void
    {
        $obj = new Argument(':modules/admin/test/path.php ?para1=abc ?para2=2 #frag');

        $obj->setRootPath('a');
        self::assertEquals('a', $obj->getRootPath());
    }
}
