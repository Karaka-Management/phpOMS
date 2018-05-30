<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    TBD
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */

namespace phpOMS\tests\Localization\Defaults;

require_once __DIR__ . '/../../Autoloader.php';

use phpOMS\Localization\Defaults\Iban;

class IbanTest extends \PHPUnit\Framework\TestCase
{
    public function testDefaults()
    {
        $obj = new Iban();
        self::assertEquals('', $obj->getCountry());
        self::assertEquals(2, $obj->getChars());
        self::assertEquals('', $obj->getBban());
        self::assertEquals('', $obj->getFields());
    }
}
