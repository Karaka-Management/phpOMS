<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    tests
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */

namespace phpOMS\tests\Localization\Defaults;

require_once __DIR__ . '/../../Autoloader.php';

use phpOMS\Localization\Defaults\Currency;

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
