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

use phpOMS\Localization\ISO3166NameEnum;

/**
 * @testdox phpOMS\tests\Localization\ISO3166NameEnumTest: ISO 3166 country names
 * @internal
 */
final class ISO3166NameEnumTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox The ISO 3166 enum has only unique values
     * @group framework
     * @coversNothing
     */
    public function testUnique() : void
    {
        $enum = ISO3166NameEnum::getConstants();
        self::assertEquals(\count($enum), \count(\array_unique($enum)));
    }
}
