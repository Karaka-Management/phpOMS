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

namespace phpOMS\tests\DataStorage\Database\Query;

use phpOMS\DataStorage\Database\Query\QueryType;

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
        self::assertCount(7, QueryType::getConstants());
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
        self::assertEquals(0, QueryType::SELECT);
        self::assertEquals(1, QueryType::INSERT);
        self::assertEquals(2, QueryType::UPDATE);
        self::assertEquals(3, QueryType::DELETE);
        self::assertEquals(4, QueryType::RANDOM);
        self::assertEquals(5, QueryType::RAW);
        self::assertEquals(6, QueryType::NONE);
    }
}
