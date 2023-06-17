<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\tests\DataStorage\Database;

use phpOMS\DataStorage\Database\RelationType;

/**
 * @internal
 */
final class RelationTypeTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @group framework
     * @coversNothing
     */
    public function testEnumCount() : void
    {
        self::assertCount(7, RelationType::getConstants());
    }

    /**
     * @group framework
     * @coversNothing
     */
    public function testUnique() : void
    {
        self::assertEquals(RelationType::getConstants(), \array_unique(RelationType::getConstants()));
    }

    /**
     * @group framework
     * @coversNothing
     */
    public function testEnums() : void
    {
        self::assertEquals(1, RelationType::NONE);
        self::assertEquals(2, RelationType::NEWEST);
        self::assertEquals(4, RelationType::BELONGS_TO);
        self::assertEquals(8, RelationType::OWNS_ONE);
        self::assertEquals(16, RelationType::HAS_MANY);
        self::assertEquals(32, RelationType::ALL);
        self::assertEquals(64, RelationType::REFERENCE);
    }
}
