<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    phpOMS\DataStorage\Database\Schema
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */
declare(strict_types=1);

namespace phpOMS\DataStorage\Database\Schema;

use phpOMS\DataStorage\Database\Query\QueryType as DefaultQueryType;

/**
 * Database type enum.
 *
 * Database types that are supported by the application
 *
 * @package    phpOMS\DataStorage\Database\Schema
 * @license    OMS License 1.0
 * @link       http://website.orange-management.de
 * @since      1.0.0
 */
abstract class QueryType extends DefaultQueryType
{
    public const DROP         = 128;
    public const ALTER        = 129;
    public const TABLES       = 130;
    public const FIELDS       = 131;
    public const CREATE_TABLE = 132;
}
