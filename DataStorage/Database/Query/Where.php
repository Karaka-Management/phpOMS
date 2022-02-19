<?php
/**
 * Karaka
 *
 * PHP Version 8.0
 *
 * @package   phpOMS\DataStorage\Database\Query
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://karaka.app
 */
declare(strict_types=1);

namespace phpOMS\DataStorage\Database\Query;

use phpOMS\DataStorage\Database\Connection\ConnectionAbstract;

/**
 * Database query builder.
 *
 * @package phpOMS\DataStorage\Database\Query
 * @license OMS License 1.0
 * @link    https://karaka.app
 * @since   1.0.0
 */
class Where extends Builder
{
    /**
     * Constructor.
     *
     * @param ConnectionAbstract $connection Database connection
     *
     * @since 1.0.0
     */
    public function __construct(ConnectionAbstract $connection)
    {
        parent::__construct($connection);
        $this->type = QueryType::SELECT;
    }
}
