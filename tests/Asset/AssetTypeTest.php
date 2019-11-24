<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\tests\Asset;

require_once __DIR__ . '/../Autoloader.php';

use phpOMS\Asset\AssetType;

/**
 * @internal
 */
class AssetTypeTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @coversNothing
     */
    public function testEnumCount() : void
    {
        self::assertCount(3, AssetType::getConstants());
    }

    /**
     * @coversNothing
     */
    public function testEnums() : void
    {
        self::assertEquals(0, AssetType::CSS);
        self::assertEquals(1, AssetType::JS);
        self::assertEquals(2, AssetType::JSLATE);
    }
}
