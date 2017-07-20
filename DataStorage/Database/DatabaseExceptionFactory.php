<?php
/**
 * Orange Management
 *
 * PHP Version 7.1
 *
 * @category   TBD
 * @package    TBD
 * @author     OMS Development Team <dev@oms.com>
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://orange-management.com
 */
declare(strict_types=1);

namespace phpOMS\DataStorage\Database;

use phpOMS\DataStorage\Database\Schema\Exception\TableException;

/**
 * Database exception factory.
 *
 * @category   Framework
 * @package    phpOMS\DataStorage\Database
 * @author     OMS Development Team <dev@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
class DatabaseExceptionFactory
{
    /**
     * Constructor.
     *
     * @param \PDOException $e Exception
     *
     * @return \PDOException
     *
     * @since  1.0.0
     */
    public static function create(\PDOException $e) : \PDOException
    {
        switch ($e->getCode()) {
            case '42S02':
                return self::createTableViewException($e);
            default:
                return $e;
        }
    }

    /**
     * Create table exception.
     *
     * @param \PDOException $e Exception
     *
     * @return \PDOException
     *
     * @since  1.0.0
     */
    private static function createTableViewException(\PDOException $e) : \PDOException
    {
        return new TableException(TableException::findTable($e->getMessage()));
    }
}
