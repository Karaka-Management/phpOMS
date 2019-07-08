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
 * @link       https://orange-management.org
 */
 declare(strict_types=1);

namespace phpOMS\tests\DataStorage\Database\Connection;

use phpOMS\DataStorage\Database\Connection\NullConnection;
use phpOMS\DataStorage\Database\DatabaseType;

/**
 * @internal
 */
class NullConnectionTest extends \PHPUnit\Framework\TestCase
{
    public function testConnect() : void
    {
        $null = new NullConnection([]);
        $null->connect();

        self::assertEquals(DatabaseType::UNDEFINED, $null->getType());
    }
}
