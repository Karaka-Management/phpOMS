<?php
/**
 * Orange Management
 *
 * PHP Version 8.0
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\tests\Message;

require_once __DIR__ . '/../../Autoloader.php';

use phpOMS\Message\Mail\Email;

/**
 * @testdox phpOMS\tests\Message\MailHandlerTest: Abstract mail handler
 *
 * @internal
 */
class EmailTestTest extends \PHPUnit\Framework\TestCase
{
    public function testEmailParsing() : void
    {
        self::assertEquals(
            [['name' => 'Test Name', 'address' => 'test@orange-management.org']],
            Email::parseAddresses('Test Name <test@orange-management.org>')
        );

        self::assertEquals(
            [['name' => 'Test Name', 'address' => 'test@orange-management.org']],
            Email::parseAddresses('Test Name <test@orange-management.org>', false)
        );
    }
}
