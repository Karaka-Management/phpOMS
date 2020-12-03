<?php
/**
 * Orange Management
 *
 * PHP Version 8.0
 *
 * @package   Modules\Tag\Models
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\tests\DataStorage\Database\TestModel;

use phpOMS\DataStorage\Database\DataMapperAbstract;

/**
 * Tag mapper class.
 *
 * @package Modules\Tag\Models
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
final class ConditionalMapper extends DataMapperAbstract
{
    /**
     * Columns.
     *
     * @var array<string, array{name:string, type:string, internal:string, autocomplete?:bool, readonly?:bool, writeonly?:bool, annotations?:array}>
     * @since 1.0.0
     */
    protected static array $columns = [
        'test_conditional_id'       => ['name' => 'test_conditional_id',       'type' => 'int',    'internal' => 'id'],
        'test_conditional_title'    => ['name' => 'test_conditional_title',    'type' => 'string', 'internal' => 'title', 'autocomplete' => true],
        'test_conditional_base'     => ['name' => 'test_conditional_base',      'type' => 'int',    'internal' => 'base'],
        'test_conditional_language' => ['name' => 'test_conditional_language', 'type' => 'string', 'internal' => 'language'],
    ];

    /**
     * Primary table.
     *
     * @var string
     * @since 1.0.0
     */
    protected static string $table = 'test_conditional';

    /**
     * Primary field name.
     *
     * @var string
     * @since 1.0.0
     */
    protected static string $primaryField = 'test_conditional_id';
}
