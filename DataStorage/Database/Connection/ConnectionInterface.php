<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\DataStorage\Database\Connection
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\DataStorage\Database\Connection;

use phpOMS\DataStorage\Database\Query\Grammar\Grammar;
use phpOMS\DataStorage\Database\Schema\Grammar\Grammar as SchemaGrammar;
use phpOMS\DataStorage\DataStorageConnectionInterface;

/**
 * Database connection interface.
 *
 * @package phpOMS\DataStorage\Database\Connection
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
interface ConnectionInterface extends DataStorageConnectionInterface
{
    /**
     * Return grammar for this connection.
     *
     * @return Grammar
     *
     * @since 1.0.0
     */
    public function getGrammar() : Grammar;

    /**
     * Return grammar for this connection.
     *
     * @return SchemaGrammar
     *
     * @since 1.0.0
     */
    public function getSchemaGrammar() : SchemaGrammar;
}
