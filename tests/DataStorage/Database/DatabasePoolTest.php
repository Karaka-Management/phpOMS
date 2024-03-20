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

namespace phpOMS\tests\DataStorage\Database;

use phpOMS\DataStorage\Database\Connection\MysqlConnection;
use phpOMS\DataStorage\Database\DatabasePool;
use phpOMS\DataStorage\Database\DatabaseStatus;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\DataStorage\Database\DatabasePool: Pool for database connections')]
final class DatabasePoolTest extends \PHPUnit\Framework\TestCase
{
    protected DatabasePool $dbPool;

    /**
     * {@inheritdoc}
     */
    protected function setUp() : void
    {
        $this->dbPool = new DatabasePool();
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The pool has the expected default values after initialization')]
    public function testDefault() : void
    {
        self::assertInstanceOf('\phpOMS\DataStorage\Database\Connection\NullConnection', $this->dbPool->get());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A database connection can be created by the pool')]
    public function testCreateConnection() : void
    {
        /** @var array $CONFIG */
        self::assertTrue($this->dbPool->create('core', $GLOBALS['CONFIG']['db']['core']['masters']['admin']));
        self::assertEquals($this->dbPool->get()->getStatus(), DatabaseStatus::OK);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Database connections cannot be overwritten')]
    public function testInvalidOverwrite() : void
    {
        /** @var array $CONFIG */
        self::assertTrue($this->dbPool->create('core', $GLOBALS['CONFIG']['db']['core']['masters']['admin']));
        self::assertFalse($this->dbPool->create('core', $GLOBALS['CONFIG']['db']['core']['masters']['admin']));
        self::assertFalse($this->dbPool->add('core', new MysqlConnection($GLOBALS['CONFIG']['db']['core']['masters']['admin'])));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Existing database connections can be added to the pool')]
    public function testAddConnections() : void
    {
        /** @var array $CONFIG */
        self::assertTrue($this->dbPool->add('core', new MysqlConnection($GLOBALS['CONFIG']['db']['core']['masters']['admin'])));
        self::assertInstanceOf('\phpOMS\DataStorage\Database\Connection\ConnectionAbstract', $this->dbPool->get());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Database connections can be removed from the pool')]
    public function testRemoveConnections() : void
    {
        /** @var array $CONFIG */
        self::assertTrue($this->dbPool->add('core', new MysqlConnection($GLOBALS['CONFIG']['db']['core']['masters']['admin'])));
        self::assertTrue($this->dbPool->remove('core'));
        self::assertInstanceOf('\phpOMS\DataStorage\Database\Connection\NullConnection', $this->dbPool->get());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Invalid database connections cannot be removed')]
    public function testInvalidRemove() : void
    {
        /** @var array $CONFIG */
        self::assertTrue($this->dbPool->add('core', new MysqlConnection($GLOBALS['CONFIG']['db']['core']['masters']['admin'])));
        self::assertFalse($this->dbPool->remove('cores'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The first connection added to the pool is the default connection')]
    public function testDefaultConnection() : void
    {
        /** @var array $CONFIG */
        self::assertTrue($this->dbPool->add('abc', new MysqlConnection($GLOBALS['CONFIG']['db']['core']['masters']['admin'])));
        self::assertEquals($this->dbPool->get('abc'), $this->dbPool->get());
    }
}
