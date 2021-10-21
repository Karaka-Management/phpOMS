<?php
/**
 * Orange Management
 *
 * PHP Version 8.0
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\tests\DataStorage\Database;

use phpOMS\DataStorage\Database\DatabaseStatus;

/**
 * @internal
 */
final class DatabaseStatusTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @group framework
     * @coversNothing
     */
    public function testEnumCount() : void
    {
        self::assertCount(6, DatabaseStatus::getConstants());
    }

    /**
     * @group framework
     * @coversNothing
     */
    public function testUnique() : void
    {
        self::assertEquals(DatabaseStatus::getConstants(), \array_unique(DatabaseStatus::getConstants()));
    }

    /**
     * @group framework
     * @coversNothing
     */
    public function testEnums() : void
    {
        self::assertEquals(0, DatabaseStatus::OK);
        self::assertEquals(1, DatabaseStatus::MISSING_DATABASE);
        self::assertEquals(2, DatabaseStatus::MISSING_TABLE);
        self::assertEquals(3, DatabaseStatus::FAILURE);
        self::assertEquals(4, DatabaseStatus::READONLY);
        self::assertEquals(5, DatabaseStatus::CLOSED);
    }
}
