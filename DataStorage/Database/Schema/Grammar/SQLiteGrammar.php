<?php
/**
 * Jingga
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

use phpOMS\DataStorage\Database\Query\Builder;
use phpOMS\DataStorage\Database\Schema\Builder as SchemaBuilder;

/**
 * Database query grammar.
 *
 * @package phpOMS\DataStorage\Database\Schema\Grammar
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
class SQLiteGrammar extends Grammar
{
    /**
     * System identifier.
     *
     * @var string
     * @since 1.0.0
     */
    public string $systemIdentifierStart = '`';

    /**
     * System identifier.
     *
     * @var string
     * @since 1.0.0
     */
    public string $systemIdentifierEnd = '`';

    /**
     * Compile from.
     *
     * @param SchemaBuilder $query Builder
     * @param array         $table Tables
     *
     * @return string
     *
     * @since 1.0.0
     */
    protected function compileSelectTables(SchemaBuilder $query, array $table) : string
    {
        $builder = new Builder($query->getConnection());
        $builder->select('name')
            ->from('sqlite_master')
            ->where('type', '=', 'table');

        return \rtrim($builder->toSql(), ';');
    }

    /**
     * Compile from.
     *
     * @param SchemaBuilder $query Builder
     * @param string        $table Tables
     *
     * @return string
     *
     * @since 1.0.0
     */
    protected function compileSelectFields(SchemaBuilder $query, string $table) : string
    {
        $builder = new Builder($query->getConnection());
        $builder->select('*')
            ->from('pragma_table_info(\'' . $table . '\')')
            ->where('pragma_table_info(\'' . $table . '\')', '=', $table);

        return \rtrim($builder->toSql(), ';');
    }

    /**
     * Compile create table fields query.
     *
     * @param SchemaBuilder $query  Query
     * @param array         $fields Fields to create
     *
     * @return string
     *
     * @since 1.0.0
     */
    protected function compileCreateFields(SchemaBuilder $query, array $fields) : string
    {
        $fieldQuery = '';
        $keys       = '';

        foreach ($fields as $name => $field) {
            $fieldQuery .= ' ' . $this->expressionizeTableColumn([$name]) . ' ' . $field['type'];

            if (isset($field['default']) || ($field['default'] === null && ($field['null'] ?? false))) {
                $fieldQuery .= ' DEFAULT ' . $this->compileValue($query, $field['default']);
            }

            if ($field['null'] ?? false) {
                $fieldQuery .= ' ' . ($field['null'] ? '' : 'NOT ') . 'NULL';
            }

            if ($field['autoincrement'] ?? false) {
                $fieldQuery .= ' AUTO_INCREMENT';
            }

            $fieldQuery .= ',';

            if ($field['primary'] ?? false) {
                $keys .= ' PRIMARY KEY (' .  $this->expressionizeTableColumn([$name]) . '),';
            }

            if ($field['unique'] ?? false) {
                $keys .= ' UNIQUE KEY (' .  $this->expressionizeTableColumn([$name]) . '),';
            }

            if (isset($field['foreignTable'], $field['foreignKey'])) {
                $keys .= ' FOREIGN KEY (' .  $this->expressionizeTableColumn([$name]) . ') REFERENCES '
                    . $this->expressionizeTableColumn([$field['foreignTable']])
                    . ' (' . $this->expressionizeTableColumn([$field['foreignKey']]) . '),';
            }

            if (isset($field['meta']['multi_autoincrement'])) {
                $query->hasPostQuery = true;
            }
        }

        return '(' . \ltrim(\rtrim($fieldQuery . $keys, ','), ' ') . ')';
    }
}
