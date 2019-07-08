<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    phpOMS\DataStorage\Database
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\DataStorage\Database;

use phpOMS\DataStorage\Database\Schema\Exception\TableException;

/**
 * Database exception factory.
 *
 * @package    phpOMS\DataStorage\Database
 * @license    OMS License 1.0
 * @link       https://orange-management.org
 * @since      1.0.0
 */
class DatabaseExceptionFactory
{
    /**
     * Create exception class string based on exception.
     *
     * @param \PDOException $e Exception
     *
     * @return string Returns exception namespace/class
     *
     * @since  1.0.0
     */
    public static function createException(\PDOException $e) : string
    {
        switch ($e->getCode()) {
            case '42S02':
                return '\phpOMS\DataStorage\Database\Schema\Exception\TableException';
            default:
                return '\PDOException';
        }
    }

    /**
     * Create exception message based on exception.
     *
     * @param \PDOException $e Exception
     *
     * @return string Returns exception pessage
     *
     * @since  1.0.0
     */
    public static function createExceptionMessage(\PDOException $e) : string
    {
        switch ($e->getCode()) {
            case '42S02':
                return TableException::findTable($e->getMessage());
            default:
                return $e->getMessage();
        }
    }
}
