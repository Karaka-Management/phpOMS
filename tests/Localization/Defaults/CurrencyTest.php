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

namespace phpOMS\tests\Localization\Defaults;

require_once __DIR__ . '/../../Autoloader.php';

use phpOMS\Localization\Defaults\Currency;

/**
 * @internal
 */
class CurrencyTest extends \PHPUnit\Framework\TestCase
{
    public function testDefaults() : void
    {
        $obj = new Currency();
        self::assertEquals('', $obj->getName());
        self::assertEquals(0, $obj->getNumber());
        self::assertEquals(0, $obj->getDecimals());
        self::assertEquals('', $obj->getCountries());
    }
}
