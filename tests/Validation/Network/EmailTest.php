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

namespace phpOMS\tests\Validation\Network;

use phpOMS\Validation\Network\Email;

/**
 * @testdox phpOMS\tests\Validation\Network\EmailTest: Email validator
 *
 * @internal
 */
final class EmailTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox A email can be validated
     * @covers \phpOMS\Validation\Network\Email
     * @group framework
     */
    public function testValidation() : void
    {
        self::assertTrue(Email::isValid('test.string@email.com'));
        self::assertFalse(Email::isValid('test.string@email'));
        self::assertTrue(Email::isValid('test.string+1234@email.com'));
    }
}
