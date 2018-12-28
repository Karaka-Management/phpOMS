<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    tests
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */

namespace phpOMS\tests\DataStorage\Database\Schema\Grammar;

use phpOMS\DataStorage\Database\Schema\Grammar\SqlServerGrammar;

class SqlServerGrammarTest extends \PHPUnit\Framework\TestCase
{
    public function testDefault()
    {
        self::assertInstanceOf('\phpOMS\DataStorage\Database\Schema\Grammar\Grammar', new SqlServerGrammar());
    }
}
