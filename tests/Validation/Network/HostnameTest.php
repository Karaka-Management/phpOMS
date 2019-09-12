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

use phpOMS\Validation\Network\Hostname;

/**
 * @internal
 */
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
