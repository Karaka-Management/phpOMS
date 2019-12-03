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

namespace phpOMS\tests\Utils\Converter;

use phpOMS\Utils\Converter\TimeType;

/**
 * @internal
 */
class TimeTypeTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @group framework
     * @coversNothing
     */
    public function testEnumCount() : void
    {
        self::assertCount(9, TimeType::getConstants());
    }

    /**
     * @group framework
     * @coversNothing
     */
    public function testUnique() : void
    {
        self::assertEquals(TimeType::getConstants(), \array_unique(TimeType::getConstants()));
    }

    /**
     * @group framework
     * @coversNothing
     */
    public function testEnums() : void
    {
        self::assertEquals('ms', TimeType::MILLISECONDS);
        self::assertEquals('s', TimeType::SECONDS);
        self::assertEquals('i', TimeType::MINUTES);
        self::assertEquals('h', TimeType::HOURS);
        self::assertEquals('d', TimeType::DAYS);
        self::assertEquals('w', TimeType::WEEKS);
        self::assertEquals('m', TimeType::MONTH);
        self::assertEquals('q', TimeType::QUARTER);
        self::assertEquals('y', TimeType::YEAR);
    }
}
