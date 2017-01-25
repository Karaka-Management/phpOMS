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
namespace phpOMS\Message;

/**
 * Message interface.
 *
 * @category   Framework
 * @package    phpOMS\Response
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
interface MessageInterface
{

    /**
     * Retrieves the HTTP protocol version as a string.
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getProtocolVersion();

    /**
     * Retrieves all message header values.
     *
     * @return HeaderAbstract
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getHeader() : HeaderAbstract;

    /**
     * Gets the body of the message.
     *
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getBody() : string;

    /**
     * Set status code.
     *
     * @param string $status Status code
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function setStatusCode(string $status) /* : void */;

    /**
     * Get status code.
     *
     * @return string Status code
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getStatusCode() : string;

    /**
     * Get account id.
     *
     * @return int Account id
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getAccount() : int;

    /**
     * Set account id.
     *
     * @param int $account Account id
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function setAccount(int $account) /* : void */;
}
