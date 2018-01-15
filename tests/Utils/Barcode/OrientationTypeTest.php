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

namespace phpOMS\tests\Utils\Barcode;


use phpOMS\Utils\Barcode\OrientationType;

class OrientationTypeTest extends \PHPUnit\Framework\TestCase
{
    public function testEnums()
    {
        self::assertEquals(2, count(OrientationType::getConstants()));
        self::assertEquals(OrientationType::getConstants(), array_unique(OrientationType::getConstants()));
        
        self::assertEquals(0, OrientationType::HORIZONTAL);
        self::assertEquals(1, OrientationType::VERTICAL);
    }
}
