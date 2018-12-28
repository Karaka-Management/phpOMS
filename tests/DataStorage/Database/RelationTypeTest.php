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

use phpOMS\DataStorage\Database\RelationType;

class RelationTypeTest extends \PHPUnit\Framework\TestCase
{
    public function testEnums() : void
    {
        self::assertEquals(7, \count(RelationType::getConstants()));
        self::assertEquals(1, RelationType::NONE);
        self::assertEquals(2, RelationType::NEWEST);
        self::assertEquals(4, RelationType::BELONGS_TO);
        self::assertEquals(8, RelationType::OWNS_ONE);
        self::assertEquals(16, RelationType::HAS_MANY);
        self::assertEquals(32, RelationType::ALL);
        self::assertEquals(64, RelationType::REFERENCE);
    }
}
