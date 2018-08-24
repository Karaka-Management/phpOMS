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

namespace phpOMS\tests\Stdlib\Map;

use phpOMS\Stdlib\Map\OrderType;

class OrderTypeTest extends \PHPUnit\Framework\TestCase
{
    public function testEnums()
    {
        self::assertEquals(2, \count(OrderType::getConstants()));
        self::assertEquals(0, OrderType::LOOSE);
        self::assertEquals(1, OrderType::STRICT);
    }
}
