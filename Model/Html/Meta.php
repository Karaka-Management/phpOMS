<?php
/**
 * Orange Management
 *
 * PHP Version 8.0
 *
 * @package   phpOMS\Model\Html
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Model\Html;

use phpOMS\Contract\RenderableInterface;
use phpOMS\Views\ViewAbstract;

/**
 * Meta class.
 *
 * @package phpOMS\Model\Html
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
final class Meta implements RenderableInterface
{
    /**
     * Keywords.
     *
     * @var string[]
     * @since 1.0.0
     */
    private array $keywords = [];

    /**
     * Author.
     *
     * @var string
     * @since 1.0.0
     */
    public string $author = '';

    /**
     * Charset.
     *
     * @var string
     * @since 1.0.0
     */
    private string $charset = '';

    /**
     * Description.
     *
     * @var string
     * @since 1.0.0
     */
    public string $description = '';

    /**
     * Itemprop.
     *
     * @var array<string, string>
     * @since 1.0.0
     */
    private array $itemprops = [];

    /**
     * Property.
     *
     * @var array<string, string>
     * @since 1.0.0
     */
    private array $properties = [];

    /**
     * Name.
     *
     * @var array<string, string>
     * @since 1.0.0
     */
    private array $names = [];

    /**
     * Add keyword.
     *
     * @param string $keyword Keyword
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function addKeyword(string $keyword) : void
    {
        if (!\in_array($keyword, $this->keywords)) {
            $this->keywords[] = $keyword;
        }
    }

    /**
     * Get keywords.
     *
     * @return string[] Keywords
     *
     * @since 1.0.0
     */
    public function getKeywords() : array
    {
        return $this->keywords;
    }

    /**
     * Get charset.
     *
     * @return string Charset
     *
     * @since 1.0.0
     */
    public function getCharset() : string
    {
        return $this->charset;
    }

    /**
     * Set charset.
     *
     * @param string $charset Charset
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setCharset(string $charset) : void
    {
        $this->charset = $charset;
    }

    /**
     * Set property.
     *
     * @param string $property Property
     * @param string $content  Content
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setProperty(string $property, string $content) : void
    {
        $this->properties[$property] = $content;
    }

    /**
     * Get property.
     *
     * @param string $property Property
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function getProperty(string $property) : string
    {
        return $this->properties[$property] ?? '';
    }

    /**
     * Set itemprop.
     *
     * @param string $itemprop Property
     * @param string $content  Content
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setItemprop(string $itemprop, string $content) : void
    {
        $this->itemprops[$itemprop] = $content;
    }

    /**
     * Get itemprop.
     *
     * @param string $itemprop Itemprop
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function getItemprop(string $itemprop) : string
    {
        return $this->itemprops[$itemprop] ?? '';
    }

    /**
     * Set name.
     *
     * @param string $name    Name
     * @param string $content Content
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setName(string $name, string $content) : void
    {
        $this->names[$name] = $content;
    }

    /**
     * Get name.
     *
     * @param string $name Name
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function getName(string $name) : string
    {
        return $this->names[$name] ?? '';
    }

    /**
     * {@inheritdoc}
     */
    public function render(...$data) : string
    {
        return (\count($this->keywords) > 0 ? '<meta name="keywords" content="' . ViewAbstract::html(\implode(',', $this->keywords)) . '">' : '')
        . (!empty($this->author) ? '<meta name="author" content="' . ViewAbstract::html($this->author) . '">' : '')
        . (!empty($this->description) ? '<meta name="description" content="' . ViewAbstract::html($this->description) . '">' : '')
        . (!empty($this->charset) ? '<meta charset="' . ViewAbstract::html($this->charset) . '">' : '')
        . '<meta name="generator" content="Orange Management">'
        . $this->renderProperty()
        . $this->renderItemprop()
        . $this->renderName();
    }

    /**
     * Render property meta tags.
     *
     * @return string
     *
     * @since 1.0.0
     */
    private function renderProperty() : string
    {
        $properties = '';
        foreach ($this->properties as $key => $content) {
            $properties .= '<meta property="' . $key . '" content="' . $content . '">';
        }

        return $properties;
    }

    /**
     * Render itemprop meta tags.
     *
     * @return string
     *
     * @since 1.0.0
     */
    private function renderItemprop() : string
    {
        $itemprops = '';
        foreach ($this->itemprops as $key => $content) {
            $itemprops .= '<meta itemprop="' . $key . '" content="' . $content . '">';
        }

        return $itemprops;
    }

    /**
     * Render name meta tags.
     *
     * @return string
     *
     * @since 1.0.0
     */
    private function renderName() : string
    {
        $names = '';
        foreach ($this->names as $key => $content) {
            $names .= '<meta name="' . $key . '" content="' . $content . '">';
        }

        return $names;
    }
}
