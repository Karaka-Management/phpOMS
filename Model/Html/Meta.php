<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    phpOMS\Model\Html
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Model\Html;

use phpOMS\Contract\RenderableInterface;
use phpOMS\Views\ViewAbstract;

/**
 * Meta class.
 *
 * @package    phpOMS\Model\Html
 * @license    OMS License 1.0
 * @link       https://orange-management.org
 * @since      1.0.0
 */
class Meta implements RenderableInterface
{

    /**
     * Keywords.
     *
     * @var string[]
     * @since 1.0.0
     */
    private $keywords = [];

    /**
     * Author.
     *
     * @var string
     * @since 1.0.0
     */
    private $author = '';

    /**
     * Charset.
     *
     * @var string
     * @since 1.0.0
     */
    private $charset = '';

    /**
     * Description.
     *
     * @var string
     * @since 1.0.0
     */
    private $description = '';

    /**
     * Itemprop.
     *
     * @var array
     * @since 1.0.0
     */
    private $itemprops = [];

    /**
     * Property.
     *
     * @var array
     * @since 1.0.0
     */
    private $properties = [];

    /**
     * Name.
     *
     * @var array
     * @since 1.0.0
     */
    private $names = [];

    /**
     * Add keyword.
     *
     * @param string $keyword Keyword
     *
     * @return void
     *
     * @since  1.0.0
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
     * @since  1.0.0
     */
    public function getKeywords() : array
    {
        return $this->keywords;
    }

    /**
     * Get author.
     *
     * @return string Author
     *
     * @since  1.0.0
     */
    public function getAuthor() : string
    {
        return $this->author;
    }

    /**
     * Set author.
     *
     * @param string $author Author
     *
     * @return void
     *
     * @since  1.0.0
     */
    public function setAuthor(string $author) : void
    {
        $this->author = $author;
    }

    /**
     * Get charset.
     *
     * @return string Charset
     *
     * @since  1.0.0
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
     * @since  1.0.0
     */
    public function setCharset(string $charset) : void
    {
        $this->charset = $charset;
    }

    /**
     * Get description.
     *
     * @return string Descritpion
     *
     * @since  1.0.0
     */
    public function getDescription() : string
    {
        return $this->description;
    }

    /**
     * Set description.
     *
     * @param string $description Meta description
     *
     * @return void
     *
     * @since  1.0.0
     */
    public function setDescription(string $description) : void
    {
        $this->description = $description;
    }

    /**
     * Set property.
     *
     * @param string $property Property
     * @param string $content  Content
     *
     * @return void
     *
     * @since  1.0.0
     */
    public function setProperty(string $property, string $content) : void
    {
        $this->properties[$property] = $content;
    }

    /**
     * Set itemprop.
     *
     * @param string $itemprop Property
     * @param string $content  Content
     *
     * @return void
     *
     * @since  1.0.0
     */
    public function setItemprop(string $itemprop, string $content) : void
    {
        $this->itemprops[$itemprop] = $content;
    }

    /**
     * Set name.
     *
     * @param string $name    Property
     * @param string $content Content
     *
     * @return void
     *
     * @since  1.0.0
     */
    public function setName(string $name, string $content) : void
    {
        $this->names[$name] = $content;
    }

    /**
     * {@inheritdoc}
     */
    public function render() : string
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
     * @since  1.0.0
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
     * @since  1.0.0
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
     * @since  1.0.0
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
