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
namespace phpOMS\DataStorage\Database\Connection;

use phpOMS\DataStorage\Database\Query\Grammar\Grammar;
use phpOMS\DataStorage\Database\Schema\Grammar\Grammar as SchemaGrammar;

/**
 * Database connection interface.
 *
 * @category   Framework
 * @package    phpOMS\DataStorage\Database
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
interface ConnectionInterface
{

    /**
     * Connect to database.
     *
     * Overwrites current connection if existing
     *
     * @param string[] $dbdata the basic database information for establishing a connection
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function connect(array $dbdata) /* : void */;

    /**
     * Get the database type.
     *
     * @return int
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getType() : int;

    /**
     * Get the database status.
     *
     * @return int
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getStatus() : int;

    /**
     * Close database connection.
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function close() /* : void */;

    /**
     * Return grammar for this connection.
     *
     * @return Grammar
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getGrammar() : Grammar;

    /**
     * Return grammar for this connection.
     *
     * @return SchemaGrammar
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getSchemaGrammar() : SchemaGrammar;

}
