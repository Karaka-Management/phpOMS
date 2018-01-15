<?php
/**
 * Orange Management
 *
 * PHP Version 7.1
 *
 * @package    TBD
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */
declare(strict_types = 1);
namespace phpOMS\tests\DataStorage\Database\TestModel;


use phpOMS\DataStorage\Database\DataMapperAbstract;
use phpOMS\DataStorage\Database\Query\Builder;
use phpOMS\DataStorage\Database\Query\Column;
use phpOMS\DataStorage\Database\RelationType;

class ManyToManyDirectModelMapper extends DataMapperAbstract
{

    /**
     * Columns.
     *
     * @var array
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
