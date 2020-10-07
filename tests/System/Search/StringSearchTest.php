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

namespace phpOMS\tests\System\Search;

use phpOMS\System\Search\StringSearch;

/**
 * @testdox phpOMS\tests\System\Search\StringSearchTest: Search utilities
 *
 * @internal
 */
class StringSearchTest extends \PHPUnit\Framework\TestCase
{
    public function testKnutMorrisPrattSearch() : void
    {
        self::assertEquals(15, StringSearch::knuthMorrisPrattSearch('ABCDABD', 'ABC ABCDAB ABCDABCDABDE'));
    }

    public function testInvalidKnutMorrisPrattSearch() : void
    {
        self::assertEquals(-1, StringSearch::knuthMorrisPrattSearch('ABCDABDZ', 'ABC ABCDAB ABCDABCDABDE'));
    }

    public function testBoyerMooreHorspoolSimpleSearch() : void
    {
        self::assertEquals(15, StringSearch::boyerMooreHorspoolSimpleSearch('ABCDABD', 'ABC ABCDAB ABCDABCDABDE'));
    }

    public function testInvalidBoyerMooreHorspoolSimpleSearch() : void
    {
        self::assertEquals(-1, StringSearch::boyerMooreHorspoolSimpleSearch('ABCDABDZ', 'ABC ABCDAB ABCDABCDABDE'));
    }

    public function testBoyerMooreHorspoolSearch() : void
    {
        self::assertEquals(15, StringSearch::boyerMooreHorspoolSearch('ABCDABD', 'ABC ABCDAB ABCDABCDABDE'));
    }

    public function testInvalidBoyerMooreHorspoolSearch() : void
    {
        self::assertEquals(-1, StringSearch::boyerMooreHorspoolSearch('ABCDABDZ', 'ABC ABCDAB ABCDABCDABDE'));
    }
}
