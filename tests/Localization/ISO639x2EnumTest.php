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

namespace phpOMS\tests\Localization;

require_once __DIR__ . '/../Autoloader.php';

use phpOMS\Localization\ISO639x2Enum;

/**
 * @testdox phpOMS\tests\Localization\ISO639x2EnumTest: ISO 639-2 language codes
 * @internal
 */
final class ISO639x2EnumTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox The ISO 639-2 language code enum has the correct format of language codes
     * @group framework
     * @coversNothing
     */
    public function testEnums() : void
    {
        $ok = true;

        $enum = ISO639x2Enum::getConstants();

        foreach ($enum as $code) {
            if (\strlen($code) !== 3) {
                $ok = false;
                break;
            }
        }

        self::assertTrue($ok);
    }

    /**
     * @testdox The ISO 639-2 enum has only unique values
     * @group framework
     * @coversNothing
     */
    public function testUnique() : void
    {
        self::assertEquals(ISO639x2Enum::getConstants(), \array_unique(ISO639x2Enum::getConstants()));
    }
}
