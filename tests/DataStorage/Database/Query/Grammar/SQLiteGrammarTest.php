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

namespace phpOMS\tests\DataStorage\Database\Query\Grammar;

use phpOMS\DataStorage\Database\Query\Grammar\SQLiteGrammar;
use phpOMS\Utils\TestUtils;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\DataStorage\Database\Query\SQLiteGrammarTest: SQLite sql query grammar')]
final class SQLiteGrammarTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The grammar has the expected default values after initialization')]
    public function testDefault() : void
    {
        self::assertInstanceOf('\phpOMS\DataStorage\Database\Query\Grammar\Grammar', new SqliteGrammar());
        self::assertEquals('`', TestUtils::getMember(new SqliteGrammar(), 'systemIdentifierStart'));
        self::assertEquals('`', TestUtils::getMember(new SqliteGrammar(), 'systemIdentifierEnd'));
    }
}
