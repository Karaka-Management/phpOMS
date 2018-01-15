<?php
/**
 * Orange Management
 *
 * PHP Version 7.1
 *
 * @package    TBD
 * @author     OMS Development Team <dev@oms.com>
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */

namespace phpOMS\tests\Localization;

require_once __DIR__ . '/../Autoloader.php';

use phpOMS\Localization\ISO3166NameEnum;

class ISO3166NameEnumTest extends \PHPUnit\Framework\TestCase
{
    public function testEnums()
    {
        $enum = ISO3166NameEnum::getConstants();
        self::assertEquals(count($enum), count(array_unique($enum)));
    }
}
