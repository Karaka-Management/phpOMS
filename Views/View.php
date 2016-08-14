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
namespace phpOMS\Views;

use phpOMS\ApplicationAbstract;
use phpOMS\Localization\Localization;
use phpOMS\Message\RequestAbstract;
use phpOMS\Message\ResponseAbstract;
use phpOMS\System\File\PathException;
use phpOMS\Utils\StringUtils;

/**
 * List view.
 *
 * @category   Framework
 * @package    phpOMS/Views
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
class View extends ViewAbstract
{
    /**
     * View data.
     *
     * @var array
     * @since 1.0.0
     */
    protected $data = [];

    /**
     * View Localization.
     *
     * @var Localization
     * @since 1.0.0
     */
    protected $l11n = null;

    /**
     * Application.
     *
     * @var ApplicationAbstract
     * @since 1.0.0
     */
    protected $app = null;

    /**
     * Request.
     *
     * @var RequestAbstract
     * @since 1.0.0
     */
    protected $request = null;

    /**
     * Request.
     *
     * @var ResponseAbstract
     * @since 1.0.0
     */
    protected $response = null;

    /**
     * Constructor.
     *
     * @param ApplicationAbstract $app      Application
     * @param RequestAbstract     $request  Request
     * @param ResponseAbstract    $response Request
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function __construct(ApplicationAbstract $app, RequestAbstract $request, ResponseAbstract $response)
    {
        $this->app      = $app;
        $this->request  = $request;
        $this->response = $response;
        $this->l11n     = $response->getL11n();
    }

    /**
     * @param string $id Data Id
     *
     * @return mixed
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getData($id)
    {
        return $this->data[$id] ?? null;
    }

    /**
     * @param string $id   Data ID
     * @param mixed  $data Data
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function setData(string $id, $data)
    {
        $this->data[$id] = $data;
    }

    /**
     * Remove view.
     *
     * @param string $id Data Id
     *
     * @return bool
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function removeData(string $id) : bool
    {
        if (isset($this->data[$id])) {
            unset($this->data[$id]);

            return true;
        }

        return false;
    }

    /**
     * @param string $id   Data ID
     * @param mixed  $data Data
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function addData(string $id, $data)
    {
        $this->data[$id] = $data;
    }

    /**
     * Get translation.
     *
     * @param string $module      Module name
     * @param string $theme       Theme name
     * @param string $translation Text
     *
     * @return array
     *
     * @throws
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    private function getText(string $translation, string $module = null, string $theme = null)
    {
        if (!isset($module)) {
            $match = '/Modules/';

            if (($start = strripos($this->template, $match)) === false) {
                throw new \Exception('Unknown Module');
            }

            $start  = $start + strlen($match);
            $end    = strpos($this->template, '/', $start);
            $module = substr($this->template, $start, $end - $start);
        }

        if (!isset($theme)) {
            $match = '/Theme/';

            if (($start = strripos($this->template, $match)) === false) {
                throw new \Exception('Unknown Theme');
            }

            $start = $start + strlen($match);
            $end   = strpos($this->template, '/', $start);
            $theme = substr($this->template, $start, $end - $start);
        }

        return $this->app->l11nManager->getText($this->l11n->getLanguage(), $module, $theme, $translation);
    }

}
