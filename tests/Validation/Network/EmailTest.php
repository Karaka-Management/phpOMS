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

namespace phpOMS\tests\Validation\Network;

use phpOMS\Validation\Network\Email;

/**
 * @internal
 */
class EmailTest extends \PHPUnit\Framework\TestCase
{
    public function testValidation() : void
    {
        self::assertTrue(Email::isValid('test.string@email.com'));
        self::assertFalse(Email::isValid('test.string@email'));
        self::assertTrue(Email::isValid('test.string+1234@email.com'));
    }
}
