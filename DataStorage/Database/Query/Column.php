<?php
/**
 * Orange Management
 *
 * PHP Version 7.1
 *
 * @package    TBD
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */
declare(strict_types=1);

namespace phpOMS\DataStorage\Database\Query;

/**
 * Database query builder.
 *
 * @package    Framework
 * @license    OMS License 1.0
 * @link       http://website.orange-management.de
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
    private $column = '';

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
