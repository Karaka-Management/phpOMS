<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\tests\Validation\Finance;

use phpOMS\Validation\Finance\IbanErrorType;

/**
 * @internal
 */
final class IbanErrorTypeTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\CoversNothing]
    public function testEnumCount() : void
    {
        self::assertCount(5, IbanErrorType::getConstants());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\CoversNothing]
    public function testUnique() : void
    {
        self::assertEquals(IbanErrorType::getConstants(), \array_unique(IbanErrorType::getConstants()));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\CoversNothing]
    public function testEnums() : void
    {
        self::assertEquals(1, IbanErrorType::INVALID_COUNTRY);
        self::assertEquals(2, IbanErrorType::INVALID_LENGTH);
        self::assertEquals(4, IbanErrorType::INVALID_CHECKSUM);
        self::assertEquals(8, IbanErrorType::EXPECTED_ZERO);
        self::assertEquals(16, IbanErrorType::EXPECTED_NUMERIC);
    }
}
