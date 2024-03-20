<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\tests\Views;

require_once __DIR__ . '/../Autoloader.php';

use phpOMS\Views\PaginationView;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Views\PaginationView::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Views\PaginationViewTest: View for pagination')]
final class PaginationViewTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The pagination view has the expected default values after initialization')]
    public function testDefault() : void
    {
        $view = new PaginationView();
        self::assertEquals(7, $view->getMaxPages());
        self::assertEquals(1, $view->getPages());
        self::assertEquals(1, $view->getPage());
        self::assertEquals(0, $view->getResults());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The max pages can be set and returned')]
    public function testMaxPagesInputOutput() : void
    {
        $view = new PaginationView();

        $view->setMaxPages(9);
        self::assertEquals(9, $view->getMaxPages());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The pages can be set and returned')]
    public function testPagesInputOutput() : void
    {
        $view = new PaginationView();

        $view->setPages(2);
        self::assertEquals(2, $view->getPages());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The page can be set and returned')]
    public function testPageInputOutput() : void
    {
        $view = new PaginationView();

        $view->setPage(3);
        self::assertEquals(3, $view->getPage());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The results can be set and returned')]
    public function testResultsInputOutput() : void
    {
        $view = new PaginationView();

        $view->setResults(12);
        self::assertEquals(12, $view->getResults());
    }
}
