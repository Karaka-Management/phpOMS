<?php
/**
 * Orange Management
 *
 * PHP Version 7.0
 *
 * @category   TBD
 * @package    TBD
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @copyright  2013 Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://orange-management.com
 */

namespace phpOMS\DataStorage\Database\Schema\Grammar;

use phpOMS\DataStorage\Database\Query\Builder;

/**
 * Database query grammar.
 *
 * @category   Framework
 * @package    phpOMS\DataStorage\Database
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
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
     * Compile select.
     *
     * @param Builder $query   Builder
     * @param array   $columns Columns
     *
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    protected function compileSelects(Builder $query, array $columns) : string
    {
        $expression = $this->expressionizeTableColumn($columns);

        if ($expression === '') {
            $expression = '*';
        }

        return $expression;
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
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    protected function compileFrom(Builder $query, array $table) : string
    {
        $expression = $this->expressionizeTableColumn('information_schema.tables');

        return 'FROM ' . $expression;
    }
}
