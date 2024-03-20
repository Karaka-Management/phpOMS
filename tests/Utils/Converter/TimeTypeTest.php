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

namespace phpOMS\tests\Utils\Converter;

use phpOMS\Utils\Converter\TimeType;

/**
 * @internal
 */
final class TimeTypeTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\CoversNothing]
    public function testEnumCount() : void
    {
        self::assertCount(9, TimeType::getConstants());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\CoversNothing]
    public function testUnique() : void
    {
        self::assertEquals(TimeType::getConstants(), \array_unique(TimeType::getConstants()));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\CoversNothing]
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
