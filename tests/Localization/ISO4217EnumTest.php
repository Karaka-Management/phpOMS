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

namespace phpOMS\tests\Localization;

require_once __DIR__ . '/../Autoloader.php';

use phpOMS\Localization\ISO4217Enum;

/**
 * @testdox phpOMS\tests\Localization\ISO4217EnumTest: ISO 4217 currency codes
 * @internal
 */
final class ISO4217EnumTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox The ISO 4217 currency code enum has only unique values
     * @group framework
     * @coversNothing
     */
    public function testUnique() : void
    {
        $enum = ISO4217Enum::getConstants();
        self::assertTrue(\count($enum) >= \count(\array_unique($enum)));
    }
}
