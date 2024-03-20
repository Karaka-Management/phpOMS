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
use phpOMS\Localization\ISO639Enum;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Localization\ISO639EnumTest: ISO 639 language codes')]
final class ISO639EnumTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The ISO 639 language code enum has only unique values')]
    #[\PHPUnit\Framework\Attributes\CoversNothing]
    public function testUnique() : void
    {
        $enum = ISO639Enum::getConstants();
        self::assertEquals(\count($enum), \count(\array_unique($enum)));
    }

    public function testLanguage() : void
    {
        $enum = ISO3166TwoEnum::getConstants();

        foreach ($enum as $code) {
            if ($code === 'XX') {
                continue;
            }

            self::assertGreaterThan(0, \count(ISO639Enum::languageFromCountry($code)), 'Failed for ' . $code);
        }
    }
}
