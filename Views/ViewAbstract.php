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

use phpOMS\System\File\PathException;

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
     * Constructor.
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function __construct()
    {
    }

    /**
     * Get the template.
     *
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getTemplate() : string
    {
        return $this->template;
    }

    /**
     * Set the template.
     *
     * @param string $template
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function setTemplate(string $template)
    {
        $this->template = $template;
    }

    /**
     * Sort views by order.
     *
     * @param array $a Array 1
     * @param array $b Array 2
     *
     * @return int
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    private static function viewSort(array $a, array $b) : int
    {
        if ($a['order'] === $b['order']) {
            return 0;
        }

        return ($a['order'] < $b['order']) ? -1 : 1;
    }

    /**
     * @return View[]
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getViews() : array
    {
        return $this->views;
    }

    /**
     * @param string $id View ID
     *
     * @return false|View
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getView($id)
    {
        if (!isset($this->views[$id])) {
            return false;
        }

        return $this->views[$id];
    }

    /**
     * Remove view.
     *
     * @param string $id View ID
     *
     * @return bool
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
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
     * Edit view.
     *
     * @param string   $id    View ID
     * @param View     $view
     * @param null|int $order Order of view
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function editView(string $id, View $view, $order = null)
    {
        $this->addView($id, $view, $order, true);
    }

    /**
     * Add view.
     *
     * @param string $id        View ID
     * @param View   $view
     * @param int    $order     Order of view
     * @param bool   $overwrite Overwrite existing view
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function addView(string $id, View $view, int $order = 0, bool $overwrite = true)
    {
        if ($overwrite || !isset($this->views[$id])) {
            $this->views[$id] = $view;

            if ($order !== 0) {
                uasort($this->views, ['\phpOMS\Views\View', 'viewSort']);
            }
        }
    }

    /**
     * Arrayify view and it's subviews.
     *
     * @return array
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function toArray() : array
    {
        $viewArray = [];

        if($this->template !== '') {
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
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function render() : string
    {
        $path = realpath($oldPath = __DIR__ . '/../..' . $this->template . '.tpl.php');

        if ($path === false) {
            throw new PathException($oldPath);
        }

        ob_start();
        /** @noinspection PhpIncludeInspection */
        $data = include $path;
        $ob   = ob_get_clean();

        if (is_array($data)) {
            return $data;
        }

        return $ob;
    }

    /**
     * Serialize view for rendering.
     *
     * @return string|array
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function serialize()
    {
        $path = realpath($oldPath = __DIR__ . '/../..' . $this->template . '.tpl.php');

        if ($path === false) {
            return $this->toArray();
        }

        return $this->render();
    }

    /**
     * Unserialize view.
     *
     * @param string $raw Raw data to parse
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function unserialize($raw)
    {
        // todo: implement
    }

}
