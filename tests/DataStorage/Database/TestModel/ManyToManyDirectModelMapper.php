<?php
/**
 * Orange Management
 *
 * PHP Version 8.0
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
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
        'test_has_many_direct_id'     => ['name' => 'test_has_many_direct_id',     'type' => 'int',    'internal' => 'id'],
        'test_has_many_direct_string' => ['name' => 'test_has_many_direct_string', 'type' => 'string', 'internal' => 'string'],
        'test_has_many_direct_to'     => ['name' => 'test_has_many_direct_to',     'type' => 'int',    'internal' => 'to'],
    ];

    public const TABLE = 'test_has_many_direct';

    public const PRIMARYFIELD ='test_has_many_direct_id';
}
