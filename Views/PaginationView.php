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
namespace phpOMS\Views;

use phpOMS\Views\View;

/**
 * Pagination view.
 *
 * @package    phpOMS\Views
 * @license    OMS License 1.0
 * @link       http://website.orange-management.de
 * @since      1.0.0
 */
class PaginationView extends View
{

    /**
     * Maximum amount of pages.
     *
     * @var int
     * @since 1.0.0
     */
    protected $maxPages = 7;

    /**
     * Current page id.
     *
     * @var int
     * @since 1.0.0
     */
    protected $page = 1;

    /**
     * How many pages exists?
     *
     * @var int
     * @since 1.0.0
     */
    protected $pages = 1;

    /**
     * How many results exists?
     *
     * @var int
     * @since 1.0.0
     */
    protected $results = 0;

    /**
     * @return int
     *
     * @since  1.0.0
     */
    public function getMaxPages() : int
    {
        return $this->maxPages;
    }

    /**
     * @param int $maxPages
     *
     * @return void
     *
     * @since  1.0.0
     */
    public function setMaxPages(int $maxPages)
    {
        $this->maxPages = $maxPages;
    }

    /**
     * @return int
     *
     * @since  1.0.0
     */
    public function getPages() : int
    {
        return $this->pages;
    }

    /**
     * @param int $pages
     *
     * @return void
     *
     * @since  1.0.0
     */
    public function setPages(int $pages)
    {
        $this->pages = $pages;
    }

    /**
     * @return int
     *
     * @since  1.0.0
     */
    public function getPage() : int
    {
        return $this->page;
    }

    /**
     * @param int $page
     *
     * @return void
     *
     * @since  1.0.0
     */
    public function setPage(int $page = 1)
    {
        $this->page = $page;
    }

    /**
     * @return int
     *
     * @since  1.0.0
     */
    public function getResults() : int
    {
        return $this->results;
    }

    /**
     * @param int $results
     *
     * @return void
     *
     * @since  1.0.0
     */
    public function setResults(int $results = 0)
    {
        $this->results = $results;
    }
}
