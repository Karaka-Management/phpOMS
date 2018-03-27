<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    TBD
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */

namespace phpOMS\tests\DataStorage\Database\Connection;

use phpOMS\DataStorage\Database\Connection\ConnectionFactory;
use phpOMS\DataStorage\Database\Connection\MysqlConnection;

class ConnectionFactoryTest extends \PHPUnit\Framework\TestCase
{
    public function testCreate()
    {
        self::assertInstanceOf(
            MysqlConnection::class,
            ConnectionFactory::create($GLOBALS['CONFIG']['db']['core']['masters']['admin'])
        );
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidDatabaseType()
    {
        ConnectionFactory::create(['db' => 'invalid']);
    }
}
