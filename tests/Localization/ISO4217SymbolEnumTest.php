<?php
/**
 * Orange Management
 *
 * PHP Version 7.1
 *
 * @package    TBD
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */

namespace phpOMS\tests\Localization;

require_once __DIR__ . '/../Autoloader.php';

use phpOMS\Localization\ISO4217SymbolEnum;

class ISO4217SymbolEnumTest extends \PHPUnit\Framework\TestCase
{
    public function testEnum()
    {
        $enum = ISO4217SymbolEnum::getConstants();
        self::assertEquals(109, count($enum));
    }
}
