<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\DataStorage\Database\Schema
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://karaka.app
 */
declare(strict_types=1);

namespace phpOMS\DataStorage\Database\Schema;

use phpOMS\DataStorage\Database\Query\QueryType as DefaultQueryType;

/**
 * Query type enum.
 *
 * Types used by the schema grammar in order to build the correct query.
 *
 * @package phpOMS\DataStorage\Database\Schema
 * @license OMS License 1.0
 * @link    https://karaka.app
 * @since   1.0.0
 */
abstract class QueryType extends DefaultQueryType
{
    public const DROP_DATABASE = 128;

    public const ALTER = 129;

    public const TABLES = 130;

    public const FIELDS = 131;

    public const CREATE_TABLE = 132;

    public const DROP_TABLE = 133;
}
