<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   phpOMS\DataStorage\Database\Schema\Grammar
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\DataStorage\Database\Schema\Grammar;

use phpOMS\DataStorage\Database\BuilderAbstract;
use phpOMS\DataStorage\Database\Query\Builder;

/**
 * Database query grammar.
 *
 * @package phpOMS\DataStorage\Database\Schema\Grammar
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 *
 * @todo Orange-Management/phpOMS#32
 *  Implement schema grammar
 *  Basic create/drop schema grammar created. Next step is to be able to update existing schema and read existing schema.
 */
class MysqlGrammar extends Grammar
{
    /**
     * System identifier.
     *
     * @var string
     * @since 1.0.0
     */
    protected string $systemIdentifierStart = '`';

    /**
     * System identifier.
     *
     * @var string
     * @since 1.0.0
     */
    protected string $systemIdentifierEnd = '`';

    /**
     * Compile remove
     *
     * @param Builder $query  Builder
     * @param array   $remove Remove data
     *
     * @return string
     *
     * @since 1.0.0
     */
    protected function compileAlterRemove(BuilderAbstract $query, array $remove) : string
    {
        $keyWord = $remove['type'] === 'CONSTRAINT' ? 'FOREIGN KEY ' : 'COLUMN';

        return 'DROP ' . $keyWord . ' ' . $remove['identifier'];
    }

    /**
     * Compile from.
     *
     * @param Builder $query Builder
     * @param array   $table Tables
     *
     * @return string
     *
     * @since 1.0.0
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
     * @param string  $table Tables
     *
     * @return string
     *
     * @since 1.0.0
     */
    protected function compileSelectFields(Builder $query, string $table) : string
    {
        $builder = new Builder($query->getConnection());
        $builder->select('*')
            ->from('information_schema.columns')
            ->where('table_schema', '=', $query->getConnection()->getDatabase())
            ->andWhere('table_name', '=', $table);

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
     * @since 1.0.0
     */
    protected function compileCreateFields(Builder $query, array $fields) : string
    {
        $fieldQuery = '';
        $keys       = '';

        foreach ($fields as $name => $field) {
            $fieldQuery .= ' ' . $this->expressionizeTableColumn([$name]) . ' ' . $field['type'];

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
                $keys .= ' PRIMARY KEY (' .  $this->expressionizeTableColumn([$name]) . '),';
            }

            if (isset($field['unique']) && $field['unique']) {
                $keys .= ' UNIQUE KEY (' .  $this->expressionizeTableColumn([$name]) . '),';
            }

            if (isset($field['foreignTable'], $field['foreignKey'])
                && !empty($field['foreignTable']) && !empty($field['foreignKey'])
            ) {
                $keys .= ' FOREIGN KEY (' .  $this->expressionizeTableColumn([$name]) . ') REFERENCES '
                    . $this->expressionizeTableColumn([$field['foreignTable']])
                    . ' (' . $this->expressionizeTableColumn([$field['foreignKey']]) . '),';
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
     * @since 1.0.0
     */
    protected function compileCreateTableSettings(BuilderAbstract $query, bool $settings) : string
    {
        return 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1';
    }
}
