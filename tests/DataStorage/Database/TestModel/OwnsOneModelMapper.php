<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
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

class OwnsOneModelMapper extends DataMapperAbstract
{

    /**
     * Columns.
     *
     * @var array<string, array<string, bool|string>>
     * @since 1.0.0
     */
    protected static $columns = [
        'test_owns_one_id'          => ['name' => 'test_owns_one_id', 'type' => 'int', 'internal' => 'id'],
        'test_owns_one_string'        => ['name' => 'test_owns_one_string', 'type' => 'string', 'internal' => 'string'],
    ];

    protected static $table = 'test_owns_one';

    protected static $primaryField = 'test_owns_one_id';
}
