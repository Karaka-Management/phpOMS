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

use phpOMS\Contract\RenderableInterface;
use phpOMS\System\File\PathException;

/**
 * View Abstract.
 *
 * @package phpOMS\Views
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
abstract class ViewAbstract implements RenderableInterface
{
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
     * @param string $template View template
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setTemplate(string $template) : void
    {
        $this->template = $template;
    }

    /**
     * Print html output.
     *
     * @param mixed $text Text
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function printHtml($text) : string
    {
        return \htmlspecialchars((string) $text);
    }

    /**
     * Print html output.
     *
     * @param mixed $text Text
     *
     * @return string
     *
     * @since 1.0.0
     */
    public static function html($text) : string
    {
        return \htmlspecialchars((string) $text);
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
     * @return false|View
     *
     * @since 1.0.0
     */
    public function getView(string $id)
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
     * @param array ...$data Data to pass to renderer
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function render(...$data) : string
    {
        $ob = '';

        try {
            \ob_start();

            $path = __DIR__ . '/../..' . $this->template . '.tpl.php';

            if (!\file_exists($path)) {
                throw new PathException($path);
            }

            /** @noinspection PhpIncludeInspection */
            $includeData = include $path;
            $ob          = (string) \ob_get_clean();

            if (\is_array($includeData)) {
                $ob = (string) \json_encode($includeData);
            }
        } catch (\Throwable $e) {
            \ob_end_clean();
            $ob = '';
        } finally {
            return $ob;
        }
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
    public function unserialize($raw) : void
    {
    }
}
