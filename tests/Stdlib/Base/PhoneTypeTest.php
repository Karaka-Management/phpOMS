<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package    tests
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       https://orange-management.org
 */
 declare(strict_types=1);

namespace phpOMS\tests\Stdlib\Base;

use phpOMS\Stdlib\Base\PhoneType;

/**
 * @internal
 */
class PhoneTypeTest extends \PHPUnit\Framework\TestCase
{
    public function testEnums() : void
    {
        self::assertCount(4, PhoneType::getConstants());
        self::assertEquals(1, PhoneType::HOME);
        self::assertEquals(2, PhoneType::BUSINESS);
        self::assertEquals(3, PhoneType::MOBILE);
        self::assertEquals(4, PhoneType::WORK);
    }
}
