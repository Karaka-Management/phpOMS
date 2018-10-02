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

use phpOMS\DataStorage\Database\DatabaseStatus;

class DatabaseStatusTest extends \PHPUnit\Framework\TestCase
{
    public function testEnums()
    {
        self::assertEquals(6, \count(DatabaseStatus::getConstants()));
        self::assertEquals(0, DatabaseStatus::OK);
        self::assertEquals(1, DatabaseStatus::MISSING_DATABASE);
        self::assertEquals(2, DatabaseStatus::MISSING_TABLE);
        self::assertEquals(3, DatabaseStatus::FAILURE);
        self::assertEquals(4, DatabaseStatus::READONLY);
        self::assertEquals(5, DatabaseStatus::CLOSED);
    }
}
