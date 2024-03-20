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

namespace phpOMS\tests\DataStorage\Database\Connection;

use phpOMS\DataStorage\Database\Connection\NullConnection;
use phpOMS\DataStorage\Database\DatabaseType;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\DataStorage\Database\Connection\NullConnection::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\DataStorage\Database\Connection\NullConnectionTest: Null connection')]
final class NullConnectionTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A null connection can be created as placeholder')]
    public function testConnect() : void
    {
        $null = new NullConnection([]);
        $null->connect();

        self::assertEquals(DatabaseType::UNDEFINED, $null->getType());
    }
}
