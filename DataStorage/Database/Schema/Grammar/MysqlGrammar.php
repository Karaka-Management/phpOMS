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
use phpOMS\DataStorage\Database\Query\Builder;

/**
 * Database query grammar.
 *
 * @package    phpOMS\DataStorage\Database\Schema\Grammar
 * @license    OMS License 1.0
 * @link       http://website.orange-management.de
 * @since      1.0.0
 */
class MysqlGrammar extends Grammar
{
    /**
     * System identifier.
     *
     * @var string
     * @since 1.0.0
     */
    protected $systemIdentifier = '`';

    /**
     * Compile from.
     *
     * @param Builder $query Builder
     * @param array   $table Tables
     *
     * @return string
     *
     * @since  1.0.0
     */
    protected function compileSelectTables(Builder $query, array $table) : string
    {
        $builder = new Builder($query->getConnection());
        $builder->select('table_name')
            ->from('information_schema.tables')
            ->where('table_schema', '=', $query->getConnection()->getDatabase());

        return \rtrim($builder->toSql(), ';');
    }

    /**
     * Compile from.
     *
     * @param Builder $query Builder
     * @param array   $table Tables
     *
     * @return string
     *
     * @since  1.0.0
     */
    protected function compileSelectFields(Builder $query, string $table) : string
    {
        $builder = new Builder($query->getConnection());
        $builder->select('*')
            ->from('information_schema.columns')
            ->where('table_schema', '=', $query->getConnection()->getDatabase())
            ->andWhere('table_name', '=', $query->getPrefix() . $table);

        return \rtrim($builder->toSql(), ';');
    }

    /**
     * Compile create table fields query.
     *
     * @param Builder $query  Query
     * @param array   $fields Fields to create
     *
     * @return string
     *
     * @since  1.0.0
     */
    protected function compileCreateFields(Builder $query, array $fields) : string
    {
        $fieldQuery = '';
        $keys       = '';

        foreach ($fields as $name => $field) {
            $fieldQuery .= ' ' . $this->expressionizeTableColumn([$name], '') . ' ' . $field['type'];

            if (isset($field['default']) || ($field['default'] === null && isset($field['null']) && $field['null'])) {
                $fieldQuery .= ' DEFAULT ' . $this->compileValue($query, $field['default']);
            }

            if (isset($field['null'])) {
                $fieldQuery .= ' ' . ($field['null'] ? '' : 'NOT ') . 'NULL';
            }

            if (isset($field['autoincrement']) && $field['autoincrement']) {
                $fieldQuery .= ' AUTO_INCREMENT';
            }

            $fieldQuery .= ',';

            if (isset($field['primary']) && $field['primary']) {
                $keys .= ' PRIMARY KEY (' .  $this->expressionizeTableColumn([$name], '') . '),';
            }

            if (isset($field['foreignTable'], $field['foreignKey'])
                && !empty($field['foreignTable']) && !empty($field['foreignKey'])
            ) {
                $keys .= ' FOREIGN KEY (' .  $this->expressionizeTableColumn([$name], '') . ') REFERENCES '
                    . $this->expressionizeTable([$field['foreignTable']], $query->getPrefix())
                    . ' (' . $this->expressionizeTableColumn([$field['foreignKey']], '') . '),';
            }
        }

        return '(' . \ltrim(\rtrim($fieldQuery . $keys, ','), ' ') . ')';
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
        return 'ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1';
    }
}
