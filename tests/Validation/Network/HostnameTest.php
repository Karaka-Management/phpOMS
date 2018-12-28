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

use phpOMS\Validation\Network\Hostname;

class HostnameTest extends \PHPUnit\Framework\TestCase
{
    public function testHostname() : void
    {
        self::assertTrue(Hostname::isValid('test.com'));
        self::assertFalse(Hostname::isValid('http://test.com'));
        self::assertFalse(Hostname::isValid('test.com/test?something=a'));
        self::assertFalse(Hostname::isValid('//somethign/wrong'));
    }
}
