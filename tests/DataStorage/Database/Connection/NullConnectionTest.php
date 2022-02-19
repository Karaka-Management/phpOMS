<?php
/**
 * Karaka
 *
 * PHP Version 8.0
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://karaka.app
 */
declare(strict_types=1);

namespace phpOMS\tests\DataStorage\Database\Connection;

use phpOMS\DataStorage\Database\Connection\NullConnection;
use phpOMS\DataStorage\Database\DatabaseType;

/**
 * @testdox phpOMS\tests\DataStorage\Database\Connection\NullConnectionTest: Null connection
 *
 * @internal
 */
final class NullConnectionTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox A null connection can be created as placeholder
     * @covers phpOMS\DataStorage\Database\Connection\NullConnection<extended>
     * @group framework
     */
    public function testConnect() : void
    {
        $null = new NullConnection([]);
        $null->connect();

        self::assertEquals(DatabaseType::UNDEFINED, $null->getType());
    }
}
