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

use phpOMS\DataStorage\Database\DataMapperAbstract;

class BelongsToModelMapper extends DataMapperAbstract
{
    /**
     * Columns.
     *
     * @var array<string, array{name:string, type:string, internal:string, autocomplete?:bool, readonly?:bool, writeonly?:bool, annotations?:array}>
     * @since 1.0.0
     */
    protected static array $columns = [
        'test_belongs_to_one_id'     => ['name' => 'test_belongs_to_one_id',     'type' => 'int',    'internal' => 'id'],
        'test_belongs_to_one_string' => ['name' => 'test_belongs_to_one_string', 'type' => 'string', 'internal' => 'string'],
    ];

    protected static string $table = 'test_belongs_to_one';

    protected static string $primaryField = 'test_belongs_to_one_id';
}
