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

namespace phpOMS\tests\DataStorage\Database;

use phpOMS\DataStorage\Database\DatabaseType;

class DatabaseTypeTest extends \PHPUnit\Framework\TestCase
{
    public function testEnums()
    {
        self::assertEquals(5, \count(DatabaseType::getConstants()));
        self::assertEquals('mysql', DatabaseType::MYSQL);
        self::assertEquals('pgsql', DatabaseType::PGSQL);
        self::assertEquals('sqlite', DatabaseType::SQLITE);
        self::assertEquals('mssql', DatabaseType::SQLSRV);
        self::assertEquals('undefined', DatabaseType::UNDEFINED);
    }
}
