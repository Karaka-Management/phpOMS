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

namespace phpOMS\tests\Utils\RnG;

use phpOMS\Utils\RnG\Phone;

class PhoneTest extends \PHPUnit\Framework\TestCase
{
    public function testRnG() : void
    {
        self::assertRegExp('/^\+\d{1,2} \(\d{3,4}\) \d{3,5}\-\d{3,8}$/', Phone::generatePhone());
    }
}
