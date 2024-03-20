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

namespace phpOMS\tests\DataStorage\Database\Query\Grammar;

use phpOMS\DataStorage\Database\Query\Grammar\SqlServerGrammar;

/**
 * @internal
 */
final class SqlServerGrammarTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers \phpOMS\DataStorage\Database\Query\Grammar\SqlServerGrammar
     * @group framework
     */
    public function testDefault() : void
    {
        self::assertInstanceOf('\phpOMS\DataStorage\Database\Query\Grammar\Grammar', new SqlServerGrammar());
    }
}
