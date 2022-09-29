<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\DataStorage\Database\Schema\Grammar
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://karaka.app
 */
declare(strict_types=1);

namespace phpOMS\DataStorage\Database\Schema\Grammar;

use phpOMS\DataStorage\Database\BuilderAbstract;
use phpOMS\DataStorage\Database\Query\Grammar\Grammar as QueryGrammar;
use phpOMS\DataStorage\Database\Schema\QueryType;

/**
 * Database query grammar.
 *
 * @package phpOMS\DataStorage\Database\Schema\Grammar
 * @license OMS License 1.0
 * @link    https://karaka.app
 * @since   1.0.0
 */
class Grammar extends QueryGrammar
{
    /**
     * Drop components.
     *
     * @var string[]
     * @since 1.0.0
     */
    protected array $dropDatabaseComponents = [
        'dropDatabase',
    ];

    /**
     * Drop components.
     *
     * @var string[]
     * @since 1.0.0
     */
    protected array $dropTableComponents = [
        'dropTable',
    ];

    /**
     * Select tables components.
     *
     * @var string[]
     * @since 1.0.0
     */
    protected array $createTablesComponents = [
        'createTable',
        'createFields',
        'createTableSettings',
    ];

    /**
     * Select tables components.
     *
     * @var string[]
     * @since 1.0.0
     */
    protected array $tablesComponents = [
        'selectTables',
    ];

    /**
     * Select field components.
     *
     * @var string[]
     * @since 1.0.0
     */
    protected array $fieldsComponents = [
        'selectFields',
    ];

    /**
     * Alter components.
     *
     * @var string[]
     * @since 1.0.0
     */
    protected array $alterComponents = [
        'alterTable',
        'alterColumn',
        'alterAdd',
        'alterRename',
        'alterRemove',
    ];

    /**
     * {@inheritdoc}
     */
    protected function getComponents(int $type) : array
    {
        switch ($type) {
            case QueryType::DROP_DATABASE:
                return $this->dropDatabaseComponents;
            case QueryType::TABLES:
                return $this->tablesComponents;
            case QueryType::FIELDS:
                return $this->fieldsComponents;
            case QueryType::CREATE_TABLE:
                return $this->createTablesComponents;
            case QueryType::DROP_TABLE:
                return $this->dropTableComponents;
            case QueryType::ALTER:
                return $this->alterComponents;
            default:
                return parent::getComponents($type);
        }
    }

    /**
     * Compile alter table query.
     *
     * @param BuilderAbstract $query Query
     * @param string          $table Table to alter
     *
     * @return string
     *
     * @since 1.0.0
     */
    protected function compileAlterTable(BuilderAbstract $query, string $table) : string
    {
        return 'ALTER TABLE ' . $this->expressionizeTableColumn([$table]);
    }

    /**
     * Compile alter column query.
     *
     * @param BuilderAbstract $query  Query
     * @param string          $column Column to alter
     *
     * @return string
     *
     * @since 1.0.0
     */
    protected function compileAlterColumn(BuilderAbstract $query, string $column) : string
    {
        return '';
    }

    /**
     * Compile alter add query.
     *
     * @param BuilderAbstract $query Query
     * @param array           $add   Add data
     *
     * @return string
     *
     * @since 1.0.0
     */
    protected function compileAlterAdd(BuilderAbstract $query, array $add) : string
    {
        switch ($add['type']) {
            case 'COLUMN':
                return $this->addColumn($add);
            case 'CONSTRAINT':
                return $this->addConstraint($add);
            default:
                return '';
        }
    }

    /**
     * Add a new column.
     *
     * @param array $add Add data
     *
     * @return string
     *
     * @since 1.0.0
     */
    private function addColumn(array $add) : string
    {
        return 'ADD ' . $this->expressionizeTableColumn([$add['name']]) . ' ' . $add['datatype'];
    }

    /**
     * Add a new constraint/foreign key.
     *
     * @param array $add Add data
     *
     * @return string
     *
     * @since 1.0.0
     */
    private function addConstraint(array $add) : string
    {
        return 'ADD' . (isset($add['constraint']) ? ' CONSTRAINT ' . $add['constraint'] : '')
            . ' FOREIGN KEY (' .  $this->expressionizeTableColumn([$add['key']]) . ') REFERENCES '
            . $this->expressionizeTableColumn([$add['foreignTable']])
            . ' (' . $this->expressionizeTableColumn([$add['foreignKey']]) . ')';
    }

    /**
     * Compile create table query.
     *
     * @param BuilderAbstract $query Query
     * @param string          $table Tables to drop
     *
     * @return string
     *
     * @since 1.0.0
     */
    protected function compileCreateTable(BuilderAbstract $query, string $table) : string
    {
        return 'CREATE TABLE IF NOT EXISTS ' . $this->expressionizeTableColumn([$table]);
    }

    /**
     * Compile create table settings query.
     *
     * @param BuilderAbstract $query    Query
     * @param bool            $settings Has settings
     *
     * @return string
     *
     * @since 1.0.0
     */
    protected function compileCreateTableSettings(BuilderAbstract $query, bool $settings) : string
    {
        return '';
    }

    /**
     * Compile drop query.
     *
     * @param BuilderAbstract $query    Query
     * @param string          $database Tables to drop
     *
     * @return string
     *
     * @since 1.0.0
     */
    protected function compileDropDatabase(BuilderAbstract $query, string $database) : string
    {
        $expression = $this->expressionizeTableColumn([$database]);

        if ($expression === '') {
            $expression = '*';
        }

        return 'DROP DATABASE ' . $expression;
    }

    /**
     * Compile drop query.
     *
     * @param BuilderAbstract $query  Query
     * @param array           $tables Tables to drop
     *
     * @return string
     *
     * @since 1.0.0
     */
    protected function compileDropTable(BuilderAbstract $query, array $tables) : string
    {
        $expression = $this->expressionizeTableColumn($tables);

        if ($expression === '') {
            $expression = '*';
        }

        return 'DROP TABLE ' . $expression;
    }
}
