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

namespace phpOMS\tests\DataStorage\Database\Schema\Grammar;

use phpOMS\DataStorage\Database\Schema\Grammar\Grammar;

/**
 * @testdox phpOMS\tests\DataStorage\Database\Schema\GrammarTest: Basic sql query grammar
 *
 * @internal
 */
class GrammarTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox The grammar has the expected default values after initialization
     */
    public function testDefault() : void
    {
        $grammar = new Grammar();
        self::assertEquals('Y-m-d H:i:s', $grammar->getDateFormat());
        self::assertEquals('', $grammar->getTablePrefix());
    }

    /**
     * @testdox The grammar can define a default table prefix and return this value
     */
    public function testPrefixInputOutput() : void
    {
        $grammar = new Grammar();
        $grammar->setTablePrefix('oms_');
        self::assertEquals('oms_', $grammar->getTablePrefix());
    }
}
