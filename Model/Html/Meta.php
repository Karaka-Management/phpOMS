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

use phpOMS\Contract\RenderableInterface;

/**
 * Meta class.
 *
 * @category   Log
 * @package    Framework
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
class Meta implements RenderableInterface
{

    /**
     * Keywords.
     *
     * @var \string[]
     * @since 1.0.0
     */
    private $keywords = [];

    /**
     * Author.
     *
     * @var \string
     * @since 1.0.0
     */
    private $author = null;

    /**
     * Charset.
     *
     * @var \string
     * @since 1.0.0
     */
    private $charset = null;

    /**
     * Description.
     *
     * @var \string
     * @since 1.0.0
     */
    private $description = null;

    /**
     * Language.
     *
     * @var \string
     * @since 1.0.0
     */
    private $language = 'en';

    /**
     * Add keyword.
     *
     * @param \string $keyword Keyword
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function addKeyword(\string $keyword)
    {
        if (!in_array($keyword, $this->keywords)) {
            $this->keywords[] = $keyword;
        }
    }

    /**
     * Get keywords.
     *
     * @return \string[] Keywords
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getKeywords() : array
    {
        return $this->keywords;
    }

    /**
     * Get author.
     *
     * @return \string Author
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getAuthor() : \string
    {
        return $this->author;
    }

    /**
     * Set author.
     *
     * @param \string $author Author
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function setAuthor(\string $author)
    {
        $this->author = $author;
    }

    /**
     * Get charset.
     *
     * @return \string Charset
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getCharset() : \string
    {
        return $this->charset;
    }

    /**
     * Set charset.
     *
     * @param \string $charset Charset
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function setCharset(\string $charset)
    {
        $this->charset = $charset;
    }

    /**
     * Get description.
     *
     * @return \string Descritpion
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getDescription() : \string
    {
        return $this->description;
    }

    /**
     * Set description.
     *
     * @param \string Descritpion
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function setDescription(\string $description)
    {
        $this->description = $description;
    }

    /**
     * Get language.
     *
     * @return \string Language
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getLanguage() : \string
    {
        return $this->language;
    }

    /**
     * Set language.
     *
     * @param \string $language Language
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function setLanguage(\string $language)
    {
        $this->language = $language;
    }

    /**
     * {@inheritdoc}
     */
    public function render() : \string
    {
        return (count($this->keywords) > 0 ? '<meta name="keywords" content="' . implode(',', $this->keywords) . '">"' : '')
               . (isset($this->author) ? '<meta name="author" content="' . $this->author . '">' : '')
               . (isset($this->description) ? '<meta name="description" content="' . $this->description . '">' : '')
               . (isset($this->charset) ? '<meta charset="' . $this->charset . '">' : '')
               . '<meta name="generator" content="Orange Management">';
    }
}
