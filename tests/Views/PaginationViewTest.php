<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    tests
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */
 declare(strict_types=1);

namespace phpOMS\tests\Views;

require_once __DIR__ . '/../Autoloader.php';

use phpOMS\Views\PaginationView;

/**
 * @internal
 */
class PaginationViewTest extends \PHPUnit\Framework\TestCase
{
    public function testDefault() : void
    {
        $view = new PaginationView();
        self::assertEquals(7, $view->getMaxPages());
        self::assertEquals(1, $view->getPages());
        self::assertEquals(1, $view->getPage());
        self::assertEquals(0, $view->getResults());
    }

    public function testGetSet() : void
    {
        $view = new PaginationView();

        $view->setMaxPages(9);
        self::assertEquals(9, $view->getMaxPages());

        $view->setPages(2);
        self::assertEquals(2, $view->getPages());

        $view->setPage(3);
        self::assertEquals(3, $view->getPage());

        $view->setResults(12);
        self::assertEquals(12, $view->getResults());
    }
}
