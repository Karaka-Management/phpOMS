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
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\tests\Localization\Defaults;

require_once __DIR__ . '/../../Autoloader.php';

use phpOMS\Localization\Defaults\Iban;

/**
 * @testdox phpOMS\tests\Localization\Defaults\IbanTest: Iban database model
 *
 * @internal
 */
final class IbanTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox The model has the expected member variables and default values
     * @covers phpOMS\Localization\Defaults\Iban
     * @group framework
     */
    public function testDefaults() : void
    {
        $obj = new Iban();
        self::assertEquals('', $obj->getCountry());
        self::assertEquals(2, $obj->getChars());
        self::assertEquals('', $obj->getBban());
        self::assertEquals('', $obj->getFields());
    }
}
