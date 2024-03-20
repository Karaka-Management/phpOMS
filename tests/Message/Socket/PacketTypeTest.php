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

namespace phpOMS\tests\Message\Socket;

use phpOMS\Message\Socket\PacketType;

/**
 * @internal
 */
final class PacketTypeTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\CoversNothing]
    public function testEnumCount() : void
    {
        self::assertCount(11, PacketType::getConstants());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\CoversNothing]
    public function testUnique() : void
    {
        self::assertEquals(PacketType::getConstants(), \array_unique(PacketType::getConstants()));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\CoversNothing]
    public function testEnums() : void
    {
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
