<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\tests\Utils\RnG;

use phpOMS\Utils\RnG\Phone;

/**
 * @testdox phpOMS\tests\Utils\RnG\PhoneTest: Random phone number generator
 *
 * @internal
 */
class PhoneTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox Random phone numbers can be generated
     * @covers phpOMS\Utils\RnG\Phone
     * @group framework
     */
    public function testRnG() : void
    {
        self::assertRegExp('/^\+\d{1,2} \(\d{3,4}\) \d{3,5}\-\d{3,8}$/', Phone::generatePhone());
    }
}
