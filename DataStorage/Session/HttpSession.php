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
namespace phpOMS\DataStorage\Session;
use phpOMS\Uri\UriFactory;
use phpOMS\Utils\RnG\StringUtils;

/**
 * Http session class.
 *
 * @category   Framework
 * @package    phpOMS\DataStorage\Session
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
class HttpSession implements SessionInterface
{

    private $sessionData = [];

    /**
     * Session ID.
     *
     * @var \string|int
     * @since 1.0.0
     */
    private $sid = null;

    /**
     * Constructor.
     *
     * @param \int              $liftetime Session life time
     * @param \string|int|\bool $sid       Session id
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function __construct(\int $liftetime = 3600, $sid = false)
    {
        if ($sid !== false) {
            session_id($sid);
        }

        session_set_cookie_params($liftetime, '/', null, false, true);
        session_start();
        $this->sessionData = $_SESSION;
        $_SESSION = null;

        $this->sid = session_id();
        session_write_close();

        $CSRF = StringUtils::generateString(10, 16);
        $this->set('CSRF', $CSRF, false);
        UriFactory::setQuery('$CSRF', $CSRF);
    }

    /**
     * {@inheritdoc}
     */
    public function get($key)
    {
        return $this->sessionData[$key] ?? null;
    }

    /**
     * {@inheritdoc}
     */
    public function set($key, $value, \bool $overwrite = true) : \bool
    {
        if($overwrite || !isset($this->sessionData[$key])) {
            $this->sessionData[$key] = $value;

            return true;
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function save()
    {
        session_id($this->sid);
        session_start();
        $_SESSION = $this->sessionData;
        session_write_close();
    }

    /**
     * {@inheritdoc}
     */
    public function remove($key) : \bool
    {
        if (isset($this->sessionData[$key])) {
            unset($this->sessionData[$key]);

            return true;
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function getSID()
    {
        return $this->sid;
    }

    /**
     * {@inheritdoc}
     */
    public function setSID($sid)
    {
        $this->sid = $sid;
    }

}
