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
 * @link      https://karaka.app
 */
declare(strict_types=1);
namespace phpOMS\tests\DataStorage\Database\Schema\Grammar;

use phpOMS\DataStorage\Database\Schema\Grammar\SQLiteGrammar;
use phpOMS\Utils\TestUtils;

/**
 * @testdox phpOMS\tests\DataStorage\Database\Schema\Grammar\SQLiteGrammarTest: SQLite sql schema grammar
 *
 * @internal
 */
final class SQLiteGrammarTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox The grammar has the expected default values after initialization
     * @group framework
     */
    public function testDefault() : void
    {
        self::assertInstanceOf('\phpOMS\DataStorage\Database\Schema\Grammar\Grammar', new SQLiteGrammar());
        self::assertEquals('`', TestUtils::getMember(new SQLiteGrammar(), 'systemIdentifierStart'));
        self::assertEquals('`', TestUtils::getMember(new SQLiteGrammar(), 'systemIdentifierEnd'));
    }
}
