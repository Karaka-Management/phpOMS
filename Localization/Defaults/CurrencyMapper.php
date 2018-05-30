<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    phpOMS\Localization\Defaults
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */
declare(strict_types=1);

namespace phpOMS\Localization\Defaults;

use phpOMS\DataStorage\Database\DataMapperAbstract;
use phpOMS\DataStorage\Database\Query\Builder;
use phpOMS\DataStorage\Database\Query\Column;
use phpOMS\DataStorage\Database\RelationType;

/**
 * Mapper class.
 *
 * @package    phpOMS\Localization\Defaults
 * @license    OMS License 1.0
 * @link       http://website.orange-management.de
 * @since      1.0.0
 */
class CurrencyMapper extends DataMapperAbstract
{

    /**
     * Columns.
     *
     * @var array<string, array<string, string>>
     * @since 1.0.0
     */
    protected static $columns = [
        'currency_id'        => ['name' => 'currency_id', 'type' => 'int', 'internal' => 'id'],
        'currency_name'      => ['name' => 'currency_name', 'type' => 'string', 'internal' => 'name'],
        'currency_code'      => ['name' => 'currency_code', 'type' => 'string', 'internal' => 'code'],
        'currency_number'    => ['name' => 'currency_number', 'type' => 'int', 'internal' => 'number'],
        'currency_decimal'   => ['name' => 'currency_decimal', 'type' => 'int', 'internal' => 'decimals'],
        'currency_countries' => ['name' => 'currency_countries', 'type' => 'string', 'internal' => 'countries'],
    ];

    /**
     * Primary table.
     *
     * @var string
     * @since 1.0.0
     */
    protected static $table = 'currency';

    /**
     * Primary field name.
     *
     * @var string
     * @since 1.0.0
     */
    protected static $primaryField = 'currency_id';
}
