<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    tests
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */

namespace phpOMS\tests\Validation\Base;

use phpOMS\Validation\Base\DateTime;

class DateTimeTest extends \PHPUnit\Framework\TestCase
{
    public function testDateTime() : void
    {
        self::assertTrue(DateTime::isValid('now'));
        self::assertTrue(DateTime::isValid('10 September 2000'));
        self::assertTrue(DateTime::isValid('2012-05-16'));
        self::assertTrue(DateTime::isValid('2012-05-16 22:13:01'));

        self::assertFalse(DateTime::isValid('2012-05-16 22:66:01'));
        self::assertFalse(DateTime::isValid('201M-05-16 22:66:01'));
        self::assertFalse(DateTime::isValid('2'));
        self::assertFalse(DateTime::isValid('String'));
    }
}
