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

namespace phpOMS\tests\Socket\Client;

use phpOMS\Account\Account;
use phpOMS\Socket\Client\ClientConnection;
use phpOMS\Socket\Client\NullClientConnection;

/**
 * @internal
 */
final class NullClientConnectionTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers phpOMS\Socket\Client\NullClientConnection
     * @group framework
     */
    public function testDefault() : void
    {
        self::assertInstanceOf(ClientConnection::class, new NullClientConnection(new Account(), null));
    }
}
