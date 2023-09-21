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
class PostgresGrammar extends Grammar
{
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
        $builder->select('table_name')
            ->from('information_schema.tables')
            ->where('table_schema', '=', $query->getConnection()->getDatabase());

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
            ->from('information_schema.columns')
            ->where('table_schema', '=', $query->getConnection()->getDatabase())
            ->andWhere('table_name', '=', $table);

        return \rtrim($builder->toSql(), ';');
    }
}
