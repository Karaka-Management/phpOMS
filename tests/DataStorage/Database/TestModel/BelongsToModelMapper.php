<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\tests\DataStorage\Database\TestModel;

use phpOMS\DataStorage\Database\Mapper\DataMapperFactory;

class BelongsToModelMapper extends DataMapperFactory
{
    /**
     * Columns.
     *
     * @var array<string, array{name:string, type:string, internal:string, autocomplete?:bool, readonly?:bool, writeonly?:bool, annotations?:array}>
     * @since 1.0.0
     */
    public const COLUMNS = [
        'test_belongs_to_one_id'     => ['name' => 'test_belongs_to_one_id',     'type' => 'int',    'internal' => 'id'],
        'test_belongs_to_one_string' => ['name' => 'test_belongs_to_one_string', 'type' => 'string', 'internal' => 'string'],
    ];

    /**
     * Primary table.
     *
     * @var string
     * @since 1.0.0
     */
    public const TABLE = 'test_belongs_to_one';

    /**
     * Primary field name.
     *
     * @var string
     * @since 1.0.0
     */
    public const PRIMARYFIELD = 'test_belongs_to_one_id';
}
