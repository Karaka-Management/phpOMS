<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Model\Html
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Model\Html;

use phpOMS\Asset\AssetType;
use phpOMS\Contract\RenderableInterface;
use phpOMS\Localization\ISO639x1Enum;

/**
 * Head class.
 *
 * Responsible for handling everything that's going on in the <head>
 *
 * @package phpOMS\Model\Html
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class Head implements RenderableInterface
{
    /**
     * Page language.
     *
     * @var string
     * @since 1.0.0
     */
    private string $language = ISO639x1Enum::_EN;

    /**
     * Page title.
     *
     * @var string
     * @since 1.0.0
     */
    public string $title = '';

    /**
     * Assets bound to this page instance.
     *
     * @var array|array<string, array{type:int, attributes:array}>
     * @since 1.0.0
     */
    private array $assets = [];

    /**
     * Page meta.
     *
     * @var Meta
     * @since 1.0.0
     */
    public Meta $meta;

    /**
     * html style.
     *
     * Inline style
     *
     * @var mixed[]
     * @since 1.0.0
     */
    private array $style = [];

    /**
     * html script.
     *
     * @var mixed[]
     * @since 1.0.0
     */
    private array $script = [];

    /**
     * Tags bound to this page instance.
     *
     * @var array
     * @since 1.0.0
     */
    private array $tags = [];

    /**
     * Constructor.
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        $this->meta = new Meta();
    }

    /**
     * Add asset.
     *
     * @param int    $type       Asset type
     * @param string $uri        Asset uri
     * @param array  $attributes Asset attributes
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function addAsset(int $type, string $uri, array $attributes = []) : void
    {
        $this->assets[$uri] = ['type' => $type, 'attributes' => $attributes];
    }

    /**
     * Add tag.
     *
     * @param string $tag        Html tag type
     * @param string $content    Tag content
     * @param array  $attributes Tag attributes
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function addTag(string $tag, string $content, array $attributes = []) : void
    {
        $this->tags[] = ['tag' => $tag, 'content' => $content, 'attributes' => $attributes];
    }

    /**
     * Set page language.
     *
     * @param string $language language string
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setLanguage(string $language) : void
    {
        $this->language = $language;
    }

    /**
     * Get page language.
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function getLanguage() : string
    {
        return $this->language;
    }

    /**
     * Get the evaluated contents of the object.
     *
     * @param mixed ...$data Data to pass to renderer
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function render(mixed ...$data) : string
    {
        $head  = '';
        $head .= $this->meta->render();
        $head .= $this->renderAssets();
        $head .= empty($this->style) ? '' : '<style>' . $this->renderStyle() . '</style>';

        return $head . (empty($this->script) ? '' : '<script>' . $this->renderScript() . '</script>');
    }

    /**
     * Render style.
     *
     * @return string
     *
     * @since 1.0.0
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
     * @since 1.0.0
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
     * @since 1.0.0
     */
    public function setStyle(string $key, string $style, bool $overwrite = true) : void
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
     * @since 1.0.0
     */
    public function setScript(string $key, string $script, bool $overwrite = true) : void
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
     * @since 1.0.0
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
     * @since 1.0.0
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
     * @since 1.0.0
     */
    public function renderAssets() : string
    {
        $rendered = '';
        foreach ($this->assets as $uri => $asset) {
            if ($asset['type'] === AssetType::CSS) {
                $rendered .= '<link rel="stylesheet" type="text/css" href="' . $uri . '">';
            } elseif ($asset['type'] === AssetType::JS) {
                $rendered .= '<script src="' . $uri . '"';

                foreach ($asset['attributes'] as $key => $attribute) {
                    $rendered .= ' ' . $key . '="' . $attribute . '"';
                }

                $rendered .= '></script>';
            }
        }

        return $rendered;
    }

    /**
     * Render assets.
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function renderAssetsLate() : string
    {
        $rendered = '';
        foreach ($this->assets as $uri => $asset) {
            if ($asset['type'] === AssetType::JSLATE) {
                $rendered .= '<script src="' . $uri . '"';

                foreach ($asset['attributes'] as $key => $attribute) {
                    $rendered .= ' ' . $key . '="' . $attribute . '"';
                }

                $rendered .= '></script>';
            }
        }

        return $rendered;
    }

    /**
     * Render tags.
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function renderTags() : string
    {
        $rendered = '';
        foreach ($this->tags as $tag) {
            $rendered .= '<' . $tag['tag'];

            foreach ($tag['attributes'] as $key => $attribute) {
                $rendered .= ' ' . $key . '="' . $attribute . '"';
            }

            $rendered .= '>' . $tag['content'] . '</' . $tag['tag'] . '>';
        }

        return $rendered;
    }
}
