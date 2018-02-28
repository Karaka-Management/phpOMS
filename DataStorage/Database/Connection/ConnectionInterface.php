<?php
/**
 * Orange Management
 *
 * PHP Version 7.1
 *
 * @package    phpOMS\DataStorage\Database\Connection
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */
declare(strict_types=1);

namespace phpOMS\DataStorage\Database\Connection;

use phpOMS\DataStorage\Database\Query\Grammar\Grammar;
use phpOMS\DataStorage\Database\Schema\Grammar\Grammar as SchemaGrammar;

/**
 * Database connection interface.
 *
 * @package    phpOMS\DataStorage\Database\Connection
 * @license    OMS License 1.0
 * @link       http://website.orange-management.de
 * @since      1.0.0
 */
interface ConnectionInterface extends DataStorageConnectionInterface
{
    /**
     * Return grammar for this connection.
     *
     * @return Grammar
     *
     * @since  1.0.0
     */
    public function getGrammar() : Grammar;

    /**
     * Return grammar for this connection.
     *
     * @return SchemaGrammar
     *
     * @since  1.0.0
     */
    public function getSchemaGrammar() : SchemaGrammar;
}
