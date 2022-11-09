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

namespace phpOMS\tests\Localization\Defaults;

require_once __DIR__ . '/../../Autoloader.php';

use phpOMS\Localization\Defaults\Language;

/**
 * @testdox phpOMS\tests\Localization\Defaults\LanguageTest: Language database model
 *
 * @internal
 */
final class LanguageTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox The model has the expected member variables and default values
     * @covers phpOMS\Localization\Defaults\Language
     * @group framework
     */
    public function testDefaults() : void
    {
        $obj = new Language();
        self::assertEquals(0, $obj->getId());
        self::assertEquals('', $obj->getName());
        self::assertEquals('', $obj->getNative());
        self::assertEquals('', $obj->getCode2());
        self::assertEquals('', $obj->getCode3());
        self::assertEquals('', $obj->getCode3Native());
    }
}
