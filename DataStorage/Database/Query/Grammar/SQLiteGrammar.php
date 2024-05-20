<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   phpOMS\DataStorage\Database\Query\Grammar
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.2
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\DataStorage\Database\Query\Grammar;

use phpOMS\DataStorage\Database\Query\Builder;

/**
 * Grammar class.
 *
 * @package phpOMS\DataStorage\Database\Query\Grammar
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

        return 'SELECT ' . $expression . ' ' . $this->compileFrom($query, $query->from) . ' ORDER BY RANDOM() ' . $this->compileLimit($query, $query->limit ?? 1);
    }

    /**
     * Create concat
     *
     * @param Concat $query   Builder
     * @param array  $columns Columns
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function compileConcat(\phpOMS\DataStorage\Database\Query\Concat $query, array $columns) : string
    {
        $sql = $this->expressionizeTableColumn($columns);
        $sql = \str_replace(',', ' ||', $sql);

        return $sql . ' AS ' . $query->as;
    }
}
