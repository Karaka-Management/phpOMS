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
use phpOMS\DataStorage\Database\Query\Builder;
use phpOMS\DataStorage\Database\Query\Column;
use phpOMS\DataStorage\Database\RelationType;

class OwnsOneModelMapper extends DataMapperAbstract
{

    /**
     * Columns.
     *
     * @var array<string, array<string, string>>
     * @since 1.0.0
     */
    protected static $columns = [
        'test_owns_one_id'          => ['name' => 'test_owns_one_id', 'type' => 'int', 'internal' => 'id'],
        'test_owns_one_string'        => ['name' => 'test_owns_one_string', 'type' => 'string', 'internal' => 'string'],
    ];

    protected static $table = 'test_owns_one';

    protected static $primaryField = 'test_owns_one_id';
}
