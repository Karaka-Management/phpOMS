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
namespace phpOMS\DataStorage\Database\Connection;

use phpOMS\DataStorage\Database\DatabaseStatus;
use phpOMS\DataStorage\Database\Query\Grammar\Grammar;

/**
 * Database handler.
 *
 * Handles the database connection.
 * Implementing wrapper functions for multiple databases is planned (far away).
 *
 * @category   Framework
 * @package    phpOMS\DataStorage\Database
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
abstract class ConnectionAbstract implements ConnectionInterface
{

    /**
     * Connection object.
     *
     * This can be used externally to define queries and execute them.
     *
     * @var \PDO
     * @since 1.0.0
     */
    public $con = null;

    /**
     * Database prefix.
     *
     * The database prefix name for unique table names
     *
     * @var \string
     * @since 1.0.0
     */
    public $prefix = '';

    /**
     * Database data.
     *
     * @var \string[]
     * @since 1.0.0
     */
    protected $dbdata = null;

    /**
     * Database type.
     *
     * @var \phpOMS\DataStorage\Database\DatabaseType
     * @since 1.0.0
     */
    protected $type = null;

    /**
     * Database status.
     *
     * @var DatabaseStatus
     * @since 1.0.0
     */
    protected $status = DatabaseStatus::CLOSED;

    /**
     * Database grammar.
     *
     * @var Grammar
     * @since 1.0.0
     */
    protected $grammar = null;

    /**
     * {@inheritdoc}
     */
    public function getType() : \int
    {
        return $this->type;
    }

    /**
     * {@inheritdoc}
     */
    public function getStatus() : \int
    {
        return $this->status;
    }

    /**
     * Get table prefix.
     *
     * @return \string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getPrefix() : \string
    {
        return $this->prefix;
    }

    /**
     * {@inheritdoc}
     */
    public function getGrammar() : Grammar
    {
        if (!isset($this->grammar)) {
            $this->grammar = new Grammar();
        }

        return $this->grammar;
    }

    /**
     * Object destructor.
     *
     * Sets the database connection to null
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function __destruct()
    {
        $this->close();
    }

    /**
     * {@inheritdoc}
     */
    public function close()
    {
        $this->con    = null;
        $this->status = DatabaseStatus::CLOSED;
    }

}
