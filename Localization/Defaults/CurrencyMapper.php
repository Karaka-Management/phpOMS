<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   phpOMS\Localization\Defaults
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Localization\Defaults;

use phpOMS\DataStorage\Database\DataMapperAbstract;

/**
 * Mapper class.
 *
 * @package phpOMS\Localization\Defaults
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
class CurrencyMapper extends DataMapperAbstract
{
    /**
     * Columns.
     *
     * @var array<string, array<string, bool|string>>
     * @since 1.0.0
     */
    protected static array $columns = [
        'currency_id'        => ['name' => 'currency_id',        'type' => 'int',    'internal' => 'id'],
        'currency_name'      => ['name' => 'currency_name',      'type' => 'string', 'internal' => 'name'],
        'currency_char'      => ['name' => 'currency_char',      'type' => 'string', 'internal' => 'code'],
        'currency_number'    => ['name' => 'currency_num',       'type' => 'string', 'internal' => 'number'],
        'currency_symbol'    => ['name' => 'currency_symbol',    'type' => 'string', 'internal' => 'symbol'],
        'currency_subunits'  => ['name' => 'currency_subunits',  'type' => 'int',    'internal' => 'subunits'],
        'currency_decimals'  => ['name' => 'currency_decimals',  'type' => 'int',    'internal' => 'decimals'],
        'currency_countries' => ['name' => 'currency_countries', 'type' => 'string', 'internal' => 'countries'],
    ];

    /**
     * Primary table.
     *
     * @var string
     * @since 1.0.0
     */
    protected static string $table = 'currency';

    /**
     * Primary field name.
     *
     * @var string
     * @since 1.0.0
     */
    protected static string $primaryField = 'currency_id';
}
