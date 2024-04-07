<?php
/**
 * Jingga
 *
 * PHP Version 8.2
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
 * Iban mapper class.
 *
 * @package phpOMS\Localization\Defaults
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 *
 * @template T of Iban
 * @extends DataMapperFactory<T>
 */
class IbanMapper extends DataMapperFactory
{
    /**
     * Columns.
     *
     * @var array<string, array{name:string, type:string, internal:string, autocomplete?:bool, readonly?:bool, writeonly?:bool, annotations?:array}>
     * @since 1.0.0
     */
    public const COLUMNS = [
        'iban_id'      => ['name' => 'iban_id',      'type' => 'int',    'internal' => 'id'],
        'iban_country' => ['name' => 'iban_country', 'type' => 'string', 'internal' => 'country'],
        'iban_chars'   => ['name' => 'iban_chars',   'type' => 'int',    'internal' => 'chars'],
        'iban_bban'    => ['name' => 'iban_bban',    'type' => 'string', 'internal' => 'bban'],
        'iban_fields'  => ['name' => 'iban_fields',  'type' => 'string', 'internal' => 'fields'],
    ];

    /**
     * Primary table.
     *
     * @var string
     * @since 1.0.0
     */
    public const TABLE = 'iban';

    /**
     * Primary field name.
     *
     * @var string
     * @since 1.0.0
     */
    public const PRIMARYFIELD = 'iban_id';
}
