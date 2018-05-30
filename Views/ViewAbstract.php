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

use phpOMS\System\File\PathException;

/**
 * View Abstract.
 *
 * @package    phpOMS\Views
 * @license    OMS License 1.0
 * @link       http://website.orange-management.de
 * @since      1.0.0
 */
abstract class ViewAbstract implements \Serializable
{

    /**
     * Template.
     *
     * @var string
     * @since 1.0.0
     */
    protected $template = '';

    /**
     * Views.
     *
     * @var \phpOMS\Views\View[]
     * @since 1.0.0
     */
    protected $views = [];

    /**
     * Sort views by order.
     *
     * @param array $a Array 1
     * @param array $b Array 2
     *
     * @return int
     *
     * @since  1.0.0
     */
    private static function viewSort(array $a, array $b) : int
    {
        if ($a['order'] === $b['order']) {
            return 0;
        }

        return ($a['order'] < $b['order']) ? -1 : 1;
    }

    /**
     * Get the template.
     *
     * @return string
     *
     * @since  1.0.0
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
     * @since  1.0.0
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
     * @since  1.0.0
     */
    public function printHtml($text) : string
    {
        return htmlspecialchars((string) $text);
    }

    /**
     * Print html output.
     *
     * @param mixed $text Text
     *
     * @return string
     *
     * @since  1.0.0
     */
    public static function html($text) : string
    {
        return htmlspecialchars((string) $text);
    }

    /**
     * Returns all views
     *
     * @return View[]
     *
     * @since  1.0.0
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
     * @since  1.0.0
     */
    public function getView($id)
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
     * @since  1.0.0
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
     * @param int    $order     Order of view
     * @param bool   $overwrite Overwrite existing view
     *
     * @return bool
     *
     * @since  1.0.0
     */
    public function addView(string $id, View $view, int $order = 0, bool $overwrite = true) : bool
    {
        if ($overwrite || !isset($this->views[$id])) {
            $this->views[$id] = $view;

            if ($order !== 0) {
                uasort($this->views, ['\phpOMS\Views\View', 'viewSort']);
            }

            return true;
        }

        return false;
    }

    /**
     * Serialize view for rendering.
     *
     * @return string
     *
     * @since  1.0.0
     */
    public function serialize() : string
    {
        if (empty($this->template)) {
            return \json_encode($this->toArray());
        }

        return $this->render();
    }

    /**
     * Arrayify view and it's subviews.
     *
     * @return array
     *
     * @since  1.0.0
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
     * @since  1.0.0
     */
    public function render(...$data) : string
    {
        $ob   = '';
        $path = __DIR__ . '/../..' . $this->template . '.tpl.php';

        if (!\file_exists($path)) {
            throw new PathException($path);
        }

        try {
            ob_start();
            /** @noinspection PhpIncludeInspection */
            $includeData = include $path;
            $ob          = ob_get_clean();

            if (is_array($includeData)) {
                return \json_encode($includeData);
            }
        } catch (\Throwable $e) {
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
     * @since  1.0.0
     * @codeCoverageIgnore
     */
    public function unserialize($raw)
    {
    }
}
