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

namespace phpOMS\tests\Stdlib\Base;

use phpOMS\Stdlib\Base\AddressType;

class AddressTypeTest extends \PHPUnit\Framework\TestCase
{
    public function testEnums()
    {
        self::assertEquals(7, \count(AddressType::getconstants()));
        self::assertEquals(1, AddressType::HOME);
        self::assertEquals(2, AddressType::BUSINESS);
        self::assertEquals(3, AddressType::SHIPPING);
        self::assertEquals(4, AddressType::BILLING);
        self::assertEquals(5, AddressType::WORK);
        self::assertEquals(6, AddressType::CONTRACT);
        self::assertEquals(7, AddressType::OTHER);
    }
}
