<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    phpOMS\DataStorage\Database\Schema\Grammar
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */
declare(strict_types=1);

namespace phpOMS\DataStorage\Database\Schema\Grammar;

use phpOMS\DataStorage\Database\BuilderAbstract;
use phpOMS\DataStorage\Database\Query\Grammar\Grammar as QueryGrammar;
use phpOMS\DataStorage\Database\Schema\QueryType;

/**
 * Database query grammar.
 *
 * @package    phpOMS\DataStorage\Database\Schema\Grammar
 * @license    OMS License 1.0
 * @link       http://website.orange-management.de
 * @since      1.0.0
 */
class Grammar extends QueryGrammar
{
    /**
     * Drop components.
     *
     * @var string[]
     * @since 1.0.0
     */
    protected $dropComponents = [
        'drop',
    ];

    /**
     * Select tables components.
     *
     * @var string[]
     * @since 1.0.0
     */
    protected $createTablesComponents = [
        'createTable',
        'createFields',
        'createTableSettings'
    ];

    /**
     * Select tables components.
     *
     * @var string[]
     * @since 1.0.0
     */
    protected $tablesComponents = [
        'selectTables',
    ];

    /**
     * Select field components.
     *
     * @var string[]
     * @since 1.0.0
     */
    protected $fieldsComponents = [
        'selectFields',
    ];

    /**
     * {@inheritdoc}
     */
    protected function getComponents(int $type) : array
    {
        switch ($type) {
            case QueryType::DROP:
                return $this->dropComponents;
            case QueryType::TABLES:
                return $this->tablesComponents;
            case QueryType::FIELDS:
                return $this->fieldsComponents;
            case QueryType::CREATE_TABLE:
                return $this->createTablesComponents;
            default:
                return parent::getComponents($type);
        }
    }

    /**
     * Compile create table query.
     *
     * @param BuilderAbstract $query Query
     * @param string          $table Tables to drop
     *
     * @return string
     *
     * @since  1.0.0
     */
    protected function compileCreateTable(BuilderAbstract $query, string $table) : string
    {
        return 'CREATE TABLE ' . $this->expressionizeTable([$table], $query->getPrefix());
    }

    /**
     * Compile create table settings query.
     *
     * @param BuilderAbstract $query    Query
     * @param bool            $settings Has settings
     *
     * @return string
     *
     * @since  1.0.0
     */
    protected function compileCreateTableSettings(BuilderAbstract $query, bool $settings) : string
    {
        return '';
    }

    /**
     * Compile drop query.
     *
     * @param BuilderAbstract $query  Query
     * @param array           $tables Tables to drop
     *
     * @return string
     *
     * @since  1.0.0
     */
    protected function compileDrop(BuilderAbstract $query, array $tables) : string
    {
        $expression = $this->expressionizeTableColumn($tables, $query->getPrefix());

        if ($expression === '') {
            $expression = '*';
        }

        return 'DROP DATABASE ' . $expression;
    }
}
