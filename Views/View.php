<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   phpOMS\Views
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Views;

use phpOMS\Localization\L11nManager;
use phpOMS\Localization\Localization;
use phpOMS\Localization\Money;
use phpOMS\Message\RequestAbstract;
use phpOMS\Message\ResponseAbstract;
use phpOMS\Module\Exception\InvalidModuleException;
use phpOMS\Module\Exception\InvalidThemeException;
use phpOMS\Stdlib\Base\FloatInt;

/**
 * Basic view which can be used as basis for specific implementations.
 *
 * @package phpOMS\Views
 * @license OMS License 2.0
 * @link    https://jingga.app
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
    public array $data = [];

    /**
     * View Localization.
     *
     * @var Localization
     * @since 1.0.0
     */
    public Localization $l11n;

    /**
     * Application.
     *
     * @var L11nManager
     * @since 1.0.0
     */
    public L11nManager $l11nManager;

    /**
     * Request.
     *
     * @var null|RequestAbstract
     * @since 1.0.0
     */
    public ?RequestAbstract $request;

    /**
     * Request.
     *
     * @var null|ResponseAbstract
     * @since 1.0.0
     */
    public ?ResponseAbstract $response;

    /**
     * Theme name.
     *
     * @var null|string
     * @since 1.0.0
     */
    public ?string $theme = null;

    /**
     * Module name.
     *
     * @var null|string
     * @since 1.0.0
     */
    public ?string $module = null;

    /**
     * Constructor.
     *
     * @param L11nManager      $l11n     Localization manager
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Request
     *
     * @since 1.0.0
     */
    public function __construct(?L11nManager $l11n = null, ?RequestAbstract $request = null, ?ResponseAbstract $response = null)
    {
        $this->l11nManager = $l11n ?? new L11nManager();
        $this->request     = $request;
        $this->response    = $response;
        $this->l11n        = $response !== null ? $response->header->l11n : new Localization();
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
    public function getData(string $id) : mixed
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
    public function setData(string $id, mixed $data) : void
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
    public function addData(string $id, mixed $data) : bool
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
     * @param string $translation Text
     * @param string $module      Module name
     * @param string $theme       Theme name
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function getText(string $translation, ?string $module = null, ?string $theme = null) : string
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

        return $this->l11nManager->getText($this->l11n->language, $module, $theme, $translation);
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
            $this->module = '0';
        }

        $start += \strlen($match);
        if (\strlen($this->template) < $start) {
            throw new InvalidModuleException($this->template);
        }

        $end = \strpos($this->template, '/', $start);
        if ($end === false) {
            throw new InvalidModuleException($this->template);
        }

        $this->module = \substr($this->template, $start, $end - $start);

        if ($this->module === false) {
            $this->module = '0'; // @codeCoverageIgnore
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
            $this->theme = '0';
        }

        $start += \strlen($match);
        if (\strlen($this->template) < $start) {
            throw new InvalidThemeException($this->template);
        }

        $end = \strpos($this->template, '/', $start);
        if ($end === false) {
            throw new InvalidThemeException($this->template);
        }

        $this->theme = \substr($this->template, $start, $end - $start);

        if ($this->theme === false) {
            $this->theme = '0'; // @codeCoverageIgnore
        }
    }

    /**
     * Get translation.
     *
     * @param string      $translation Text
     * @param null|string $module      Module name
     * @param null|string $theme       Theme name
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function getHtml(string $translation, ?string $module = null, ?string $theme = null) : string
    {
        return \htmlspecialchars($this->getText($translation, $module, $theme));
    }

    /**
     * Print html output.
     *
     * @param ?string $text Text
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function printTextarea(?string $text) : string
    {
        return $text === null
            ? ''
            : \trim(\str_replace(["\r\n", "\n"], ['&#10;', '&#10;'], \htmlspecialchars($text)));
    }

    /**
     * Print a numeric value
     *
     * @param int|float|FloatInt $numeric Numeric value to print
     * @param null|string        $format  Format type to use
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function getNumeric(int | float | FloatInt $numeric, ?string $format = null) : string
    {
        return $this->l11nManager->getNumeric($this->l11n, $numeric, $format);
    }

    /**
     * Print a percentage value
     *
     * @param float|FloatInt $percentage Percentage value to print
     * @param null|string    $format     Format type to use
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function getPercentage(float | FloatInt $percentage, ?string $format = null) : string
    {
        return $this->l11nManager->getPercentage($this->l11n, $percentage, $format);
    }

    /**
     * Print a currency
     *
     * @param int|float|Money $currency Currency value to print
     * @param null|string     $symbol   Currency name/symbol
     * @param null|string     $format   Format type to use
     * @param int             $divide   Divide currency by divisor
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function getCurrency(
        int | float | Money | FloatInt $currency,
        ?string $symbol = null,
        ?string $format = null,
        int $divide = 1
    ) : string
    {
        return $this->l11nManager->getCurrency($this->l11n, $currency, $symbol, $format, $divide);
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
    public function getDateTime(?\DateTimeInterface $datetime = null, ?string $format = null) : string
    {
        return $this->l11nManager->getDateTime($this->l11n, $datetime, $format);
    }

    /**
     * Render user name based on format
     *
     * @param string $format Format used in printf
     * @param array  $names  Names to render according to the format
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function renderUserName(string $format, array $names) : string
    {
        $name = \preg_replace('/\s+/', ' ', \sprintf($format, ...$names));

        return $name === null ? '' : \trim($name);
    }
}
