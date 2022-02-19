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

namespace phpOMS\tests\DataStorage\Database;

use phpOMS\DataStorage\Database\DatabaseType;

/**
 * @internal
 */
final class DatabaseTypeTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @group framework
     * @coversNothing
     */
    public function testEnumCount() : void
    {
        self::assertCount(5, DatabaseType::getConstants());
    }

    /**
     * @group framework
     * @coversNothing
     */
    public function testUnique() : void
    {
        self::assertEquals(DatabaseType::getConstants(), \array_unique(DatabaseType::getConstants()));
    }

    /**
     * @group framework
     * @coversNothing
     */
    public function testEnums() : void
    {
        self::assertEquals('mysql', DatabaseType::MYSQL);
        self::assertEquals('pgsql', DatabaseType::PGSQL);
        self::assertEquals('sqlite', DatabaseType::SQLITE);
        self::assertEquals('mssql', DatabaseType::SQLSRV);
        self::assertEquals('undefined', DatabaseType::UNDEFINED);
    }
}
