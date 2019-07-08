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

class BelongsToModelMapper extends DataMapperAbstract
{

    /**
     * Columns.
     *
     * @var array<string, array<string, bool|string>>
     * @since 1.0.0
     */
    protected static $columns = [
        'test_belongs_to_one_id'          => ['name' => 'test_belongs_to_one_id', 'type' => 'int', 'internal' => 'id'],
        'test_belongs_to_one_string'        => ['name' => 'test_belongs_to_one_string', 'type' => 'string', 'internal' => 'string'],
    ];

    protected static $table = 'test_belongs_to_one';

    protected static $primaryField = 'test_belongs_to_one_id';
}
