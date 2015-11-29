<?php
/**
 * Orange Management
 *
 * PHP Version 7.0
 *
 * @category   TBD
 * @package    TBD
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @copyright  2013 Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://orange-management.com
 */

namespace phpOMS\DataStorage\Database;

abstract class Grammar
{

    protected $tablePrefix = '';

    public function getDateFormat() : \string
    {
        return 'Y-m-d H:i:s';
    }

    public function getTablePrefix() : \string
    {
        return $this->tablePrefix;
    }

    public function setTablePrefix(\string $prefix)
    {
        $this->tablePrefix = $prefix;
    }

}
