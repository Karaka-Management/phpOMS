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

use phpOMS\Stdlib\Base\PhoneType;

class PhoneTypeTest extends \PHPUnit\Framework\TestCase
{
    public function testEnums()
    {
        self::assertEquals(4, \count(PhoneType::getConstants()));
        self::assertEquals(1, PhoneType::HOME);
        self::assertEquals(2, PhoneType::BUSINESS);
        self::assertEquals(3, PhoneType::MOBILE);
        self::assertEquals(4, PhoneType::WORK);
    }
}
