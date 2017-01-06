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
 * @copyright  2013 Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://orange-management.com
 */
namespace phpOMS\Message;

use phpOMS\Localization\Localization;
use phpOMS\Utils\ArrayUtils;

/**
 * Response abstract class.
 *
 * @category   Framework
 * @package    phpOMS\Response
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
abstract class ResponseAbstract implements MessageInterface, \JsonSerializable
{

    /**
     * Localization.
     *
     * @var Localization
     * @since 1.0.0
     */
    protected $l11n = null;

    /**
     * Responses.
     *
     * @var string[]
     * @since 1.0.0
     */
    protected $response = [];

    /**
     * Response status.
     *
     * @var string
     * @since 1.0.0
     */
    protected $status = 200;

    /**
     * Account.
     *
     * @var int
     * @since 1.0.0
     */
    protected $account = null;

    /**
     * Header.
     *
     * @var HeaderAbstract
     * @since 1.0.0
     */
    protected $header = null;

    /**
     * {@inheritdoc}
     */
    public function getL11n() : Localization
    {
        return $this->l11n;
    }

    /**
     * Get response by ID.
     *
     * @param mixed $id Response ID
     *
     * @return mixed
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function &get($id)
    {
        return $this->response[$id];
    }

    /**
     * Add response.
     *
     * @param mixed $key       Response id
     * @param mixed $response  Response to add
     * @param bool  $overwrite Overwrite
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function set($key, $response, bool $overwrite = true) /* : void */
    {
        $this->response = ArrayUtils::setArray($key, $this->response, $response, ':', $overwrite);
    }

    /**
     * {@inheritdoc}
     * todo: shouldn't this only be available in the header?!
     */
    public function setStatusCode(string $status) /* : void */
    {
        $this->status = $status;
        $this->header->generate($status);
    }

    /**
     * {@inheritdoc}
     * todo: shouldn't this only be available in the header?!
     */
    public function getStatusCode() : string
    {
        return $this->status;
    }

    /**
     * {@inheritdoc}
     */
    public function getAccount() : int
    {
        return $this->account;
    }

    /**
     * {@inheritdoc}
     */
    public function setAccount(int $account) /* : void */
    {
        $this->account = $account;
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize()
    {
        return json_encode($this->toArray());
    }

    /**
     * Generate response array from views.
     *
     * @return array
     *
     * @throws \Exception
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    abstract public function toArray() : array;

    /**
     * Get header.
     *
     * @return HeaderAbstract
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getHeader() : HeaderAbstract
    {
        return $this->header;
    }

    /**
     * Get response body.
     *
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    abstract public function getBody() : string;
}
