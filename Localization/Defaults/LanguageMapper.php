<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Localization\Defaults
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Localization\Defaults;

use phpOMS\DataStorage\Database\Mapper\DataMapperFactory;

/**
 * Mapper class.
 *
 * @package phpOMS\Localization\Defaults
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 *
 * @template T of Language
 * @extends DataMapperFactory<T>
 */
class LanguageMapper extends DataMapperFactory
{
    /**
     * Columns.
     *
     * @var array<string, array{name:string, type:string, internal:string, autocomplete?:bool, readonly?:bool, writeonly?:bool, annotations?:array}>
     * @since 1.0.0
     */
    public const COLUMNS = [
        'language_id'     => ['name' => 'language_id',     'type' => 'int',    'internal' => 'id'],
        'language_name'   => ['name' => 'language_name',   'type' => 'string', 'internal' => 'name'],
        'language_native' => ['name' => 'language_native', 'type' => 'string', 'internal' => 'native'],
        'language_639_1'  => ['name' => 'language_639_1',  'type' => 'string', 'internal' => 'code2'],
        'language_639_2B' => ['name' => 'language_639_2B', 'type' => 'string', 'internal' => 'code3'],
        'language_639_2T' => ['name' => 'language_639_2T', 'type' => 'string', 'internal' => 'code3Native'],
    ];

    /**
     * Primary table.
     *
     * @var string
     * @since 1.0.0
     */
    public const TABLE = 'language';

    /**
     * Primary field name.
     *
     * @var string
     * @since 1.0.0
     */
    public const PRIMARYFIELD = 'language_id';
}
