<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\tests\DataStorage\Database\Schema\Grammar;

use phpOMS\DataStorage\Database\Schema\Grammar\Grammar;

/**
 * @testdox phpOMS\tests\DataStorage\Database\Schema\GrammarTest: Basic sql query grammar
 *
 * @internal
 */
final class GrammarTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox The grammar has the expected default values after initialization
     * @group framework
     */
    public function testDefault() : void
    {
        $grammar = new Grammar();
        self::assertEquals('Y-m-d H:i:s', $grammar->getDateFormat());
    }
}
