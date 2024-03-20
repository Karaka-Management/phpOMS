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
 * @testdox phpOMS\tests\Uri\UriFactoryTest: Http uri / url factory
 *
 * @internal
 */
final class UriFactoryTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox The http url factory has the expected default values after initialization
     * @covers \phpOMS\Uri\UriFactory
     * @group framework
     */
    public function testDefault() : void
    {
        self::assertNull(UriFactory::getQuery('Invalid'));
        self::assertFalse(UriFactory::clear('Valid5'));
    }

    /**
     * @testdox Data can be set to the factory and returned
     * @covers \phpOMS\Uri\UriFactory
     * @group framework
     */
    public function testQueryInputOutput() : void
    {
        self::assertTrue(UriFactory::setQuery('Valid', 'query1'));
        self::assertEquals('query1', UriFactory::getQuery('Valid'));
    }

    /**
     * @testdox Data can be forcefully overwritten
     * @covers \phpOMS\Uri\UriFactory
     * @group framework
     */
    public function testOverwrite() : void
    {
        UriFactory::setQuery('Valid2', 'query1');
        self::assertTrue(UriFactory::setQuery('Valid2', 'query2', true));
        self::assertEquals('query2', UriFactory::getQuery('Valid2'));
    }

    /**
     * @testdox By default data is not overwritten in the factory
     * @covers \phpOMS\Uri\UriFactory
     * @group framework
     */
    public function testInvalidOverwrite() : void
    {
        UriFactory::setQuery('Valid3', 'query1');
        self::assertFalse(UriFactory::setQuery('Valid3', 'query3'));
        self::assertEquals('query1', UriFactory::getQuery('Valid3'));
    }

    /**
     * @testdox Data can be removed/cleared from the factory
     * @covers \phpOMS\Uri\UriFactory
     * @group framework
     */
    public function testClearing() : void
    {
        UriFactory::setQuery('Valid4', 'query1');
        self::assertTrue(UriFactory::clear('Valid4'));
        self::assertNull(UriFactory::getQuery('Valid4'));
    }

    /**
     * @testdox None-existing data cannot be cleared from the factory
     * @covers \phpOMS\Uri\UriFactory
     * @group framework
     */
    public function testInvalidClearing() : void
    {
        UriFactory::setQuery('Valid5', 'query1');
        self::assertTrue(UriFactory::clear('Valid5'));
        self::assertFalse(UriFactory::clear('Valid5'));
    }

    /**
     * @testdox Data can be removed from the factory by category
     * @covers \phpOMS\Uri\UriFactory
     * @group framework
     */
    public function testClean() : void
    {
        UriFactory::setQuery('\Valid6', 'query1');
        UriFactory::setQuery('\Valid7', 'query2');
        UriFactory::clean('\\');
        self::assertNull(UriFactory::getQuery('\Valid6'));
        self::assertNull(UriFactory::getQuery('\Valid7'));
    }

    /**
     * @testdox All data can be removed from the factory with a wildcard
     * @covers \phpOMS\Uri\UriFactory
     * @group framework
     */
    public function testCleanWildcard() : void
    {
        UriFactory::setQuery('\Valid8', 'query1');
        UriFactory::setQuery('.Valid9', 'query2');
        UriFactory::clean('*');
        self::assertNull(UriFactory::getQuery('\Valid8'));
        self::assertNull(UriFactory::getQuery('.Valid9'));
    }

    /**
     * @testdox Data can be removed from the factory with regular expression matches
     * @covers \phpOMS\Uri\UriFactory
     * @group framework
     */
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

    /**
     * @testdox Data which doesn't match the regular expression is not removed
     * @covers \phpOMS\Uri\UriFactory
     * @group framework
     */
    public function testInvalidClearingLike() : void
    {
        UriFactory::setQuery('/def', 'query1');
        UriFactory::setQuery('/ghi3', 'query2');
        UriFactory::setQuery('/jkl4', 'query3');
        self::assertFalse(UriFactory::clearLike('\d+'));
    }

    /**
     * @testdox A url can be build with the defined factory data and/or build specific data
     * @covers \phpOMS\Uri\UriFactory
     * @group framework
     */
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

    /**
     * @testdox The uri factory can be set up with default values from a url and build with these default values
     * @covers \phpOMS\Uri\UriFactory
     * @group framework
     */
    public function testSetupBuild() : void
    {
        $uri = 'http://www.test-uri.com/path/here?id=123&ab=c#fragi';

        UriFactory::setupUriBuilder(new HttpUri($uri));

        self::assertEquals($uri, UriFactory::build('{/tld}{/rootPath}{/}?id={?id}&ab={?ab}#{#}'));
        self::assertEquals($uri, UriFactory::build('{/scheme}://{/host}{/rootPath}{/}?id={?id}&ab={?ab}#{#}'));
        self::assertEquals($uri, UriFactory::build('{%}'));
        self::assertEquals($uri, UriFactory::build('{/tld}{/rootPath}{/}?{?}#{#}'));
    }

    /**
     * @testdox In case of duplicated query elements the last element is used
     * @covers \phpOMS\Uri\UriFactory
     * @group framework
     */
    public function testDuplicatedQueryElements() : void
    {
        $uri      = '/path/here?id=123&ab=c&id=456#fragi';
        $expected = '/path/here?id=456&ab=c#fragi';

        UriFactory::setupUriBuilder(new HttpUri($uri));

        self::assertEquals($expected, UriFactory::build('{/base}{/rootPath}{/}?id={?id}&ab={?ab}#{#}'));
    }

    /**
     * @testdox The uri variables can be unescaped
     * @covers \phpOMS\Uri\UriFactory
     * @group framework
     */
    public function testVariableUnescape() : void
    {
        $uri       = '/path/here?id=123&ab=c#fragi';
        $escaped   = '{/base}{/rootPath}{/}?id=\{\?id\}&ab={?ab}#{#}';
        $unescaped = '/path/here?id={?id}&ab=c#fragi';

        UriFactory::setupUriBuilder(new HttpUri($uri));

        self::assertEquals($unescaped, UriFactory::build($escaped));
    }

    /**
     * @testdox In case of missing ? for  query the builder automatically fixes it
     * @covers \phpOMS\Uri\UriFactory
     * @group framework
     */
    public function testMissingQueryIdentifier() : void
    {
        $uri = '/path/here?id=123&ab=c#fragi';

        UriFactory::setupUriBuilder(new HttpUri($uri));

        self::assertEquals($uri, UriFactory::build('{/base}{/rootPath}{/}&id={?id}&ab={?ab}#{#}'));
    }

    /**
     * @testdox A normal url will not be changed
     * @covers \phpOMS\Uri\UriFactory
     * @group framework
     */
    public function testNormalUrlParsing() : void
    {
        $uri      = 'http://www.test-uri.com/path/here?id=123&ab=c#fragi';
        $expected = 'http://www.test-uri.com/path/here?id=123&ab=c#fragi';

        self::assertEquals($expected, UriFactory::build($uri));
    }
}
