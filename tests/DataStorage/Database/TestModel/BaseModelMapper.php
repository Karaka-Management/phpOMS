<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
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

class BaseModelMapper extends DataMapperAbstract
{
    /**
     * Columns.
     *
     * @var array<string, array{name:string, type:string, internal:string, autocomplete?:bool, readonly?:bool, writeonly?:bool, annotations?:array}>
     * @since 1.0.0
     */
    protected static array $columns = [
        'test_base_id'                => ['name' => 'test_base_id',                'type' => 'int',      'internal' => 'id'],
        'test_base_string'            => ['name' => 'test_base_string',            'type' => 'string',   'internal' => 'string', 'autocomplete' => true],
        'test_base_int'               => ['name' => 'test_base_int',               'type' => 'int',      'internal' => 'int'],
        'test_base_bool'              => ['name' => 'test_base_bool',              'type' => 'bool',     'internal' => 'bool'],
        'test_base_null'              => ['name' => 'test_base_null',              'type' => 'int',      'internal' => 'null'],
        'test_base_float'             => ['name' => 'test_base_float',             'type' => 'float',    'internal' => 'float'],
        'test_base_json'              => ['name' => 'test_base_json',              'type' => 'Json',     'internal' => 'json'],
        'test_base_json_serializable' => ['name' => 'test_base_json_serializable', 'type' => 'Json',     'internal' => 'jsonSerializable'],
        'test_base_datetime'          => ['name' => 'test_base_datetime',          'type' => 'DateTime', 'internal' => 'datetime'],
        'test_base_datetime_null'     => ['name' => 'test_base_datetime_null',     'type' => 'DateTime', 'internal' => 'datetime_null'],
        'test_base_owns_one_self'     => ['name' => 'test_base_owns_one_self',     'type' => 'int',      'internal' => 'ownsOneSelf'],
        'test_base_belongs_to_one'    => ['name' => 'test_base_belongs_to_one',    'type' => 'int',      'internal' => 'belongsToOne'],
    ];

    /**
     * Belongs to.
     *
     * @var array<string, array{mapper:string, self:string}>
     * @since 1.0.0
     */
    protected static array $belongsTo = [
        'belongsToOne' => [
            'mapper' => BelongsToModelMapper::class,
            'self'   => 'test_base_belongs_to_one',
        ],
    ];

    protected static array $ownsOne = [
        'ownsOneSelf' => [
            'mapper' => OwnsOneModelMapper::class,
            'self'   => 'test_base_owns_one_self',
        ],
    ];

    /**
     * Has many relation.
     *
     * @var array<string, array{mapper:string, table:string, self?:?string, external?:?string, column?:string}>
     * @since 1.0.0
     */
    protected static array $hasMany = [
        'hasManyDirect' => [
            'mapper'         => ManyToManyDirectModelMapper::class,
            'table'          => 'test_has_many_direct',
            'external'       => 'test_has_many_direct_to',
            'self'           => null,
        ],
        'hasManyRelations' => [
            'mapper'         => ManyToManyRelModelMapper::class,
            'table'          => 'test_has_many_rel_relations',
            'external'       => 'test_has_many_rel_relations_dest',
            'self'           => 'test_has_many_rel_relations_src',
        ],
        'conditional' => [
            'mapper'   => ConditionalMapper::class,
            'table'    => 'test_conditional',
            'external' => 'test_conditional_base',
            'column'   => 'title',
            'self'     => null,
        ],
    ];

    protected static string $table = 'test_base';

    protected static string $createdAt = 'test_base_datetime';

    protected static string $primaryField = 'test_base_id';
}
