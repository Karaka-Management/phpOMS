<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   phpOMS\DataStorage\Database\Query\Grammar
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\DataStorage\Database\Query\Grammar;

use phpOMS\DataStorage\Database\Query\Builder;

/**
 * Grammar class.
 *
 * @package phpOMS\DataStorage\Database\Query\Grammar
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
class SQLiteGrammar extends Grammar
{
    /**
     * System identifier.
     *
     * @var   string
     * @since 1.0.0
     */
    public string $systemIdentifier = '`';

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
        $expression = $this->expressionizeTableColumn($columns, $query->getPrefix());

        if ($expression === '') {
            $expression = '*';
        }

        return 'SELECT ' . $expression . ' ' . $this->compileFrom($query, $query->from) . ' ORDER BY RANDOM() ' . $this->compileLimit($query, $query->limit ?? 1);
    }
}
