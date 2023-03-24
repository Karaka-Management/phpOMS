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

class BaseModelMapper extends DataMapperFactory
{
    /**
     * Columns.
     *
     * @var array<string, array{name:string, type:string, internal:string, autocomplete?:bool, readonly?:bool, writeonly?:bool, annotations?:array}>
     * @since 1.0.0
     */
    public const COLUMNS = [
        'test_base_id'                => ['name' => 'test_base_id',                'type' => 'int',      'internal' => 'id'],
        'test_base_string'            => ['name' => 'test_base_string',            'type' => 'string',   'internal' => 'string', 'autocomplete' => true],
        'test_base_int'               => ['name' => 'test_base_int',               'type' => 'int',      'internal' => 'int'],
        'test_base_bool'              => ['name' => 'test_base_bool',              'type' => 'bool',     'internal' => 'bool'],
        'test_base_null'              => ['name' => 'test_base_null',              'type' => 'int',      'internal' => 'null'],
        'test_base_float'             => ['name' => 'test_base_float',             'type' => 'float',    'internal' => 'float'],
        'test_base_json'              => ['name' => 'test_base_json',              'type' => 'Json',     'internal' => 'json'],
        'test_base_json_serializable' => ['name' => 'test_base_json_serializable', 'type' => 'Json',     'internal' => 'jsonSerializable'],
        'test_base_serializable'      => ['name' => 'test_base_serializable', 'type' => 'Serializable',     'internal' => 'serializable'],
        'test_base_datetime'          => ['name' => 'test_base_datetime',          'type' => 'DateTime', 'internal' => 'datetime'],
        'test_base_datetime_null'     => ['name' => 'test_base_datetime_null',     'type' => 'DateTime', 'internal' => 'datetime_null'],
        'test_base_owns_one_self'     => ['name' => 'test_base_owns_one_self',     'type' => 'int',      'internal' => 'ownsOneSelf'],
        'test_base_belongs_to_one'    => ['name' => 'test_base_belongs_to_one',    'type' => 'int',      'internal' => 'belongsToOne'],
    ];

    /**
     * Belongs to.
     *
     * @var array<string, array{mapper:class-string, external:string, column?:string, by?:string}>
     * @since 1.0.0
     */
    public const BELONGS_TO = [
        'belongsToOne' => [
            'mapper'   => BelongsToModelMapper::class,
            'external' => 'test_base_belongs_to_one',
        ],
    ];

    public const OWNS_ONE = [
        'ownsOneSelf' => [
            'mapper'   => OwnsOneModelMapper::class,
            'external' => 'test_base_owns_one_self',
        ],
    ];

    /**
     * Has many relation.
     *
     * @var array<string, array{mapper:class-string, table:string, self?:?string, external?:?string, column?:string}>
     * @since 1.0.0
     */
    public const HAS_MANY = [
        'hasManyDirect' => [
            'mapper'   => ManyToManyDirectModelMapper::class,
            'table'    => 'test_has_many_direct',
            'self'     => 'test_has_many_direct_to',
            'external' => null,
        ],
        'hasManyRelations' => [
            'mapper'   => ManyToManyRelModelMapper::class,
            'table'    => 'test_has_many_rel_relations',
            'external' => 'test_has_many_rel_relations_src',
            'self'     => 'test_has_many_rel_relations_dest',
        ],
        'conditional' => [
            'mapper'   => ConditionalMapper::class,
            'table'    => 'test_conditional',
            'self'     => 'test_conditional_base',
            'column'   => 'title',
            'external' => null,
        ],
    ];

    /**
     * Primary table.
     *
     * @var string
     * @since 1.0.0
     */
    public const TABLE = 'test_base';

    /**
     * Created at.
     *
     * @var string
     * @since 1.0.0
     */
    public const CREATED_AT = 'test_base_datetime';

    /**
     * Primary field name.
     *
     * @var string
     * @since 1.0.0
     */
    public const PRIMARYFIELD = 'test_base_id';
}
