<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Views
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://karaka.app
 */
declare(strict_types=1);

namespace phpOMS\Views;

use phpOMS\Contract\RenderableInterface;

/**
 * View Abstract.
 *
 * @package phpOMS\Views
 * @license OMS License 1.0
 * @link    https://karaka.app
 * @since   1.0.0
 */
abstract class ViewAbstract implements RenderableInterface
{
    /**
     * Base path.
     *
     * @var string
     * @since 1.0.0
     */
    protected const BASE_PATH = __DIR__ . '/../..';

    /**
     * Output is buffered
     *
     * @var bool
     * @since 1.0.0
     */
    public bool $isBuffered = true;

    /**
     * Template.
     *
     * @var string
     * @since 1.0.0
     */
    protected string $template = '';

    /**
     * Views.
     *
     * @var \phpOMS\Views\View[]
     * @since 1.0.0
     */
    protected array $views = [];

    /**
     * Get the template.
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function getTemplate() : string
    {
        return $this->template;
    }

    /**
     * Set the template.
     *
     * @param string $template  View template
     * @param string $extension Extension of the template
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setTemplate(string $template, string $extension = 'tpl.php') : void
    {
        $this->template = self::BASE_PATH . $template . '.' . $extension;
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
    public function printHtml(?string $text) : string
    {
        return $text === null ? '' : \htmlspecialchars($text);
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
    public static function html(?string $text) : string
    {
        return $text === null ? '' : \htmlspecialchars($text);
    }

    /**
     * Print cli output.
     *
     * @param ?string $text Text
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function printCli(?string $text) : string
    {
        return $text === null ? '' : \escapeshellcmd($text);
    }

    /**
     * Print cli output.
     *
     * @param ?string $text Text
     *
     * @return string
     *
     * @since 1.0.0
     */
    public static function cli(?string $text) : string
    {
        return $text === null ? '' : \escapeshellcmd($text);
    }

    /**
     * Returns all views
     *
     * @return View[]
     *
     * @since 1.0.0
     */
    public function getViews() : array
    {
        return $this->views;
    }

    /**
     * Returns a specific view
     *
     * @param string $id View ID
     *
     * @return false|self
     *
     * @since 1.0.0
     */
    public function getView(string $id) : bool | self
    {
        if (!isset($this->views[$id])) {
            return false;
        }

        return $this->views[$id];
    }

    /**
     * Remove view bz id
     *
     * @param string $id View ID
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function removeView(string $id) : bool
    {
        if (isset($this->views[$id])) {
            unset($this->views[$id]);

            return true;
        }

        return false;
    }

    /**
     * Add view.
     *
     * @param string $id        View ID
     * @param View   $view      View to add
     * @param bool   $overwrite Overwrite existing view
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function addView(string $id, View $view, bool $overwrite = false) : bool
    {
        if ($overwrite || !isset($this->views[$id])) {
            $this->views[$id] = $view;

            return true;
        }

        return false;
    }

    /**
     * Serialize view for rendering.
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function serialize() : string
    {
        if (empty($this->template)) {
            return (string) \json_encode($this->toArray());
        }

        return $this->render();
    }

    /**
     * Arrayify view and it's subviews.
     *
     * @return array
     *
     * @since 1.0.0
     */
    public function toArray() : array
    {
        $viewArray = [];

        if ($this->template !== '') {
            $viewArray[] = $this->render();
        }

        foreach ($this->views as $key => $view) {
            $viewArray[$key] = $view->toArray();
        }

        return $viewArray;
    }

    /**
     * Get view/template response.
     *
     * @param mixed ...$data Data to pass to renderer
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function render(...$data) : string
    {
        return $this->renderTemplate($this->template, ...$data);
    }

    protected function renderTemplate(string $template, ...$data) : string
    {
        $ob = '';

        try {
            if ($this->isBuffered) {
                \ob_start();
            }

            $path = $template;
            if (!\is_file($path)) {
                return '';
            }

            /** @noinspection PhpIncludeInspection */
            $includeData = include $path;

            if ($this->isBuffered) {
                $ob = (string) \ob_get_clean();
            }

            if (\is_array($includeData)) {
                $ob = (string) \json_encode($includeData);
            }
        } catch (\Throwable $e) {
            if ($this->isBuffered) {
                \ob_end_clean();
            }

            $ob = '';
        }

        return $ob;
    }

    /**
     * Very similar to render, except that it executes the template file and returns its response as is.
     * This allows to build the template as any datatype (e.g. pdf).
     *
     * @param mixed ...$data Data to pass to build
     *
     * @return mixed
     *
     * @since 1.0.0
     */
    public function build(...$data) : mixed
    {
        $ob = '';

        try {
            $path = $this->template;
            if (!\is_file($path)) {
                return '';
            }

            /** @noinspection PhpIncludeInspection */
            $ob = include $path;
        } catch (\Throwable $e) {
            $ob = '';
        }

        return $ob;
    }

    /**
     * Unserialize view.
     *
     * @param string $raw Raw data to parse
     *
     * @return void
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    public function unserialize(mixed $raw) : void
    {
    }
}
