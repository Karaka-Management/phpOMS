<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   phpOMS\Views
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Views;

use phpOMS\Localization\L11nManager;
use phpOMS\Localization\Localization;
use phpOMS\Message\RequestAbstract;
use phpOMS\Message\ResponseAbstract;
use phpOMS\Module\Exception\InvalidModuleException;
use phpOMS\Module\Exception\InvalidThemeException;

/**
 * Basic view which can be used as basis for specific implementations.
 *
 * @package phpOMS\Views
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
class View extends ViewAbstract
{
    /**
     * View data.
     *
     * @var array<string, mixed>
     * @since 1.0.0
     */
    protected array $data = [];

    /**
     * View Localization.
     *
     * @var Localization
     * @since 1.0.0
     */
    protected Localization $l11n;

    /**
     * Application.
     *
     * @var L11nManager
     * @since 1.0.0
     */
    protected L11nManager $l11nManager;

    /**
     * Request.
     *
     * @var null|RequestAbstract
     * @since 1.0.0
     */
    protected ?RequestAbstract $request;

    /**
     * Request.
     *
     * @var null|ResponseAbstract
     * @since 1.0.0
     */
    protected ?ResponseAbstract $response;

    /**
     * Theme name.
     *
     * @var null|string
     * @since 1.0.0
     */
    protected ?string $theme = null;

    /**
     * Module name.
     *
     * @var null|string
     * @since 1.0.0
     */
    protected ?string $module = null;

    /**
     * Constructor.
     *
     * @param L11nManager      $l11n     Localization manager
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Request
     *
     * @since 1.0.0
     */
    public function __construct(L11nManager $l11n = null, RequestAbstract $request = null, ResponseAbstract $response = null)
    {
        $this->l11nManager = $l11n ?? new L11nManager('Error');
        $this->request     = $request;
        $this->response    = $response;
        $this->l11n        = $response !== null ? $response->getHeader()->getL11n() : new Localization();
    }

    /**
     * Check if data exists
     *
     * @param string $id Data Id
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function hasData(string $id) : bool
    {
        return isset($this->data[$id]);
    }

    /**
     * Get data attached to view
     *
     * @param string $id Data Id
     *
     * @return mixed
     *
     * @since 1.0.0
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
     * @since 1.0.0
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
     * @since 1.0.0
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
     * @since 1.0.0
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
     * @since 1.0.0
     */
    public function getText($translation, string $module = null, string $theme = null) : string
    {
        if ($module === null && $this->module === null) {
            $this->setModuleDynamically();
        }

        if ($theme === null && $this->theme === null) {
            $this->setThemeDynamically();
        }

        /** @var string $module */
        $module = $module ?? $this->module;
        /** @var string $theme */
        $theme = $theme ?? $this->theme;

        return $this->l11nManager->getText($this->l11n->getLanguage() ?? 'en', $module, $theme, $translation);
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
     * @since 1.0.0
     */
    private function setModuleDynamically() : void
    {
        $match = '/Modules/';

        if (($start = \strripos($this->template, $match)) === false) {
            throw new InvalidModuleException($this->template);
        }

        $start = $start + \strlen($match);
        $end   = \strpos($this->template, '/', $start);

        if ($end === false) {
            throw new InvalidModuleException($this->template);
        }

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
     * @since 1.0.0
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
     * @since 1.0.0
     */
    public function getHtml($translation, string $module = null, string $theme = null) : string
    {
        return \htmlspecialchars($this->getText($translation, $module, $theme));
    }

    /**
     * Print a numeric value
     *
     * @param int|float   $numeric Numeric value to print
     * @param null|string $format  Format type to use
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function getNumeric($numeric, string $format = null) : string
    {
        return $this->l11nManager->getNumeric($this->l11n, $numeric, $format);
    }

    /**
     * Print a percentage value
     *
     * @param float       $percentage Percentage value to print
     * @param null|string $format     Format type to use
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function getPercentage(float $percentage, string $format = null) : string
    {
        return $this->l11nManager->getPercentage($this->l11n, $percentage, $format);
    }

    /**
     * Print a currency
     *
     * @param int|float   $currency Currency value to print
     * @param null|string $format   Format type to use
     * @param null|string $symbol   Currency name/symbol
     * @param int         $divide   Divide currency by divisor
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function getCurrency($currency, string $format = null, string $symbol = null, int $divide = 1) : string
    {
        return $this->l11nManager->getCurrency($this->l11n, $currency, $format, $symbol, $divide);
    }

    /**
     * Print a datetime
     *
     * @param null|\DateTimeInterface $datetime DateTime to print
     * @param string                  $format   Format type to use
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function getDateTime(\DateTimeInterface $datetime = null, string $format = null) : string
    {
        return $this->l11nManager->getDateTime($this->l11n, $datetime, $format);
    }

    /**
     * Get request of view
     *
     * @return null|RequestAbstract
     *
     * @since 1.0.0
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
     * @since 1.0.0
     */
    public function getResponse() : ?ResponseAbstract
    {
        return $this->response;
    }
}
