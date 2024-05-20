<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   phpOMS\DataStorage\Database\Schema\Grammar
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.2
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\DataStorage\Database\Schema\Grammar;

use phpOMS\DataStorage\Database\BuilderAbstract;
use phpOMS\DataStorage\Database\Query\Builder;
use phpOMS\DataStorage\Database\Schema\Builder as SchemaBuilder;

/**
 * Database query grammar.
 *
 * @package phpOMS\DataStorage\Database\Schema\Grammar
 * @license OMS License 2.2
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
     * {@inheritdoc}
     */
    protected function compileSelectTables(SchemaBuilder $query, array $table) : string
    {
        $builder = new Builder($query->getConnection());
        $builder->select('name')
            ->from('sqlite_master')
            ->where('type', '=', 'table');

        $sql = $builder->toSql();

        foreach ($builder->binds as $bind) {
            $query->bind($bind);
        }

        return \rtrim($sql, ';');
    }

    /**
     * {@inheritdoc}
     */
    protected function compileSelectFields(SchemaBuilder $query, string $table) : string
    {
        $builder = new Builder($query->getConnection());
        $builder->select('*')
            ->from('pragma_table_info(\'' . $table . '\')')
            ->where('pragma_table_info(\'' . $table . '\')', '=', $table);

        $sql = $builder->toSql();

        foreach ($builder->binds as $bind) {
            $query->bind($bind);
        }

        return \rtrim($sql, ';');
    }

    /**
     * Parses the sql data types to the appropriate SQLite data types
     *
     * @param string $type Data type
     *
     * @return string
     *
     * @since 1.0.0
     */
    private function parseFieldType(string $type) : string
    {
        $type = \strtoupper($type);

        if (\str_starts_with($type, 'INT')
            || \str_starts_with($type, 'TINYINT')
            || \str_starts_with($type, 'SMALLINT')
            || \str_starts_with($type, 'BIGINT')
        ) {
            return 'INTEGER';
        } elseif (\str_starts_with($type, 'VARCHAR')) {
            return 'TEXT';
        } elseif (\str_starts_with($type, 'DATETIME')) {
            return 'TEXT';
        } elseif (\str_starts_with($type, 'DECIMAL')) {
            return 'REAL';
        } elseif (\stripos($type, 'BINARY') !== false) {
            return 'BLOB';
        }

        return $type;
    }

    /**
     * {@inheritdoc}
     */
    protected function compileCreateTable(BuilderAbstract $query, string $table) : string
    {
        return 'CREATE TABLE ' . $this->expressionizeTableColumn([$table]);
    }

    /**
     * {@inheritdoc}
     */
    protected function compileCreateFields(SchemaBuilder $query, array $fields) : string
    {
        $fieldQuery = '';
        $keys       = '';

        foreach ($fields as $name => $field) {
            $fieldQuery .= ' ' . $this->expressionizeTableColumn([$name]) . ' ' . $this->parseFieldType($field['type']);

            if (isset($field['default']) || ($field['default'] === null && ($field['null'] ?? false))) {
                $fieldQuery .= ' DEFAULT ' . $this->compileValue($query, $field['default']);
            }

            if ($field['null'] ?? false) {
                $fieldQuery .= ' ' . ($field['null'] ? '' : 'NOT ') . 'NULL';
            }

            if ($field['primary'] ?? false) {
                $fieldQuery .= ' PRIMARY KEY';
            }

            if ($field['autoincrement'] ?? false) {
                $fieldQuery .= ' AUTOINCREMENT';
            }

            $fieldQuery .= ',';

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
