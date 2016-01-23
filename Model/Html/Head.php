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
namespace phpOMS\Model\Html;

use phpOMS\Asset\AssetType;
use phpOMS\Contract\RenderableInterface;


/**
 * Head class.
 *
 * Responsible for handling everything that's going on in the <head>
 *
 * @category   Log
 * @package    Framework
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
class Head implements RenderableInterface
{

    /**
     * Page language.
     *
     * @var string
     * @since 1.0.0
     */
    private $language = '';

    /**
     * Page title.
     *
     * @var string
     * @since 1.0.0
     */
    private $title = '';

    /**
     * Assets bound to this page instance.
     *
     * @var array
     * @since 1.0.0
     */
    private $assets = [];

    /**
     * Is the header set?
     *
     * @var bool
     * @since 1.0.0
     */
    private $hasContent = false;

    /**
     * Page meta.
     *
     * @var Meta
     * @since 1.0.0
     */
    private $meta = null;

    /**
     * html style.
     *
     * Inline style
     *
     * @var mixed[]
     * @since 1.0.0
     */
    private $style = [];

    /**
     * html script.
     *
     * @var mixed[]
     * @since 1.0.0
     */
    private $script = [];

    /**
     * Constructor.
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function __construct()
    {
        $this->meta = new Meta();
    }

    /**
     * Set page meta.
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getMeta() : Meta
    {
        return $this->meta;
    }

    /**
     * Set page title.
     *
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getTitle() : string
    {
        return $this->title;
    }

    /**
     * Set page title.
     *
     * @param string $title Page title
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function setTitle(string $title)
    {
        $this->title = $title;
    }

    /**
     * Set page title.
     *
     * @param string $type Asset type
     * @param string $uri  Asset uri
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function addAsset(string $type, string $uri)
    {
        $this->assets[$uri] = $type;
    }

    /**
     * Set page language.
     *
     * @param string $language language string
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function setLanguage(string $language)
    {
        $this->language = $language;
    }

    /**
     * Get the evaluated contents of the object.
     *
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function render() : string
    {
        $head = '';
        if ($this->hasContent) {
            $head .= $this->meta->render();
            $head .= $this->renderStyle();
            $head .= $this->renderScript();
        }

        return $head;
    }

    /**
     * Render style.
     *
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function renderStyle() : string
    {
        $style = '';
        foreach ($this->style as $css) {
            $style .= $css;
        }

        return $style;
    }

    /**
     * Render script.
     *
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function renderScript() : string
    {
        $script = '';
        foreach ($this->script as $js) {
            $script .= $js;
        }

        return $script;
    }

    /**
     * Set a style.
     *
     * @param string $key       Style key
     * @param string $style     Style source
     * @param bool   $overwrite Overwrite if already existing
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function setStyle(string $key, string $style, bool $overwrite = true)
    {
        if ($overwrite || !isset($this->script[$key])) {
            $this->style[$key] = $style;
        }
    }

    /**
     * Set a script.
     *
     * @param string $key       Script key
     * @param string $script    Script source
     * @param bool   $overwrite Overwrite if already existing
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function setScript(string $key, string $script, bool $overwrite = true)
    {
        if ($overwrite || !isset($this->script[$key])) {
            $this->script[$key] = $script;
        }
    }

    /**
     * Get all styles.
     *
     * @return array
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getStyleAll() : array
    {
        return $this->style;
    }

    /**
     * Get all scripts.
     *
     * @return array
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getScriptAll() : array
    {
        return $this->script;
    }

    /**
     * Render assets.
     *
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function renderAssets() : string
    {
        $asset = '';
        foreach ($this->assets as $uri => $type) {
            if ($type == AssetType::CSS) {
                $asset .= '<link rel="stylesheet" type="text/css" href="' . $uri . '">';
            } elseif ($type === AssetType::JS) {
                $asset .= '<script src="' . $uri . '"></script>';
            }
        }

        return $asset;
    }

    /**
     * Render assets.
     *
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function renderAssetsLate() : string
    {
        $asset = '';
        foreach ($this->assets as $uri => $type) {
            if ($type === AssetType::JSLATE) {
                $asset .= '<script src="' . $uri . '"></script>';
            }
        }

        return $asset;
    }
}
