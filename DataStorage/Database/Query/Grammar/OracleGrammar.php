<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\DataStorage\Database\Query\Grammar
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://karaka.app
 */
declare(strict_types=1);

namespace phpOMS\DataStorage\Database\Query\Grammar;

use phpOMS\DataStorage\Database\Query\Builder;

/**
 * Grammar class.
 *
 * @package phpOMS\DataStorage\Database\Query\Grammar
 * @license OMS License 1.0
 * @link    https://karaka.app
 * @since   1.0.0
 */
class OracleGrammar extends Grammar
{
    /**
     * Compile random.
     *
     * @param Builder $query   Builder
     * @param array   $columns Columns
     *
     * @return string
     *
     * @since 1.0.0
     */
    protected function compileRandom(Builder $query, array $columns) : string
    {
        $expression = $this->expressionizeTableColumn($columns);

        if ($expression === '') {
            $expression = '*';
        }

        $query->limit = $query->limit ?? 1;

        return 'SELECT ' . $expression . ' FROM (SELECT ' . $expression . ' ' . $this->compileFrom($query, $query->from) . ' ORDER BY dbms_random.value) WHERE rownum >= 1 AND rownum <= ' . ($query->limit ?? 1);
    }
}
