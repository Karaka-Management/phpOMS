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

use phpOMS\Localization\Defaults\Iban;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Localization\Defaults\Iban::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Localization\Defaults\IbanTest: Iban database model')]
final class IbanTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The model has the expected member variables and default values')]
    public function testDefaults() : void
    {
        $obj = new Iban();
        self::assertEquals('', $obj->country);
        self::assertEquals(2, $obj->getChars());
        self::assertEquals('', $obj->getBban());
        self::assertEquals('', $obj->getFields());
    }
}
