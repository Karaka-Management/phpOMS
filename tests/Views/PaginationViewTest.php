<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\tests\Views;

require_once __DIR__ . '/../Autoloader.php';

use phpOMS\Views\PaginationView;

/**
 * @testdox phpOMS\tests\Views\PaginationViewTest: View for pagination
 *
 * @internal
 */
class PaginationViewTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox The pagination view has the expected default values after initialization
     * @covers phpOMS\Views\PaginationView
     */
    public function testDefault() : void
    {
        $view = new PaginationView();
        self::assertEquals(7, $view->getMaxPages());
        self::assertEquals(1, $view->getPages());
        self::assertEquals(1, $view->getPage());
        self::assertEquals(0, $view->getResults());
    }

    /**
     * @testdox The max pages can be set and returned
     * @covers phpOMS\Views\PaginationView
     */
    public function testMaxPagesInputOutput() : void
    {
        $view = new PaginationView();

        $view->setMaxPages(9);
        self::assertEquals(9, $view->getMaxPages());
    }

    /**
     * @testdox The pages can be set and returned
     * @covers phpOMS\Views\PaginationView
     */
    public function testPagesInputOutput() : void
    {
        $view = new PaginationView();

        $view->setPages(2);
        self::assertEquals(2, $view->getPages());
    }

    /**
     * @testdox The page can be set and returned
     * @covers phpOMS\Views\PaginationView
     */
    public function testPageInputOutput() : void
    {
        $view = new PaginationView();

        $view->setPage(3);
        self::assertEquals(3, $view->getPage());
    }

    /**
     * @testdox The results can be set and returned
     * @covers phpOMS\Views\PaginationView
     */
    public function testResultsInputOutput() : void
    {
        $view = new PaginationView();

        $view->setResults(12);
        self::assertEquals(12, $view->getResults());
    }
}
