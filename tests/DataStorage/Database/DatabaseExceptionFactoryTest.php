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

namespace phpOMS\tests\DataStorage\Database;

use phpOMS\DataStorage\Database\DatabaseExceptionFactory;

class DatabaseExceptionFactoryTest extends \PHPUnit\Framework\TestCase
{
    public function testException()
    {
        self::assertEquals('\PDOException', DatabaseExceptionFactory::createException(new \PDOException()));
    }

    public function testExceptionMessage()
    {
        self::assertEquals('', DatabaseExceptionFactory::createExceptionMessage(new \PDOException()));
    }
}
