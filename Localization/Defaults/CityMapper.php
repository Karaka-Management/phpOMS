<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Localization\Defaults
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://karaka.app
 */
declare(strict_types=1);

namespace phpOMS\Localization\Defaults;

use phpOMS\DataStorage\Database\Mapper\DataMapperFactory;

/**
 * Mapper class.
 *
 * @package phpOMS\Localization\Defaults
 * @license OMS License 1.0
 * @link    https://karaka.app
 * @since   1.0.0
 */
class CityMapper extends DataMapperFactory
{
    /**
     * Columns.
     *
     * @var array<string, array{name:string, type:string, internal:string, autocomplete?:bool, readonly?:bool, writeonly?:bool, annotations?:array}>
     * @since 1.0.0
     */
    public const COLUMNS = [
        'city_id'      => ['name' => 'city_id',      'type' => 'int',    'internal' => 'id'],
        'city_city'    => ['name' => 'city_city',    'type' => 'string', 'internal' => 'name'],
        'city_country' => ['name' => 'city_country', 'type' => 'string', 'internal' => 'countryCode'],
        'city_state'   => ['name' => 'city_state',   'type' => 'string', 'internal' => 'state'],
        'city_postal'  => ['name' => 'city_postal',  'type' => 'int',    'internal' => 'postal'],
        'city_lat'     => ['name' => 'city_lat',     'type' => 'float',  'internal' => 'lat'],
        'city_long'    => ['name' => 'city_long',    'type' => 'float',  'internal' => 'long'],
    ];

    /**
     * Primary table.
     *
     * @var string
     * @since 1.0.0
     */
    public const TABLE = 'city';

    /**
     * Primary field name.
     *
     * @var string
     * @since 1.0.0
     */
    public const PRIMARYFIELD ='city_id';
}
