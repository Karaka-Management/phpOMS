<?php
/**
 * Jingga
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

use phpOMS\DataStorage\Database\Schema\Grammar\OracleGrammar;

/**
 * @internal
 */
final class OracleGrammarTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers \phpOMS\DataStorage\Database\Schema\Grammar\OracleGrammar
     * @group framework
     */
    public function testDefault() : void
    {
        self::assertInstanceOf('\phpOMS\DataStorage\Database\Schema\Grammar\Grammar', new OracleGrammar());
    }
}
