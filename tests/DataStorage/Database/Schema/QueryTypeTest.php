<?php
/**
 * Karaka
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

namespace phpOMS\tests\DataStorage\Database\Schema;

use phpOMS\DataStorage\Database\Schema\QueryType;

/**
 * @internal
 */
final class QueryTypeTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @group framework
     * @coversNothing
     */
    public function testEnumCount() : void
    {
        self::assertCount(13, QueryType::getConstants());
    }

    /**
     * @group framework
     * @coversNothing
     */
    public function testUnique() : void
    {
        self::assertEquals(QueryType::getConstants(), \array_unique(QueryType::getConstants()));
    }

    /**
     * @group framework
     * @coversNothing
     */
    public function testEnums() : void
    {
        self::assertEquals(128, QueryType::DROP_DATABASE);
        self::assertEquals(129, QueryType::ALTER);
        self::assertEquals(130, QueryType::TABLES);
        self::assertEquals(131, QueryType::FIELDS);
        self::assertEquals(132, QueryType::CREATE_TABLE);
        self::assertEquals(133, QueryType::DROP_TABLE);
    }
}
