<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\tests\DataStorage\Database\Schema\Grammar;

use phpOMS\DataStorage\Database\Connection\MysqlConnection;
use phpOMS\DataStorage\Database\Schema\Builder;
use phpOMS\DataStorage\Database\Schema\Grammar\MysqlGrammar;
use phpOMS\Utils\ArrayUtils;
use phpOMS\Utils\TestUtils;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\DataStorage\Database\Schema\Grammar\MysqlGrammar::class)]
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\DataStorage\Database\Schema\Builder::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\DataStorage\Database\Schema\Grammar\MysqlGrammarTest: Mysql sql schema grammar')]
final class MysqlGrammarTest extends \PHPUnit\Framework\TestCase
{
    protected MysqlConnection $con;

    /**
     * {@inheritdoc}
     */
    protected function setUp() : void
    {
        $this->con = new MysqlConnection($GLOBALS['CONFIG']['db']['core']['masters']['admin']);
        $this->con->connect();
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The grammar has the expected default values after initialization')]
    public function testDefault() : void
    {
        self::assertInstanceOf('\phpOMS\DataStorage\Database\Schema\Grammar\Grammar', new MysqlGrammar());
        self::assertEquals('`', TestUtils::getMember(new MysqlGrammar(), 'systemIdentifierStart'));
        self::assertEquals('`', TestUtils::getMember(new MysqlGrammar(), 'systemIdentifierEnd'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The the grammar correctly creates and returns a database table')]
    public function testSchemaInputOutput() : void
    {
        $definitions = \json_decode(\file_get_contents(__DIR__ . '/testSchema.json'), true);
        foreach ($definitions as $definition) {
            Builder::createFromSchema($definition, $this->con)->execute();
        }

        $table  = new Builder($this->con);
        $tables = $table->selectTables()->execute()->fetchAll(\PDO::FETCH_COLUMN);
        self::assertContains('test', $tables);
        self::assertContains('test_foreign', $tables);

        $field  = new Builder($this->con);
        $fields = $field->selectFields('test')->execute()->fetchAll();

        foreach ($definitions['test']['fields'] as $key => $field) {
            self::assertTrue(
                ArrayUtils::inArrayRecursive($key, $fields),
                'Couldn\'t find "' . $key . '" in array'
            );
        }

        $delete = new Builder($this->con);
        $delete->dropTable('test')
            ->dropTable('test_foreign')
            ->execute();
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The grammar correctly deletes a table')]
    public function testDelete() : void
    {
        $definitions = \json_decode(\file_get_contents(__DIR__ . '/testSchema.json'), true);
        foreach ($definitions as $definition) {
            Builder::createFromSchema($definition, $this->con)->execute();
        }

        $table  = new Builder($this->con);
        $tables = $table->selectTables()->execute()->fetchAll(\PDO::FETCH_COLUMN);
        self::assertContains('test', $tables);
        self::assertContains('test_foreign', $tables);

        $delete = new Builder($this->con);
        $delete->dropTable('test')
            ->dropTable('test_foreign')
            ->execute();

        $table  = new Builder($this->con);
        $tables = $table->selectTables()->execute()->fetchAll();
        self::assertNotContains('test', $tables);
        self::assertNotContains('test_foreign', $tables);
    }
}
