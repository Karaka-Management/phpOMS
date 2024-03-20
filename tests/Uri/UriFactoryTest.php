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

namespace phpOMS\tests\Uri;

use phpOMS\Uri\HttpUri;
use phpOMS\Uri\UriFactory;

require_once __DIR__ . '/../Autoloader.php';

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Uri\UriFactory::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Uri\UriFactoryTest: Http uri / url factory')]
final class UriFactoryTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The http url factory has the expected default values after initialization')]
    public function testDefault() : void
    {
        self::assertNull(UriFactory::getQuery('Invalid'));
        self::assertFalse(UriFactory::clear('Valid5'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Data can be set to the factory and returned')]
    public function testQueryInputOutput() : void
    {
        self::assertTrue(UriFactory::setQuery('Valid', 'query1'));
        self::assertEquals('query1', UriFactory::getQuery('Valid'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Data can be forcefully overwritten')]
    public function testOverwrite() : void
    {
        UriFactory::setQuery('Valid2', 'query1');
        self::assertTrue(UriFactory::setQuery('Valid2', 'query2', true));
        self::assertEquals('query2', UriFactory::getQuery('Valid2'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('By default data is not overwritten in the factory')]
    public function testInvalidOverwrite() : void
    {
        UriFactory::setQuery('Valid3', 'query1');
        self::assertFalse(UriFactory::setQuery('Valid3', 'query3'));
        self::assertEquals('query1', UriFactory::getQuery('Valid3'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Data can be removed/cleared from the factory')]
    public function testClearing() : void
    {
        UriFactory::setQuery('Valid4', 'query1');
        self::assertTrue(UriFactory::clear('Valid4'));
        self::assertNull(UriFactory::getQuery('Valid4'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('None-existing data cannot be cleared from the factory')]
    public function testInvalidClearing() : void
    {
        UriFactory::setQuery('Valid5', 'query1');
        self::assertTrue(UriFactory::clear('Valid5'));
        self::assertFalse(UriFactory::clear('Valid5'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Data can be removed from the factory by category')]
    public function testClean() : void
    {
        UriFactory::setQuery('\Valid6', 'query1');
        UriFactory::setQuery('\Valid7', 'query2');
        UriFactory::clean('\\');
        self::assertNull(UriFactory::getQuery('\Valid6'));
        self::assertNull(UriFactory::getQuery('\Valid7'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('All data can be removed from the factory with a wildcard')]
    public function testCleanWildcard() : void
    {
        UriFactory::setQuery('\Valid8', 'query1');
        UriFactory::setQuery('.Valid9', 'query2');
        UriFactory::clean('*');
        self::assertNull(UriFactory::getQuery('\Valid8'));
        self::assertNull(UriFactory::getQuery('.Valid9'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Data can be removed from the factory with regular expression matches')]
    public function testClearingLike() : void
    {
        UriFactory::setQuery('/abc', 'query1');
        UriFactory::setQuery('/Valid10', 'query2');
        UriFactory::setQuery('/Valid11', 'query3');
        self::assertTrue(UriFactory::clearLike('\/[a-zA-Z]*\d+'));

        self::assertNull(UriFactory::getQuery('/valid2'));
        self::assertNull(UriFactory::getQuery('/valid3'));
        self::assertEquals('query1', UriFactory::getQuery('/abc'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox("Data which doesn't match the regular expression is not removed")]
    public function testInvalidClearingLike() : void
    {
        UriFactory::setQuery('/def', 'query1');
        UriFactory::setQuery('/ghi3', 'query2');
        UriFactory::setQuery('/jkl4', 'query3');
        self::assertFalse(UriFactory::clearLike('\d+'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A url can be build with the defined factory data and/or build specific data')]
    public function testBuilder() : void
    {
        $uri = 'www.test-uri.com?id={@ID}&test={.mTest}&two={/path}&hash={#hash}&none=#none&found={/not}&v={/valid2}';

        $vars = [
            '@ID'    => 1,
            '.mTest' => 'someString',
            '/path'  => 'PATH',
            '#hash'  => 'test',
        ];

        self::assertTrue(UriFactory::setQuery('/valid2', 'query4'));

        $expected = 'www.test-uri.com?id=1&test=someString&two=PATH&hash=test#none&found=&v=query4';

        self::assertEquals($expected, UriFactory::build($uri, $vars));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The uri factory can be set up with default values from a url and build with these default values')]
    public function testSetupBuild() : void
    {
        $uri = 'http://www.test-uri.com/path/here?id=123&ab=c#fragi';

        UriFactory::setupUriBuilder(new HttpUri($uri));

        self::assertEquals($uri, UriFactory::build('{/tld}{/rootPath}{/}?id={?id}&ab={?ab}#{#}'));
        self::assertEquals($uri, UriFactory::build('{/scheme}://{/host}{/rootPath}{/}?id={?id}&ab={?ab}#{#}'));
        self::assertEquals($uri, UriFactory::build('{%}'));
        self::assertEquals($uri, UriFactory::build('{/tld}{/rootPath}{/}?{?}#{#}'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('In case of duplicated query elements the last element is used')]
    public function testDuplicatedQueryElements() : void
    {
        $uri      = '/path/here?id=123&ab=c&id=456#fragi';
        $expected = '/path/here?id=456&ab=c#fragi';

        UriFactory::setupUriBuilder(new HttpUri($uri));

        self::assertEquals($expected, UriFactory::build('{/base}{/rootPath}{/}?id={?id}&ab={?ab}#{#}'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The uri variables can be unescaped')]
    public function testVariableUnescape() : void
    {
        $uri       = '/path/here?id=123&ab=c#fragi';
        $escaped   = '{/base}{/rootPath}{/}?id=\{\?id\}&ab={?ab}#{#}';
        $unescaped = '/path/here?id={?id}&ab=c#fragi';

        UriFactory::setupUriBuilder(new HttpUri($uri));

        self::assertEquals($unescaped, UriFactory::build($escaped));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('In case of missing ? for  query the builder automatically fixes it')]
    public function testMissingQueryIdentifier() : void
    {
        $uri = '/path/here?id=123&ab=c#fragi';

        UriFactory::setupUriBuilder(new HttpUri($uri));

        self::assertEquals($uri, UriFactory::build('{/base}{/rootPath}{/}&id={?id}&ab={?ab}#{#}'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A normal url will not be changed')]
    public function testNormalUrlParsing() : void
    {
        $uri      = 'http://www.test-uri.com/path/here?id=123&ab=c#fragi';
        $expected = 'http://www.test-uri.com/path/here?id=123&ab=c#fragi';

        self::assertEquals($expected, UriFactory::build($uri));
    }
}
