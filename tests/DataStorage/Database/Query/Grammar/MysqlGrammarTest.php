<?php
/**
 * Karaka
 *
 * PHP Version 8.0
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://karaka.app
 */
declare(strict_types=1);

namespace phpOMS\tests\DataStorage\Database\Query\Grammar;

use phpOMS\DataStorage\Database\Query\Grammar\MysqlGrammar;
use phpOMS\Utils\TestUtils;

/**
 * @testdox phpOMS\tests\DataStorage\Database\Query\MysqlGrammarTest: Mysql sql query grammar
 *
 * @internal
 */
final class MysqlGrammarTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox The grammar has the expected default values after initialization
     * @group framework
     */
    public function testDefault() : void
    {
        self::assertInstanceOf('\phpOMS\DataStorage\Database\Query\Grammar\Grammar', new MysqlGrammar());
        self::assertEquals('`', TestUtils::getMember(new MysqlGrammar(), 'systemIdentifierStart'));
        self::assertEquals('`', TestUtils::getMember(new MysqlGrammar(), 'systemIdentifierEnd'));
    }
}
