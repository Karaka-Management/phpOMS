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

namespace phpOMS\tests\Asset;

require_once __DIR__ . '/../Autoloader.php';

use phpOMS\Asset\AssetType;

/**
 * @testdox phpOMS\tests\Asset\AssetType: Asset type
 * @internal
 */
final class AssetTypeTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox The asset type enum has the correct number of status codes
     * @group framework
     * @coversNothing
     */
    public function testEnumCount() : void
    {
        self::assertCount(3, AssetType::getConstants());
    }

    /**
     * @testdox The asset type enum has only unique values
     * @group framework
     * @coversNothing
     */
    public function testUnique() : void
    {
        self::assertEquals(AssetType::getConstants(), \array_unique(AssetType::getConstants()));
    }

    /**
     * @testdox The asset type enum has the correct values
     * @group framework
     * @coversNothing
     */
    public function testEnums() : void
    {
        self::assertEquals(0, AssetType::CSS);
        self::assertEquals(1, AssetType::JS);
        self::assertEquals(2, AssetType::JSLATE);
    }
}
