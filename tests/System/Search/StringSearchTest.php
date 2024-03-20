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

namespace phpOMS\tests\System\Search;

use phpOMS\System\Search\StringSearch;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\System\Search\StringSearch::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\System\Search\StringSearchTest: Search utilities')]
final class StringSearchTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testKnutMorrisPrattSearch() : void
    {
        self::assertEquals(15, StringSearch::knuthMorrisPrattSearch('ABCDABD', 'ABC ABCDAB ABCDABCDABDE'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testInvalidKnutMorrisPrattSearch() : void
    {
        self::assertEquals(-1, StringSearch::knuthMorrisPrattSearch('ABCDABDZ', 'ABC ABCDAB ABCDABCDABDE'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testBoyerMooreHorspoolSimpleSearch() : void
    {
        self::assertEquals(15, StringSearch::boyerMooreHorspoolSimpleSearch('ABCDABD', 'ABC ABCDAB ABCDABCDABDE'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testInvalidBoyerMooreHorspoolSimpleSearch() : void
    {
        self::assertEquals(-1, StringSearch::boyerMooreHorspoolSimpleSearch('ABCDABDZ', 'ABC ABCDAB ABCDABCDABDE'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testBoyerMooreHorspoolSearch() : void
    {
        self::assertEquals(15, StringSearch::boyerMooreHorspoolSearch('ABCDABD', 'ABC ABCDAB ABCDABCDABDE'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testInvalidBoyerMooreHorspoolSearch() : void
    {
        self::assertEquals(-1, StringSearch::boyerMooreHorspoolSearch('ABCDABDZ', 'ABC ABCDAB ABCDABCDABDE'));
    }
}
