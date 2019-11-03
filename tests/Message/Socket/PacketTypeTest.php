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

namespace phpOMS\tests\Message\Socket;

use phpOMS\Message\Socket\PacketType;

/**
 * @internal
 */
class PacketTypeTest extends \PHPUnit\Framework\TestCase
{
    public function testEnums() : void
    {
        self::assertCount(11, PacketType::getConstants());
        self::assertEquals(PacketType::getConstants(), \array_unique(PacketType::getConstants()));
        self::assertEquals(0, PacketType::CONNECT);
        self::assertEquals(1, PacketType::DISCONNECT);
        self::assertEquals(2, PacketType::KICK);
        self::assertEquals(3, PacketType::PING);
        self::assertEquals(4, PacketType::HELP);
        self::assertEquals(5, PacketType::RESTART);
        self::assertEquals(6, PacketType::MSG);
        self::assertEquals(7, PacketType::LOGIN);
        self::assertEquals(8, PacketType::LOGOUT);
        self::assertEquals(9, PacketType::CMD);
        self::assertEquals(10, PacketType::DOWNLOAD);
    }
}
