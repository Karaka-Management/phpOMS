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

class ManyToManyRelModelMapper extends DataMapperAbstract
{

    /**
     * Columns.
     *
     * @var   array<string, array<string, bool|string>>
     * @since 1.0.0
     */
    protected static array $columns = [
        'test_has_many_rel_id'          => ['name' => 'test_has_many_rel_id', 'type' => 'int', 'internal' => 'id'],
        'test_has_many_rel_string'        => ['name' => 'test_has_many_rel_string', 'type' => 'string', 'internal' => 'string'],
    ];

    protected static string $table = 'test_has_many_rel';

    protected static string $primaryField = 'test_has_many_rel_id';
}
