<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    TBD
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */
declare(strict_types=1);
namespace phpOMS\tests\DataStorage\Database\TestModel;

use phpOMS\DataStorage\Database\DataMapperAbstract;

class ManyToManyDirectModelMapper extends DataMapperAbstract
{

    /**
     * Columns.
     *
     * @var array<string, array<string, bool|string>>
     * @since 1.0.0
     */
    protected static $columns = [
        'test_has_many_direct_id'          => ['name' => 'test_has_many_direct_id', 'type' => 'int', 'internal' => 'id'],
        'test_has_many_direct_string'        => ['name' => 'test_has_many_direct_string', 'type' => 'string', 'internal' => 'string'],
        'test_has_many_direct_to'        => ['name' => 'test_has_many_direct_to', 'type' => 'int', 'internal' => 'to'],
    ];

    protected static $table = 'test_has_many_direct';

    protected static $primaryField = 'test_has_many_direct_id';
}
