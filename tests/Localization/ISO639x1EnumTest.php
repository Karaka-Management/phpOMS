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

namespace phpOMS\tests\Localization;

require_once __DIR__ . '/../Autoloader.php';

use phpOMS\Localization\ISO3166TwoEnum;
use phpOMS\Localization\ISO639x1Enum;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Localization\ISO639x1EnumTest: ISO 639-1 language codes')]
final class ISO639x1EnumTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The ISO 639-1 language code enum has the correct format of language codes')]
    #[\PHPUnit\Framework\Attributes\CoversNothing]
    public function testEnums() : void
    {
        $ok = true;

        $enum = ISO639x1Enum::getConstants();

        foreach ($enum as $code) {
            if (\strlen($code) !== 2 && $code !== 'fil') {
                $ok = false;
                break;
            }
        }

        self::assertTrue($ok, 'Failed for ' . $code);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The ISO 639-1 enum has only unique values')]
    #[\PHPUnit\Framework\Attributes\CoversNothing]
    public function testUnique() : void
    {
        self::assertEquals(ISO639x1Enum::getConstants(), \array_unique(ISO639x1Enum::getConstants()));
    }

    public function testLanguage() : void
    {
        $enum = ISO3166TwoEnum::getConstants();

        foreach ($enum as $code) {
            if ($code === 'XX') {
                continue;
            }

            self::assertGreaterThan(0, \count(ISO639x1Enum::languageFromCountry($code)), 'Failed for code: ' . $code);
        }
    }
}
