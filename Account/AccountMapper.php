<?php
/**
 * Orange Management
 *
 * PHP Version 7.1
 *
 * @category   TBD
 * @package    TBD
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://orange-management.com
 */
declare(strict_types=1);

namespace phpOMS\Account;

use phpOMS\DataStorage\Database\DataMapperAbstract;
use phpOMS\DataStorage\Database\Query\Builder;
use phpOMS\DataStorage\Database\Query\Column;
use phpOMS\DataStorage\Database\RelationType;

class AccountMapper extends DataMapperAbstract
{
    /**
     * Columns.
     *
     * @var array
     * @since 1.0.0
     */
    protected static $columns = [
        'account_id'         => ['name' => 'account_id', 'type' => 'int', 'internal' => 'id'],
        'account_status'     => ['name' => 'account_status', 'type' => 'int', 'internal' => 'status'],
        'account_type'       => ['name' => 'account_type', 'type' => 'int', 'internal' => 'type'],
        'account_login'      => ['name' => 'account_login', 'type' => 'string', 'internal' => 'login'],
        'account_name1'      => ['name' => 'account_name1', 'type' => 'string', 'internal' => 'name1'],
        'account_name2'      => ['name' => 'account_name2', 'type' => 'string', 'internal' => 'name2'],
        'account_name3'      => ['name' => 'account_name3', 'type' => 'string', 'internal' => 'name3'],
        'account_email'      => ['name' => 'account_email', 'type' => 'string', 'internal' => 'email'],
        'account_lactive'    => ['name' => 'account_lactive', 'type' => 'DateTime', 'internal' => 'lastActive'],
        'account_created_at' => ['name' => 'account_created_at', 'type' => 'DateTime', 'internal' => 'createdAt'],
    ];

    protected static $hasMany = [
    ];

    /**
     * Primary table.
     *
     * @var string
     * @since 1.0.0
     */
    protected static $table = 'account';

    /**
     * Created at.
     *
     * @var string
     * @since 1.0.0
     */
    protected static $createdAt = 'account_created_at';

    /**
     * Primary field name.
     *
     * @var string
     * @since 1.0.0
     */
    protected static $primaryField = 'account_id';

    /**
     * Create object.
     *
     * @param mixed $obj       Object
     * @param int   $relations Behavior for relations creation
     *
     * @return mixed
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function create($obj, int $relations = RelationType::ALL)
    {
        try {
            $objId = parent::create($obj, $relations);
        } catch (\Exception $e) {
            return false;
        }

        return $objId;
    }

    /**
     * Find.
     *
     * @param array $columns Columns to select
     *
     * @return Builder
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function find(...$columns) : Builder
    {
        return parent::find(...$columns)->from('account_permission')
            ->where('account_permission.account_permission_for', '=', 'task')
            ->where('account_permission.account_permission_id1', '=', 1)
            ->where('task.task_id', '=', new Column('account_permission.account_permission_id2'))
            ->where('account_permission.account_permission_r', '=', 1);
    }
}
