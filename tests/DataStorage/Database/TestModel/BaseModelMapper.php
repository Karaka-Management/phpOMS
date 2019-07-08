<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    tests
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       https://orange-management.org
 */
declare(strict_types=1);
namespace phpOMS\tests\DataStorage\Database\TestModel;

use phpOMS\DataStorage\Database\DataMapperAbstract;

class BaseModelMapper extends DataMapperAbstract
{

    /**
     * Columns.
     *
     * @var array<string, array<string, bool|string>>
     * @since 1.0.0
     */
    protected static $columns = [
        'test_base_id'                => ['name' => 'test_base_id', 'type' => 'int', 'internal' => 'id'],
        'test_base_string'            => ['name' => 'test_base_string', 'type' => 'string', 'internal' => 'string'],
        'test_base_int'               => ['name' => 'test_base_int', 'type' => 'int', 'internal' => 'int'],
        'test_base_bool'              => ['name' => 'test_base_bool', 'type' => 'bool', 'internal' => 'bool'],
        'test_base_null'              => ['name' => 'test_base_null', 'type' => 'int', 'internal' => 'null'],
        'test_base_float'             => ['name' => 'test_base_float', 'type' => 'float', 'internal' => 'float'],
        'test_base_json'              => ['name' => 'test_base_json', 'type' => 'Json', 'internal' => 'json'],
        'test_base_json_serializable' => ['name' => 'test_base_json_serializable', 'type' => 'Json', 'internal' => 'jsonSerializable'],
        'test_base_datetime'          => ['name' => 'test_base_datetime', 'type' => 'DateTime', 'internal' => 'datetime'],
        'test_base_owns_one_self'     => ['name' => 'test_base_owns_one_self', 'type' => 'int', 'internal' => 'ownsOneSelf'],
        'test_base_belongs_to_one'    => ['name' => 'test_base_belongs_to_one', 'type' => 'int', 'internal' => 'belongsToOne'],
    ];

    /**
     * Belongs to.
     *
     * @var array<string, array<string, string>>
     * @since 1.0.0
     */
    protected static $belongsTo = [
        'belongsToOne' => [
            'mapper'         => BelongsToModelMapper::class,
            'dest'            => 'test_base_belongs_to_one',
        ],
    ];

    protected static $ownsOne = [
        'ownsOneSelf' => [
            'mapper'         => OwnsOneModelMapper::class,
            'dest'            => 'test_base_owns_one_self',
        ],
    ];    /**
     * Has many relation.
     *
     * @var array<string, array<string, null|string>>
     * @since 1.0.0
     */
    protected static $hasMany = [
        'hasManyDirect' => [
            'mapper'         => ManyToManyDirectModelMapper::class,
            'table'          => 'test_has_many_direct',
            'dst'            => 'test_has_many_direct_to',
            'src'            => null,
        ],
        'hasManyRelations' => [
            'mapper'         => ManyToManyRelModelMapper::class,
            'table'          => 'test_has_many_rel_relations',
            'dst'            => 'test_has_many_rel_relations_dest',
            'src'            => 'test_has_many_rel_relations_src',
        ],
    ];

    protected static $table = 'test_base';

    protected static $createdAt = 'test_base_datetime';

    protected static $primaryField = 'test_base_id';
}
