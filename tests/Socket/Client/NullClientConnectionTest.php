<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\tests\Socket\Client;

use phpOMS\Account\Account;
use phpOMS\Socket\Client\ClientConnection;
use phpOMS\Socket\Client\NullClientConnection;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Socket\Client\NullClientConnection::class)]
final class NullClientConnectionTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testDefault() : void
    {
        self::assertInstanceOf(ClientConnection::class, new NullClientConnection(new Account(), null));
    }
}
