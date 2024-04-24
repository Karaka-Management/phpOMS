<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   phpOMS\DataStorage\Database\Query
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\DataStorage\Database\Query;

/**
 * Database query builder.
 *
 * @package phpOMS\DataStorage\Database\Query
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class ColumnName
{
    /**
     * Column name
     *
     * @var string
     * @since 1.0.0
     */
    public string $name = '';

    /**
     * Constructor.
     *
     * @param string $name Column name
     *
     * @since 1.0.0
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }
}
