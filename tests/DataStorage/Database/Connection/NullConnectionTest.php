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

namespace phpOMS\tests\DataStorage\Database\Connection;

use phpOMS\DataStorage\Database\Connection\NullConnection;
use phpOMS\DataStorage\Database\DatabaseType;

class NullConnectionTest extends \PHPUnit\Framework\TestCase
{
    public function testConnect()
    {
        $null = new NullConnection([]);
        $null->connect();

        self::assertEquals(DatabaseType::UNDEFINED, $null->getType());
    }
}