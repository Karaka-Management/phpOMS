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

namespace phpOMS\tests\Utils\IO\Spreadsheet;

use phpOMS\DataStorage\Database\Connection\SQLiteConnection;
use phpOMS\DataStorage\Database\Query\Builder;
use phpOMS\tests\Autoloader;
use phpOMS\Utils\IO\Spreadsheet\SpreadsheetDatabaseMapper;
use phpOMS\Utils\StringUtils;

/**
 * @testdox phpOMS\tests\Utils\IO\Spreadsheet\SpreadsheetDatabaseMapperTest: Spreadsheet database mapper
 *
 * @internal
 */
final class SpreadsheetDatabaseMapperTest extends \PHPUnit\Framework\TestCase
{
    protected $sqlite;

    public static function setUpBeforeClass() : void
    {
        Autoloader::addPath(__DIR__ . '/../../../../../Resources/');
        Autoloader::addPath(__DIR__ . '/../../../../Resources/');
        Autoloader::addPath(__DIR__ . '/../../../../MainRepository/Resources/');
    }

    /**
     * {@inheritdoc}
     */
    protected function setUp() : void
    {
        if (!\extension_loaded('pdo_sqlite')) {
            $this->markTestSkipped(
              'The SQLite extension is not available.'
            );

            return;
        }

        if (\is_file(__DIR__ . '/spreadsheet.db')) {
            \unlink(__DIR__ . '/spreadsheet.db');
        }

        \copy(__DIR__ . '/backup.db', __DIR__ . '/spreadsheet.db');

        $this->sqlite = new SQLiteConnection(['db' => 'sqlite', 'database' => __DIR__ . '/spreadsheet.db']);
        $this->sqlite->connect();
    }

    protected function tearDown() : void
    {
        if (\is_file(__DIR__ . '/spreadsheet.db')) {
            \unlink(__DIR__ . '/spreadsheet.db');
        }

        $this->sqlite->close();
    }

    /**
     * @testdox Data can be inserted into a database from an ods files
     * @covers \phpOMS\Utils\IO\Spreadsheet\SpreadsheetDatabaseMapper
     * @group framework
     */
    public function testInsertOds() : void
    {
        $mapper = new SpreadsheetDatabaseMapper($this->sqlite, __DIR__ . '/insert.ods');
        $mapper->insert();

        $builder = new Builder($this->sqlite, true);
        $data    = $builder->select('insert_1.*')->from('insert_1')->execute()?->fetchAll(\PDO::FETCH_ASSOC) ?? [];
        self::assertEquals(
            [
                ['id' => 1, 'int' => 2, 'decimal' => 2.0, 'bool' => 1, 'varchar' => 'Line 1', 'datetime' => '43631'],
                ['id' => 2, 'int' => 4, 'decimal' => 2.1, 'bool' => 0, 'varchar' => 'Line 2', 'datetime' => '42170'],
                ['id' => 3, 'int' => 6, 'decimal' => 2.12, 'bool' => 1, 'varchar' => 'Line 3', 'datetime' => '40426'],
                ['id' => 4, 'int' => 8, 'decimal' => 2.123, 'bool' => 0, 'varchar' => 'Line 4', 'datetime' => '40428'],
            ],
            $data
        );

        $builder = new Builder($this->sqlite, true);
        $data    = $builder->select('insert_2.*')->from('insert_2')->execute()?->fetchAll(\PDO::FETCH_ASSOC) ?? [];
        self::assertEquals(
            [
                ['id' => 1, 'int' => 2, 'decimal' => 2.0, 'bool' => 1, 'varchar' => 'Line 1', 'datetime' => '43631'],
                ['id' => 2, 'int' => 4, 'decimal' => 2.1, 'bool' => 0, 'varchar' => 'Line 2', 'datetime' => '42170'],
                ['id' => 3, 'int' => 6, 'decimal' => 2.12, 'bool' => 1, 'varchar' => 'Line 3', 'datetime' => '40426'],
                ['id' => 4, 'int' => 8, 'decimal' => 2.123, 'bool' => 0, 'varchar' => 'Line 4', 'datetime' => '40428'],
            ],
            $data
        );
    }

    /**
     * @testdox Data can be inserted into a database from a xls files
     * @covers \phpOMS\Utils\IO\Spreadsheet\SpreadsheetDatabaseMapper::insert
     * @group framework
     */
    public function testInsertXls() : void
    {
        $mapper = new SpreadsheetDatabaseMapper($this->sqlite, __DIR__ . '/insert.xls');
        $mapper->insert();

        $builder = new Builder($this->sqlite, true);
        $data    = $builder->select('insert_1.*')->from('insert_1')->execute()?->fetchAll(\PDO::FETCH_ASSOC) ?? [];
        self::assertEquals(
            [
                ['id' => 1, 'int' => 2, 'decimal' => 2.0, 'bool' => 1, 'varchar' => 'Line 1', 'datetime' => '43631'],
                ['id' => 2, 'int' => 4, 'decimal' => 2.1, 'bool' => 0, 'varchar' => 'Line 2', 'datetime' => '42170'],
                ['id' => 3, 'int' => 6, 'decimal' => 2.12, 'bool' => 1, 'varchar' => 'Line 3', 'datetime' => '40426'],
                ['id' => 4, 'int' => 8, 'decimal' => 2.123, 'bool' => 0, 'varchar' => 'Line 4', 'datetime' => '40428'],
            ],
            $data
        );

        $builder = new Builder($this->sqlite, true);
        $data    = $builder->select('insert_2.*')->from('insert_2')->execute()?->fetchAll(\PDO::FETCH_ASSOC) ?? [];
        self::assertEquals(
            [
                ['id' => 1, 'int' => 2, 'decimal' => 2.0, 'bool' => 1, 'varchar' => 'Line 1', 'datetime' => '43631'],
                ['id' => 2, 'int' => 4, 'decimal' => 2.1, 'bool' => 0, 'varchar' => 'Line 2', 'datetime' => '42170'],
                ['id' => 3, 'int' => 6, 'decimal' => 2.12, 'bool' => 1, 'varchar' => 'Line 3', 'datetime' => '40426'],
                ['id' => 4, 'int' => 8, 'decimal' => 2.123, 'bool' => 0, 'varchar' => 'Line 4', 'datetime' => '40428'],
            ],
            $data
        );
    }

    /**
     * @testdox Data can be inserted into a database from a xlsx files
     * @covers \phpOMS\Utils\IO\Spreadsheet\SpreadsheetDatabaseMapper::insert
     * @group framework
     */
    public function testInsertXlsx() : void
    {
        $mapper = new SpreadsheetDatabaseMapper($this->sqlite, __DIR__ . '/insert.xlsx');
        $mapper->insert();

        $builder = new Builder($this->sqlite, true);
        $data    = $builder->select('insert_1.*')->from('insert_1')->execute()?->fetchAll(\PDO::FETCH_ASSOC) ?? [];
        self::assertEquals(
            [
                ['id' => 1, 'int' => 2, 'decimal' => 2.0, 'bool' => 1, 'varchar' => 'Line 1', 'datetime' => '43631'],
                ['id' => 2, 'int' => 4, 'decimal' => 2.1, 'bool' => 0, 'varchar' => 'Line 2', 'datetime' => '42170'],
                ['id' => 3, 'int' => 6, 'decimal' => 2.12, 'bool' => 1, 'varchar' => 'Line 3', 'datetime' => '40426'],
                ['id' => 4, 'int' => 8, 'decimal' => 2.123, 'bool' => 0, 'varchar' => 'Line 4', 'datetime' => '40428'],
            ],
            $data
        );

        $builder = new Builder($this->sqlite, true);
        $data    = $builder->select('insert_2.*')->from('insert_2')->execute()?->fetchAll(\PDO::FETCH_ASSOC) ?? [];
        self::assertEquals(
            [
                ['id' => 1, 'int' => 2, 'decimal' => 2.0, 'bool' => 1, 'varchar' => 'Line 1', 'datetime' => '43631'],
                ['id' => 2, 'int' => 4, 'decimal' => 2.1, 'bool' => 0, 'varchar' => 'Line 2', 'datetime' => '42170'],
                ['id' => 3, 'int' => 6, 'decimal' => 2.12, 'bool' => 1, 'varchar' => 'Line 3', 'datetime' => '40426'],
                ['id' => 4, 'int' => 8, 'decimal' => 2.123, 'bool' => 0, 'varchar' => 'Line 4', 'datetime' => '40428'],
            ],
            $data
        );
    }

    /**
     * @testdox Data can be updated in a database from an ods files
     * @covers \phpOMS\Utils\IO\Spreadsheet\SpreadsheetDatabaseMapper::update
     * @group framework
     */
    public function testUpdateOds() : void
    {
        $mapper = new SpreadsheetDatabaseMapper($this->sqlite, __DIR__ . '/insert.ods');
        $mapper->insert();

        $builder = new Builder($this->sqlite, true);
        $data    = $builder->select('insert_1.*')->from('insert_1')->execute()?->fetchAll(\PDO::FETCH_ASSOC) ?? [];
        self::assertEquals(
            [
                ['id' => 1, 'int' => 2, 'decimal' => 2.0, 'bool' => 1, 'varchar' => 'Line 1', 'datetime' => '43631'],
                ['id' => 2, 'int' => 4, 'decimal' => 2.1, 'bool' => 0, 'varchar' => 'Line 2', 'datetime' => '42170'],
                ['id' => 3, 'int' => 6, 'decimal' => 2.12, 'bool' => 1, 'varchar' => 'Line 3', 'datetime' => '40426'],
                ['id' => 4, 'int' => 8, 'decimal' => 2.123, 'bool' => 0, 'varchar' => 'Line 4', 'datetime' => '40428'],
            ],
            $data
        );

        $builder = new Builder($this->sqlite, true);
        $data    = $builder->select('insert_2.*')->from('insert_2')->execute()?->fetchAll(\PDO::FETCH_ASSOC) ?? [];
        self::assertEquals(
            [
                ['id' => 1, 'int' => 2, 'decimal' => 2.0, 'bool' => 1, 'varchar' => 'Line 1', 'datetime' => '43631'],
                ['id' => 2, 'int' => 4, 'decimal' => 2.1, 'bool' => 0, 'varchar' => 'Line 2', 'datetime' => '42170'],
                ['id' => 3, 'int' => 6, 'decimal' => 2.12, 'bool' => 1, 'varchar' => 'Line 3', 'datetime' => '40426'],
                ['id' => 4, 'int' => 8, 'decimal' => 2.123, 'bool' => 0, 'varchar' => 'Line 4', 'datetime' => '40428'],
            ],
            $data
        );

        $mapper = new SpreadsheetDatabaseMapper($this->sqlite, __DIR__ . '/update.ods');
        $mapper->update();

        $builder = new Builder($this->sqlite, true);
        $data    = $builder->select('insert_1.*')->from('insert_1')->execute()?->fetchAll(\PDO::FETCH_ASSOC) ?? [];
        self::assertEquals(
            [
                ['id' => 1, 'int' => 2, 'decimal' => 2.0, 'bool' => 1, 'varchar' => 'Line 1', 'datetime' => '43631'],
                ['id' => 2, 'int' => 4, 'decimal' => 9.1, 'bool' => 1, 'varchar' => 'Line 2 updated', 'datetime' => '43831'],
                ['id' => 3, 'int' => 6, 'decimal' => 2.12, 'bool' => 1, 'varchar' => 'Line 3', 'datetime' => '40426'],
                ['id' => 4, 'int' => 8, 'decimal' => 9.123, 'bool' => 0, 'varchar' => 'Line 4 updated', 'datetime' => '43831'],
            ],
            $data
        );

        $builder = new Builder($this->sqlite, true);
        $data    = $builder->select('insert_2.*')->from('insert_2')->execute()?->fetchAll(\PDO::FETCH_ASSOC) ?? [];
        self::assertEquals(
            [
                ['id' => 1, 'int' => 2, 'decimal' => 2.0, 'bool' => 1, 'varchar' => 'Line 1', 'datetime' => '43631'],
                ['id' => 2, 'int' => 4, 'decimal' => 9.1, 'bool' => 1, 'varchar' => 'Line 2 updated', 'datetime' => '43831'],
                ['id' => 3, 'int' => 6, 'decimal' => 2.12, 'bool' => 1, 'varchar' => 'Line 3', 'datetime' => '40426'],
                ['id' => 4, 'int' => 8, 'decimal' => 9.123, 'bool' => 0, 'varchar' => 'Line 4 updated', 'datetime' => '43831'],
            ],
            $data
        );
    }

    /**
     * @testdox Data can be updated in a database from a xls files
     * @covers \phpOMS\Utils\IO\Spreadsheet\SpreadsheetDatabaseMapper::update
     * @group framework
     */
    public function testUpdateXls() : void
    {
        $mapper = new SpreadsheetDatabaseMapper($this->sqlite, __DIR__ . '/insert.xls');
        $mapper->insert();

        $builder = new Builder($this->sqlite, true);
        $data    = $builder->select('insert_1.*')->from('insert_1')->execute()?->fetchAll(\PDO::FETCH_ASSOC) ?? [];
        self::assertEquals(
            [
                ['id' => 1, 'int' => 2, 'decimal' => 2.0, 'bool' => 1, 'varchar' => 'Line 1', 'datetime' => '43631'],
                ['id' => 2, 'int' => 4, 'decimal' => 2.1, 'bool' => 0, 'varchar' => 'Line 2', 'datetime' => '42170'],
                ['id' => 3, 'int' => 6, 'decimal' => 2.12, 'bool' => 1, 'varchar' => 'Line 3', 'datetime' => '40426'],
                ['id' => 4, 'int' => 8, 'decimal' => 2.123, 'bool' => 0, 'varchar' => 'Line 4', 'datetime' => '40428'],
            ],
            $data
        );

        $builder = new Builder($this->sqlite, true);
        $data    = $builder->select('insert_2.*')->from('insert_2')->execute()?->fetchAll(\PDO::FETCH_ASSOC) ?? [];
        self::assertEquals(
            [
                ['id' => 1, 'int' => 2, 'decimal' => 2.0, 'bool' => 1, 'varchar' => 'Line 1', 'datetime' => '43631'],
                ['id' => 2, 'int' => 4, 'decimal' => 2.1, 'bool' => 0, 'varchar' => 'Line 2', 'datetime' => '42170'],
                ['id' => 3, 'int' => 6, 'decimal' => 2.12, 'bool' => 1, 'varchar' => 'Line 3', 'datetime' => '40426'],
                ['id' => 4, 'int' => 8, 'decimal' => 2.123, 'bool' => 0, 'varchar' => 'Line 4', 'datetime' => '40428'],
            ],
            $data
        );

        $mapper = new SpreadsheetDatabaseMapper($this->sqlite, __DIR__ . '/update.xls');
        $mapper->update();

        $builder = new Builder($this->sqlite, true);
        $data    = $builder->select('insert_1.*')->from('insert_1')->execute()?->fetchAll(\PDO::FETCH_ASSOC) ?? [];
        self::assertEquals(
            [
                ['id' => 1, 'int' => 2, 'decimal' => 2.0, 'bool' => 1, 'varchar' => 'Line 1', 'datetime' => '43631'],
                ['id' => 2, 'int' => 4, 'decimal' => 9.1, 'bool' => 1, 'varchar' => 'Line 2 updated', 'datetime' => '43831'],
                ['id' => 3, 'int' => 6, 'decimal' => 2.12, 'bool' => 1, 'varchar' => 'Line 3', 'datetime' => '40426'],
                ['id' => 4, 'int' => 8, 'decimal' => 9.123, 'bool' => 0, 'varchar' => 'Line 4 updated', 'datetime' => '43831'],
            ],
            $data
        );

        $builder = new Builder($this->sqlite, true);
        $data    = $builder->select('insert_2.*')->from('insert_2')->execute()?->fetchAll(\PDO::FETCH_ASSOC) ?? [];
        self::assertEquals(
            [
                ['id' => 1, 'int' => 2, 'decimal' => 2.0, 'bool' => 1, 'varchar' => 'Line 1', 'datetime' => '43631'],
                ['id' => 2, 'int' => 4, 'decimal' => 9.1, 'bool' => 1, 'varchar' => 'Line 2 updated', 'datetime' => '43831'],
                ['id' => 3, 'int' => 6, 'decimal' => 2.12, 'bool' => 1, 'varchar' => 'Line 3', 'datetime' => '40426'],
                ['id' => 4, 'int' => 8, 'decimal' => 9.123, 'bool' => 0, 'varchar' => 'Line 4 updated', 'datetime' => '43831'],
            ],
            $data
        );
    }

    /**
     * @testdox Data can be updated in a database from a xlsx files
     * @covers \phpOMS\Utils\IO\Spreadsheet\SpreadsheetDatabaseMapper::update
     * @group framework
     */
    public function testUpdateXlsx() : void
    {
        $mapper = new SpreadsheetDatabaseMapper($this->sqlite, __DIR__ . '/insert.xlsx');
        $mapper->insert();

        $builder = new Builder($this->sqlite, true);
        $data    = $builder->select('insert_1.*')->from('insert_1')->execute()?->fetchAll(\PDO::FETCH_ASSOC) ?? [];
        self::assertEquals(
            [
                ['id' => 1, 'int' => 2, 'decimal' => 2.0, 'bool' => 1, 'varchar' => 'Line 1', 'datetime' => '43631'],
                ['id' => 2, 'int' => 4, 'decimal' => 2.1, 'bool' => 0, 'varchar' => 'Line 2', 'datetime' => '42170'],
                ['id' => 3, 'int' => 6, 'decimal' => 2.12, 'bool' => 1, 'varchar' => 'Line 3', 'datetime' => '40426'],
                ['id' => 4, 'int' => 8, 'decimal' => 2.123, 'bool' => 0, 'varchar' => 'Line 4', 'datetime' => '40428'],
            ],
            $data
        );

        $builder = new Builder($this->sqlite, true);
        $data    = $builder->select('insert_2.*')->from('insert_2')->execute()?->fetchAll(\PDO::FETCH_ASSOC) ?? [];
        self::assertEquals(
            [
                ['id' => 1, 'int' => 2, 'decimal' => 2.0, 'bool' => 1, 'varchar' => 'Line 1', 'datetime' => '43631'],
                ['id' => 2, 'int' => 4, 'decimal' => 2.1, 'bool' => 0, 'varchar' => 'Line 2', 'datetime' => '42170'],
                ['id' => 3, 'int' => 6, 'decimal' => 2.12, 'bool' => 1, 'varchar' => 'Line 3', 'datetime' => '40426'],
                ['id' => 4, 'int' => 8, 'decimal' => 2.123, 'bool' => 0, 'varchar' => 'Line 4', 'datetime' => '40428'],
            ],
            $data
        );

        $mapper = new SpreadsheetDatabaseMapper($this->sqlite, __DIR__ . '/update.xlsx');
        $mapper->update();

        $builder = new Builder($this->sqlite, true);
        $data    = $builder->select('insert_1.*')->from('insert_1')->execute()?->fetchAll(\PDO::FETCH_ASSOC) ?? [];
        self::assertEquals(
            [
                ['id' => 1, 'int' => 2, 'decimal' => 2.0, 'bool' => 1, 'varchar' => 'Line 1', 'datetime' => '43631'],
                ['id' => 2, 'int' => 4, 'decimal' => 9.1, 'bool' => 1, 'varchar' => 'Line 2 updated', 'datetime' => '43831'],
                ['id' => 3, 'int' => 6, 'decimal' => 2.12, 'bool' => 1, 'varchar' => 'Line 3', 'datetime' => '40426'],
                ['id' => 4, 'int' => 8, 'decimal' => 9.123, 'bool' => 0, 'varchar' => 'Line 4 updated', 'datetime' => '43831'],
            ],
            $data
        );

        $builder = new Builder($this->sqlite, true);
        $data    = $builder->select('insert_2.*')->from('insert_2')->execute()?->fetchAll(\PDO::FETCH_ASSOC) ?? [];
        self::assertEquals(
            [
                ['id' => 1, 'int' => 2, 'decimal' => 2.0, 'bool' => 1, 'varchar' => 'Line 1', 'datetime' => '43631'],
                ['id' => 2, 'int' => 4, 'decimal' => 9.1, 'bool' => 1, 'varchar' => 'Line 2 updated', 'datetime' => '43831'],
                ['id' => 3, 'int' => 6, 'decimal' => 2.12, 'bool' => 1, 'varchar' => 'Line 3', 'datetime' => '40426'],
                ['id' => 4, 'int' => 8, 'decimal' => 9.123, 'bool' => 0, 'varchar' => 'Line 4 updated', 'datetime' => '43831'],
            ],
            $data
        );
    }

    /**
     * @testdox Data can be inserted into an ods files from a database
     * @covers \phpOMS\Utils\IO\Spreadsheet\SpreadsheetDatabaseMapper::select
     * @group framework
     */
    public function testSelectOds() : void
    {
        if (\is_file(__DIR__ . '/select.ods')) {
            \unlink(__DIR__ . '/select.ods');
        }

        $mapper = new SpreadsheetDatabaseMapper($this->sqlite, __DIR__ . '/insert.ods');
        $mapper->insert();

        $builder = new Builder($this->sqlite, true);
        $data    = $builder->select('insert_1.*')->from('insert_1')->execute()?->fetchAll(\PDO::FETCH_ASSOC) ?? [];
        self::assertEquals(
            [
                ['id' => 1, 'int' => 2, 'decimal' => 2.0, 'bool' => 1, 'varchar' => 'Line 1', 'datetime' => '43631'],
                ['id' => 2, 'int' => 4, 'decimal' => 2.1, 'bool' => 0, 'varchar' => 'Line 2', 'datetime' => '42170'],
                ['id' => 3, 'int' => 6, 'decimal' => 2.12, 'bool' => 1, 'varchar' => 'Line 3', 'datetime' => '40426'],
                ['id' => 4, 'int' => 8, 'decimal' => 2.123, 'bool' => 0, 'varchar' => 'Line 4', 'datetime' => '40428'],
            ],
            $data
        );

        $builder = new Builder($this->sqlite, true);
        $data    = $builder->select('insert_2.*')->from('insert_2')->execute()?->fetchAll(\PDO::FETCH_ASSOC) ?? [];
        self::assertEquals(
            [
                ['id' => 1, 'int' => 2, 'decimal' => 2.0, 'bool' => 1, 'varchar' => 'Line 1', 'datetime' => '43631'],
                ['id' => 2, 'int' => 4, 'decimal' => 2.1, 'bool' => 0, 'varchar' => 'Line 2', 'datetime' => '42170'],
                ['id' => 3, 'int' => 6, 'decimal' => 2.12, 'bool' => 1, 'varchar' => 'Line 3', 'datetime' => '40426'],
                ['id' => 4, 'int' => 8, 'decimal' => 2.123, 'bool' => 0, 'varchar' => 'Line 4', 'datetime' => '40428'],
            ],
            $data
        );

        $mapper = new SpreadsheetDatabaseMapper($this->sqlite, __DIR__ . '/select.ods');

        $builder = new Builder($this->sqlite, true);
        $data    = $builder->select('int', 'decimal', 'bool', 'varchar', 'datetime')->from('insert_1');

        $mapper->select([$builder]);

        self::assertTrue($this->compareSelectInsertSheet(__DIR__ . '/select.ods', __DIR__ . '/insert.ods'));

        if (\is_file(__DIR__ . '/select.ods')) {
            \unlink(__DIR__ . '/select.ods');
        }
    }

    /**
     * @testdox Data can be inserted into a xls files from a database
     * @covers \phpOMS\Utils\IO\Spreadsheet\SpreadsheetDatabaseMapper::select
     * @group framework
     */
    public function testSelectXls() : void
    {
        if (\is_file(__DIR__ . '/select.xls')) {
            \unlink(__DIR__ . '/select.xls');
        }

        $mapper = new SpreadsheetDatabaseMapper($this->sqlite, __DIR__ . '/insert.xls');
        $mapper->insert();

        $builder = new Builder($this->sqlite, true);
        $data    = $builder->select('insert_1.*')->from('insert_1')->execute()?->fetchAll(\PDO::FETCH_ASSOC) ?? [];
        self::assertEquals(
            [
                ['id' => 1, 'int' => 2, 'decimal' => 2.0, 'bool' => 1, 'varchar' => 'Line 1', 'datetime' => '43631'],
                ['id' => 2, 'int' => 4, 'decimal' => 2.1, 'bool' => 0, 'varchar' => 'Line 2', 'datetime' => '42170'],
                ['id' => 3, 'int' => 6, 'decimal' => 2.12, 'bool' => 1, 'varchar' => 'Line 3', 'datetime' => '40426'],
                ['id' => 4, 'int' => 8, 'decimal' => 2.123, 'bool' => 0, 'varchar' => 'Line 4', 'datetime' => '40428'],
            ],
            $data
        );

        $builder = new Builder($this->sqlite, true);
        $data    = $builder->select('insert_2.*')->from('insert_2')->execute()?->fetchAll(\PDO::FETCH_ASSOC) ?? [];
        self::assertEquals(
            [
                ['id' => 1, 'int' => 2, 'decimal' => 2.0, 'bool' => 1, 'varchar' => 'Line 1', 'datetime' => '43631'],
                ['id' => 2, 'int' => 4, 'decimal' => 2.1, 'bool' => 0, 'varchar' => 'Line 2', 'datetime' => '42170'],
                ['id' => 3, 'int' => 6, 'decimal' => 2.12, 'bool' => 1, 'varchar' => 'Line 3', 'datetime' => '40426'],
                ['id' => 4, 'int' => 8, 'decimal' => 2.123, 'bool' => 0, 'varchar' => 'Line 4', 'datetime' => '40428'],
            ],
            $data
        );

        $mapper = new SpreadsheetDatabaseMapper($this->sqlite, __DIR__ . '/select.xls');

        $builder = new Builder($this->sqlite, true);
        $data    = $builder->select('int', 'decimal', 'bool', 'varchar', 'datetime')->from('insert_1');

        $mapper->select([$builder]);

        self::assertTrue($this->compareSelectInsertSheet(__DIR__ . '/select.xls', __DIR__ . '/insert.xls'));

        if (\is_file(__DIR__ . '/select.xls')) {
            \unlink(__DIR__ . '/select.xls');
        }
    }

    /**
     * @testdox Data can be inserted into a xlsx files from a database
     * @covers \phpOMS\Utils\IO\Spreadsheet\SpreadsheetDatabaseMapper::select
     * @group framework
     */
    public function testSelectXlsx() : void
    {
        if (\is_file(__DIR__ . '/select.xlsx')) {
            \unlink(__DIR__ . '/select.xlsx');
        }

        $mapper = new SpreadsheetDatabaseMapper($this->sqlite, __DIR__ . '/insert.xlsx');
        $mapper->insert();

        $builder = new Builder($this->sqlite, true);
        $data    = $builder->select('insert_1.*')->from('insert_1')->execute()?->fetchAll(\PDO::FETCH_ASSOC) ?? [];
        self::assertEquals(
            [
                ['id' => 1, 'int' => 2, 'decimal' => 2.0, 'bool' => 1, 'varchar' => 'Line 1', 'datetime' => '43631'],
                ['id' => 2, 'int' => 4, 'decimal' => 2.1, 'bool' => 0, 'varchar' => 'Line 2', 'datetime' => '42170'],
                ['id' => 3, 'int' => 6, 'decimal' => 2.12, 'bool' => 1, 'varchar' => 'Line 3', 'datetime' => '40426'],
                ['id' => 4, 'int' => 8, 'decimal' => 2.123, 'bool' => 0, 'varchar' => 'Line 4', 'datetime' => '40428'],
            ],
            $data
        );

        $builder = new Builder($this->sqlite, true);
        $data    = $builder->select('insert_2.*')->from('insert_2')->execute()?->fetchAll(\PDO::FETCH_ASSOC) ?? [];
        self::assertEquals(
            [
                ['id' => 1, 'int' => 2, 'decimal' => 2.0, 'bool' => 1, 'varchar' => 'Line 1', 'datetime' => '43631'],
                ['id' => 2, 'int' => 4, 'decimal' => 2.1, 'bool' => 0, 'varchar' => 'Line 2', 'datetime' => '42170'],
                ['id' => 3, 'int' => 6, 'decimal' => 2.12, 'bool' => 1, 'varchar' => 'Line 3', 'datetime' => '40426'],
                ['id' => 4, 'int' => 8, 'decimal' => 2.123, 'bool' => 0, 'varchar' => 'Line 4', 'datetime' => '40428'],
            ],
            $data
        );

        $mapper = new SpreadsheetDatabaseMapper($this->sqlite, __DIR__ . '/select.xlsx');

        $builder = new Builder($this->sqlite, true);
        $data    = $builder->select('int', 'decimal', 'bool', 'varchar', 'datetime')->from('insert_1');

        $mapper->select([$builder]);

        self::assertTrue($this->compareSelectInsertSheet(__DIR__ . '/select.xlsx', __DIR__ . '/insert.xlsx'));

        if (\is_file(__DIR__ . '/select.xlsx')) {
            \unlink(__DIR__ . '/select.xlsx');
        }
    }

    /**
     * @coversNothing
     */
    private function compareSelectInsertSheet(string $pathSelect, string $pathInsert) : bool
    {
        $reader1 = null;
        if (StringUtils::endsWith($pathSelect, '.xlsx')) {
            $reader1 = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        } elseif (StringUtils::endsWith($pathSelect, '.ods')) {
            $reader1 = new \PhpOffice\PhpSpreadsheet\Reader\Ods();
        } else {
            $reader1 = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
        }

        $reader2 = null;
        if (StringUtils::endsWith($pathInsert, '.xlsx')) {
            $reader2 = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        } elseif (StringUtils::endsWith($pathInsert, '.ods')) {
            $reader2 = new \PhpOffice\PhpSpreadsheet\Reader\Ods();
        } else {
            $reader2 = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
        }

        $reader1->setReadDataOnly(true);
        $reader2->setReadDataOnly(true);

        $sheet1 = $reader1->load($pathSelect);
        $sheet2 = $reader2->load($pathInsert);

        $tables = $sheet1->getSheetCount();
        for ($i = 0; $i < $tables; ++$i) {
            $sheet1->setActiveSheetIndex($i);
            $sheet2->setActiveSheetIndex($i);

            $workSheet1 = $sheet1->getSheet($i);
            $workSheet2 = $sheet2->getSheet($i);

            $titles = [];

            // get column titles
            $column = 1;
            while (!empty($value = $workSheet1->getCellByColumnAndRow($column, 1)->getCalculatedValue())) {
                $titles[] = $value;
                ++$column;
            }

            $columns = \count($titles);

            $line = 1;
            while (!empty($row = $workSheet1->getCellByColumnAndRow(1, $line)->getCalculatedValue())) {
                for ($j = 1; $j <= $columns; ++$j) {
                    if (($v1 = $workSheet1->getCellByColumnAndRow($j, $line)->getCalculatedValue()) != ($v2 = $workSheet2->getCellByColumnAndRow($j, $line)->getCalculatedValue())) {
                        return false;
                    }
                }

                ++$line;
            }
        }

        return true;
    }
}
