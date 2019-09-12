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

namespace phpOMS\tests\DataStorage\Database\Query\Grammar;

use phpOMS\DataStorage\Database\Query\Grammar\SQLiteGrammar;
use phpOMS\Utils\TestUtils;

/**
 * @internal
 */
class SQLiteGrammarTest extends \PHPUnit\Framework\TestCase
{
    public function testDefault() : void
    {
        self::assertInstanceOf('\phpOMS\DataStorage\Database\Query\Grammar\Grammar', new SqliteGrammar());
        self::assertEquals('`', TestUtils::getMember(new SqliteGrammar(), 'systemIdentifier'));
    }
}
