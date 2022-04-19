<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://karaka.app
 */
declare(strict_types=1);

namespace phpOMS\tests\Localization;

require_once __DIR__ . '/../Autoloader.php';

use phpOMS\Localization\ISO3166TwoEnum;

/**
 * @internal
 */
final class ISO3166TwoEnumTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @group framework
     * @coversNothing
     */
    public function testEnums() : void
    {
        $ok = true;

        $countryCodes = ISO3166TwoEnum::getConstants();

        foreach ($countryCodes as $code) {
            if (\strlen($code) !== 2) {
                $ok = false;
                break;
            }
        }

        self::assertTrue($ok);
        self::assertEquals(\count($countryCodes), \count(\array_unique($countryCodes)));
    }
}
