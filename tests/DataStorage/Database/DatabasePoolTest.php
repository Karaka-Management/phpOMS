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

namespace phpOMS\tests\DataStorage\Database;

use phpOMS\DataStorage\Database\Connection\MysqlConnection;
use phpOMS\DataStorage\Database\DatabasePool;
use phpOMS\DataStorage\Database\DatabaseStatus;

/**
 * @testdox phpOMS\tests\DataStorage\Database\DatabasePool: Pool for database connections
 *
 * @internal
 */
class DatabasePoolTest extends \PHPUnit\Framework\TestCase
{
    protected DatabasePool $dbPool;

    /**
     * {@inheritdoc}
     */
    protected function setUp() : void
    {
        $this->dbPool = new DatabasePool();
    }

    /**
     * @testdox The pool has the expected default values after initialization
     * @group framework
     */
    public function testDefault() : void
    {
        self::assertInstanceOf('\phpOMS\DataStorage\Database\Connection\NullConnection', $this->dbPool->get());
    }

    /**
     * @testdox A database connection can be created by the pool
     * @group framework
     */
    public function testCreateConnection() : void
    {
        /** @var array $CONFIG */
        self::assertTrue($this->dbPool->create('core', $GLOBALS['CONFIG']['db']['core']['masters']['admin']));
        $this->dbPool->get()->connect();
        self::assertEquals($this->dbPool->get()->getStatus(), DatabaseStatus::OK);
    }

    /**
     * @testdox Database connections cannot be overwritten
     * @group framework
     */
    public function testInvalidOverwrite() : void
    {
        /** @var array $CONFIG */
        self::assertTrue($this->dbPool->create('core', $GLOBALS['CONFIG']['db']['core']['masters']['admin']));
        self::assertFalse($this->dbPool->create('core', $GLOBALS['CONFIG']['db']['core']['masters']['admin']));
        self::assertFalse($this->dbPool->add('core', new MysqlConnection($GLOBALS['CONFIG']['db']['core']['masters']['admin'])));
    }

    /**
     * @testdox Existing database connections can be added to the pool
     * @group framework
     */
    public function testAddConnections() : void
    {
        /** @var array $CONFIG */
        self::assertTrue($this->dbPool->add('core', new MysqlConnection($GLOBALS['CONFIG']['db']['core']['masters']['admin'])));
        self::assertInstanceOf('\phpOMS\DataStorage\Database\Connection\ConnectionAbstract', $this->dbPool->get());
    }

    /**
     * @testdox Database connections can be removed from the pool
     * @group framework
     */
    public function testRemoveConnections() : void
    {
        /** @var array $CONFIG */
        self::assertTrue($this->dbPool->add('core', new MysqlConnection($GLOBALS['CONFIG']['db']['core']['masters']['admin'])));
        self::assertTrue($this->dbPool->remove('core'));
        self::assertInstanceOf('\phpOMS\DataStorage\Database\Connection\NullConnection', $this->dbPool->get());
    }

    /**
     * @testdox Invalid database connections cannot be removed
     * @group framework
     */
    public function testInvalidRemove() : void
    {
        /** @var array $CONFIG */
        self::assertTrue($this->dbPool->add('core', new MysqlConnection($GLOBALS['CONFIG']['db']['core']['masters']['admin'])));
        self::assertFalse($this->dbPool->remove('cores'));
    }

    /**
     * @testdox The first connection added to the pool is the default connection
     * @group framework
     */
    public function testDefaultConnection() : void
    {
        /** @var array $CONFIG */
        self::assertTrue($this->dbPool->add('abc', new MysqlConnection($GLOBALS['CONFIG']['db']['core']['masters']['admin'])));
        self::assertEquals($this->dbPool->get('abc'), $this->dbPool->get());
    }
}
