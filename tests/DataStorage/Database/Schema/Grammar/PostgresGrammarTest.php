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

use phpOMS\DataStorage\Database\Schema\Grammar\PostgresGrammar;

/**
 * @internal
 */
class PostgresGrammarTest extends \PHPUnit\Framework\TestCase
{
    public function testDefault() : void
    {
        self::assertInstanceOf('\phpOMS\DataStorage\Database\Schema\Grammar\Grammar', new PostgresGrammar());
    }
}
