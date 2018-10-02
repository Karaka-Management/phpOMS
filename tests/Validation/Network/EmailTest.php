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

namespace phpOMS\tests\Validation\Network;

use phpOMS\Validation\Network\Email;

class EmailTest extends \PHPUnit\Framework\TestCase
{
    public function testValidation()
    {
        self::assertTrue(Email::isValid('test.string@email.com'));
        self::assertFalse(Email::isValid('test.string@email'));
        self::assertTrue(Email::isValid('test.string+1234@email.com'));
    }
}
