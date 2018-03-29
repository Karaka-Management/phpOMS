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

namespace phpOMS\tests\Localization;

require_once __DIR__ . '/../Autoloader.php';

use phpOMS\Localization\NullLocalization;
use phpOMS\Localization\Localization;

class NullLocalizationTest extends \PHPUnit\Framework\TestCase
{
    public function testNullModel()
    {
        self::assertInstanceOf(Localization::class, new NullLocalization());
    }
}