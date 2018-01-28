<?php
/**
 * Orange Management
 *
 * PHP Version 7.1
 *
 * @package    TBD
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */

namespace phpOMS\tests\DataStorage\Database\Query\Grammar;

use phpOMS\DataStorage\Database\Query\Grammar\SQLiteGrammar;
use phpOMS\Utils\TestUtils;

class SQLiteGrammarTest extends \PHPUnit\Framework\TestCase
{
    public function testDefault()
    {
        self::assertInstanceOf('\phpOMS\DataStorage\Database\Query\Grammar\Grammar', new SqliteGrammar());
        self::assertEquals('`', TestUtils::getMember(new SqliteGrammar(), 'systemIdentifier'));
    }
}
