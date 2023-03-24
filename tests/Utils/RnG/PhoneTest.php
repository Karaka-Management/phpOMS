<?php
/**
 * Karaka
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

namespace phpOMS\tests\Utils\RnG;

use phpOMS\Utils\RnG\Phone;

/**
 * @testdox phpOMS\tests\Utils\RnG\PhoneTest: Random phone number generator
 *
 * @internal
 */
final class PhoneTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox Random phone numbers can be generated
     * @covers phpOMS\Utils\RnG\Phone
     * @group framework
     */
    public function testRnG() : void
    {
        self::assertMatchesRegularExpression('/^\+\d{1,2} \(\d{3,4}\) \d{3,5}\-\d{3,8}$/', Phone::generatePhone());
    }
}
