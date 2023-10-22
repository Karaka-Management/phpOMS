<?php
/**
 * Jingga
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

class ManyToManyDirectModelMapper extends DataMapperFactory
{
    /**
     * Columns.
     *
     * @var array<string, array{name:string, type:string, internal:string, autocomplete?:bool, readonly?:bool, writeonly?:bool, annotations?:array}>
     * @since 1.0.0
     */
    public const COLUMNS = [
        'test_has_many_directp_id'     => ['name' => 'test_has_many_directp_id',     'type' => 'int',    'internal' => 'id'],
        'test_has_many_directp_string' => ['name' => 'test_has_many_directp_string', 'type' => 'string', 'internal' => 'string'],
        'test_has_many_directp_to'     => ['name' => 'test_has_many_directp_to',     'type' => 'int',    'internal' => 'to'],
    ];

    /**
     * Primary table.
     *
     * @var string
     * @since 1.0.0
     */
    public const TABLE = 'test_has_many_directp';

    /**
     * Primary field name.
     *
     * @var string
     * @since 1.0.0
     */
    public const PRIMARYFIELD = 'test_has_many_directp_id';

    public const MODEL = ManyToManyDirectModel::class;
}
