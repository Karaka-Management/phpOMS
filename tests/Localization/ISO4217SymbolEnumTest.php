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

use phpOMS\Localization\ISO4217SymbolEnum;

/**
 * @testdox phpOMS\tests\Localization\ISO4217SymbolEnumTest: ISO 4217 currency codes
 * @internal
 */
final class ISO4217SymbolEnumTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox The ISO 4217 currency code enum has the correct number of currency symbols
     * @group framework
     * @coversNothing
     */
    public function testEnums() : void
    {
        $enum = ISO4217SymbolEnum::getConstants();
        self::assertCount(109, $enum);
    }
}
