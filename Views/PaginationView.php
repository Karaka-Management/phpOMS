<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   phpOMS\Views
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Views;

/**
 * Pagination view.
 *
 * @package phpOMS\Views
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
class PaginationView extends View
{
    /**
     * Maximum amount of pages.
     *
     * @var int
     * @since 1.0.0
     */
    protected int $maxPages = 7;

    /**
     * Current page id.
     *
     * @var int
     * @since 1.0.0
     */
    protected int $page = 1;

    /**
     * How many pages exist?
     *
     * @var int
     * @since 1.0.0
     */
    protected int $pages = 1;

    /**
     * How many results exist?
     *
     * @var int
     * @since 1.0.0
     */
    protected int $results = 0;

    /**
     * @return int
     *
     * @since 1.0.0
     */
    public function getMaxPages() : int
    {
        return $this->maxPages;
    }

    /**
     * @param int $maxPages Maximum amount of pages to be shown
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setMaxPages(int $maxPages) : void
    {
        $this->maxPages = $maxPages;
    }

    /**
     * @return int
     *
     * @since 1.0.0
     */
    public function getPages() : int
    {
        return $this->pages;
    }

    /**
     * @param int $pages Number of pages
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setPages(int $pages) : void
    {
        $this->pages = $pages;
    }

    /**
     * @return int
     *
     * @since 1.0.0
     */
    public function getPage() : int
    {
        return $this->page;
    }

    /**
     * @param int $page Current page index
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setPage(int $page = 1) : void
    {
        $this->page = $page;
    }

    /**
     * @return int
     *
     * @since 1.0.0
     */
    public function getResults() : int
    {
        return $this->results;
    }

    /**
     * @param int $results Amount of results
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setResults(int $results = 0) : void
    {
        $this->results = $results;
    }
}
