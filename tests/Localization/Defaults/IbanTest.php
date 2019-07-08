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
 * @link       https://orange-management.org
 */
 declare(strict_types=1);

namespace phpOMS\tests\Localization\Defaults;

require_once __DIR__ . '/../../Autoloader.php';

use phpOMS\Localization\Defaults\Iban;

/**
 * @internal
 */
class IbanTest extends \PHPUnit\Framework\TestCase
{
    public function testDefaults() : void
    {
        $obj = new Iban();
        self::assertEquals('', $obj->getCountry());
        self::assertEquals(2, $obj->getChars());
        self::assertEquals('', $obj->getBban());
        self::assertEquals('', $obj->getFields());
    }
}
