<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\tests\Validation\Base;

require_once __DIR__ . '/../../Autoloader.php';

use phpOMS\Validation\Base\DateTime;

/**
 * @testdox phpOMS\tests\Validation\Base\DateTimeTest: Datetime validator
 *
 * @internal
 */
final class DateTimeTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox A date time string can be validated
     * @covers \phpOMS\Validation\Base\DateTime
     * @group framework
     */
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
