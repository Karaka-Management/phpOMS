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

namespace phpOMS\tests\DataStorage\Database;

use phpOMS\DataStorage\Database\DatabaseType;

/**
 * @internal
 */
class DatabaseTypeTest extends \PHPUnit\Framework\TestCase
{
    public function testEnums() : void
    {
        self::assertCount(5, DatabaseType::getConstants());
        self::assertEquals('mysql', DatabaseType::MYSQL);
        self::assertEquals('pgsql', DatabaseType::PGSQL);
        self::assertEquals('sqlite', DatabaseType::SQLITE);
        self::assertEquals('mssql', DatabaseType::SQLSRV);
        self::assertEquals('undefined', DatabaseType::UNDEFINED);
    }
}
