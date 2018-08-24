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

namespace phpOMS\tests\Asset;

require_once __DIR__ . '/../Autoloader.php';

use phpOMS\Asset\AssetType;

class AssetTypeTest extends \PHPUnit\Framework\TestCase
{
    public function testEnums()
    {
        self::assertEquals(3, \count(AssetType::getConstants()));
        self::assertEquals(0, AssetType::CSS);
        self::assertEquals(1, AssetType::JS);
        self::assertEquals(2, AssetType::JSLATE);
    }
}
