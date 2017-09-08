<?php
/**
 * Orange Management
 *
 * PHP Version 7.1
 *
 * @category   TBD
 * @package    TBD
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
    public static function createException(\PDOException $e) : \PDOException
    {
        switch ($e->getCode()) {
            case '42S02':
                return self::createTableViewException($e);
            default:
                return $e;
        }
    }

    /**
     * Constructor.
     *
     * @param \PDOException $e Exception
     *
     * @return \PDOException
     *
     * @since  1.0.0
     */
    public static function createExceptionMessage(\PDOException $e) : \PDOException
    {
        switch ($e->getCode()) {
            case '42S02':
                return self::createTableViewExceptionMessage($e);
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
    private static function createTableViewException(\PDOException $e) : string
    {
        return TableException::findTable($e->getMessage());
    }
}
