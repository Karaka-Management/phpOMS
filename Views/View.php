<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    phpOMS\Views
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */
declare(strict_types=1);

namespace phpOMS\Views;

use phpOMS\ApplicationAbstract;
use phpOMS\Localization\Localization;
use phpOMS\Message\RequestAbstract;
use phpOMS\Message\ResponseAbstract;
use phpOMS\Module\Exception\InvalidModuleException;
use phpOMS\Module\Exception\InvalidThemeException;

/**
 * Basic view which can be used as basis for specific implementations.
 *
 * @package    phpOMS\Views
 * @license    OMS License 1.0
 * @link       http://website.orange-management.de
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
     * @var null|ApplicationAbstract
     * @since 1.0.0
     */
    protected $app = null;

    /**
     * Request.
     *
     * @var null|RequestAbstract
     * @since 1.0.0
     */
    protected $request = null;

    /**
     * Request.
     *
     * @var null|ResponseAbstract
     * @since 1.0.0
     */
    protected $response = null;

    /**
     * Theme name.
     *
     * @var null|string
     * @since 1.0.0
     */
    protected $theme = null;

    /**
     * Module name.
     *
     * @var null|string
     * @since 1.0.0
     */
    protected $module = null;

    /**
     * Constructor.
     *
     * @param ApplicationAbstract $app      Application
     * @param RequestAbstract     $request  Request
     * @param ResponseAbstract    $response Request
     *
     * @since  1.0.0
     */
    public function __construct(ApplicationAbstract $app = null, RequestAbstract $request = null, ResponseAbstract $response = null)
    {
        $this->app      = $app;
        $this->request  = $request;
        $this->response = $response;
        $this->l11n     = $response !== null ? $response->getHeader()->getL11n() : new Localization();
    }

    /**
     * Get data attached to view
     *
     * @param string $id Data Id
     *
     * @return mixed
     *
     * @since  1.0.0
     */
    public function getData(string $id)
    {
        return $this->data[$id] ?? null;
    }

    /**
     * Set data of view
     *
     * @param string $id   Data ID
     * @param mixed  $data Data
     *
     * @return void
     *
     * @since  1.0.0
     */
    public function setData(string $id, $data) : void
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
     * Add data to view
     *
     * @param string $id   Data ID
     * @param mixed  $data Data
     *
     * @return bool
     *
     * @since  1.0.0
     */
    public function addData(string $id, $data) : bool
    {
        if (isset($this->data[$id])) {
            return false;
        }

        $this->data[$id] = $data;

        return true;
    }

    /**
     * Get translation.
     *
     * @param mixed  $translation Text
     * @param string $module      Module name
     * @param string $theme       Theme name
     *
     * @return string
     *
     * @since  1.0.0
     */
    public function getText($translation, string $module = null, string $theme = null) : string
    {
        if ($module === null && $this->module === null) {
            $this->setModuleDynamically();
        }

        if ($theme === null && $this->theme === null) {
            $this->setThemeDynamically();
        }

        $module = $module ?? $this->module;
        $theme  = $theme ?? $this->theme;

        /** @var string $module */
        /** @var string $theme */
        return $this->app->l11nManager->getText($this->l11n->getLanguage(), $module, $theme, $translation);
    }

    /**
     * Set the view module dynamically.
     *
     * Sets the view module based on the template path
     *
     * @return void
     *
     * @throws InvalidModuleException throws this exception if no data for the defined module could be found
     *
     * @since  1.0.0
     */
    private function setModuleDynamically() : void
    {
        $match = '/Modules/';

        if (($start = \strripos($this->template, $match)) === false) {
            throw new InvalidModuleException($this->template);
        }

        $start        = $start + \strlen($match);
        $end          = \strpos($this->template, '/', $start);
        $this->module = \substr($this->template, $start, $end - $start);

        if ($this->module === false) {
            $this->module = '0';
        }
    }

    /**
     * Set the view theme dynamically.
     *
     * Sets the view theme based on the template path
     *
     * @return void
     *
     * @throws InvalidThemeException throws this exception if no data for the defined theme could be found
     *
     * @since  1.0.0
     */
    private function setThemeDynamically() : void
    {
        $match = '/Theme/';

        if (($start = \strripos($this->template, $match)) === false) {
            throw new InvalidThemeException($this->template);
        }

        $start       = $start + \strlen($match);
        $end         = \strpos($this->template, '/', $start);
        $this->theme = \substr($this->template, $start, $end - $start);

        if ($this->theme === false) {
            $this->theme = '0';
        }
    }

    /**
     * Get translation.
     *
     * @param mixed  $translation Text
     * @param string $module      Module name
     * @param string $theme       Theme name
     *
     * @return string
     *
     * @since  1.0.0
     */
    public function getHtml($translation, string $module = null, string $theme = null) : string
    {
        return \htmlspecialchars($this->getText($translation, $module, $theme));
    }

    /**
     * Get request of view
     *
     * @return null|RequestAbstract
     *
     * @since  1.0.0
     */
    public function getRequest() : ?RequestAbstract
    {
        return $this->request;
    }

    /**
     * Get response of view
     *
     * @return null|ResponseAbstract
     *
     * @since  1.0.0
     */
    public function getResponse() : ?ResponseAbstract
    {
        return $this->response;
    }
}
