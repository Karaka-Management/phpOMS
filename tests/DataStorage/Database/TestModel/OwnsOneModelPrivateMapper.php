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

class OwnsOneModelPrivateMapper extends DataMapperFactory
{
    /**
     * Columns.
     *
     * @var array<string, array{name:string, type:string, internal:string, autocomplete?:bool, readonly?:bool, writeonly?:bool, annotations?:array}>
     * @since 1.0.0
     */
    public const COLUMNS = [
        'test_owns_onep_id'     => ['name' => 'test_owns_onep_id',     'type' => 'int',    'internal' => 'id'],
        'test_owns_onep_string' => ['name' => 'test_owns_onep_string', 'type' => 'string', 'internal' => 'string'],
    ];

    /**
     * Primary table.
     *
     * @var string
     * @since 1.0.0
     */
    public const TABLE = 'test_owns_onep';

    /**
     * Primary field name.
     *
     * @var string
     * @since 1.0.0
     */
    public const PRIMARYFIELD = 'test_owns_onep_id';

    public const MODEL = OwnsOneModel::class;
}
