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

namespace phpOMS\tests\Localization\Defaults;

require_once __DIR__ . '/../../Autoloader.php';

use phpOMS\Localization\Defaults\Currency;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Localization\Defaults\Currency::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Localization\Defaults\CurrencyTest: Currency database model')]
final class CurrencyTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The model has the expected member variables and default values')]
    public function testDefaults() : void
    {
        $obj = new Currency();
        self::assertEquals('', $obj->getName());
        self::assertEquals('', $obj->getNumber());
        self::assertEquals('', $obj->getSymbol());
        self::assertEquals(0, $obj->getSubunits());
        self::assertEquals('', $obj->getDecimals());
        self::assertEquals('', $obj->getCountries());
        self::assertEquals('', $obj->getCode());
    }
}
