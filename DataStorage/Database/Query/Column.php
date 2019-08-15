<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package    phpOMS\DataStorage\Database\Query
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\DataStorage\Database\Query;

/**
 * Database query builder.
 *
 * @package    phpOMS\DataStorage\Database\Query
 * @license    OMS License 1.0
 * @link       https://orange-management.org
 * @since      1.0.0
 */
class Column
{

    /**
     * Column name.
     *
     * @var string
     * @since 1.0.0
     */
    private string $column = '';

    /**
     * Constructor.
     *
     * @param string $column Column
     *
     * @since  1.0.0
     */
    public function __construct(string $column)
    {
        $this->column = $column;
    }

    /**
     * Get column string.
     *
     * @return string
     *
     * @since  1.0.0
     */
    public function getColumn() : string
    {
        return $this->column;
    }

    public function setColumn(string $column) : void
    {
        $this->column = $column;
    }
}
