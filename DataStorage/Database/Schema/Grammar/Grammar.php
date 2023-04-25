<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\DataStorage\Database\Schema\Grammar
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\DataStorage\Database\Schema\Grammar;

use phpOMS\DataStorage\Database\BuilderAbstract;
use phpOMS\DataStorage\Database\GrammarAbstract;
use phpOMS\DataStorage\Database\Schema\Builder as SchemaBuilder;
use phpOMS\DataStorage\Database\Schema\QueryType;

/**
 * Database query grammar.
 *
 * @package phpOMS\DataStorage\Database\Schema\Grammar
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
class Grammar extends GrammarAbstract
{
    /**
     * {@inheritdoc}
     */
    protected function compileComponents(BuilderAbstract $query) : array
    {
        /** @var SchemaBuilder $query */

        $sql = [];
        switch ($query->getType()) {
            case QueryType::DROP_DATABASE:
                if (empty($query->dropDatabase)) {
                    break;
                }

                $sql[] = $this->compileDropDatabase($query, $query->dropDatabase);

                break;
            case QueryType::TABLES:
                if (empty($query->selectTables)) {
                    break;
                }

                $sql[] = $this->compileSelectTables($query, $query->selectTables);

                break;
            case QueryType::FIELDS:
                if (empty($query->selectFields)) {
                    break;
                }

                $sql[] = $this->compileSelectFields($query, $query->selectFields);

                break;
            case QueryType::CREATE_TABLE:
                if (empty($query->createTable)) {
                    break;
                }

                $sql[] = $this->compileCreateTable($query, $query->createTable);
                $sql[] = $this->compileCreateFields($query, $query->createFields);

                if (empty($query->createTableSettings)) {
                    break;
                }

                $sql[] = $this->compileCreateTableSettings($query, $query->createTableSettings);

                break;
            case QueryType::DROP_TABLE:
                if (empty($query->dropTable)) {
                    break;
                }

                $sql[] = $this->compileDropTable($query, $query->dropTable);

                break;
            case QueryType::ALTER:
                $sql[] = $this->compileAlterTable($query, $query->alterTable);
                $sql[] = $this->compileAlterColumn($query, $query->alterColumn);
                $sql[] = $this->compileAlterAdd($query, $query->alterAdd);
                // $sql[] = $this->compileAlterRename($query, $query->alterRename);
                // $sql[] = $this->compileAlterRemove($query, $query->alterRemove);

                break;
            case QueryType::RAW:
                $sql[] = $query->raw;

                break;
            default:
                return [];
        }

        return $sql;
    }

    /**
     * Compile the select tables.
     *
     * @param SchemaBuilder $query  Query
     * @param array         $tables Tables
     *
     * @return string
     *
     * @since 1.0.0
     */
    protected function compileSelectTables(SchemaBuilder $query, array $tables) : string
    {
        return '';
    }

    /**
     * Compile the select fields from table.
     *
     * @param SchemaBuilder $query Query
     * @param array         $table Table
     *
     * @return string
     *
     * @since 1.0.0
     */
    protected function compileSelectFields(SchemaBuilder $query, string $table) : string
    {
        return '';
    }

    /**
     * Compile the create fields.
     *
     * @param SchemaBuilder $query  Query
     * @param array         $fields Fields
     *
     * @return string
     *
     * @since 1.0.0
     */
    protected function compileCreateFields(SchemaBuilder $query, array $fields) : string
    {
        return '';
    }

    /**
     * Compile the select tables.
     *
     * @param SchemaBuilder $query Query
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function compilePostQueries(BuilderAbstract $query): array
    {
        return [];
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
