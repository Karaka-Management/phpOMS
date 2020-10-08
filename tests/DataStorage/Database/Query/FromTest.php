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

namespace phpOMS\tests\DataStorage\Database\Query;

use phpOMS\DataStorage\Database\Query\From;

/**
 * @internal
 */
class FromTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers phpOMS\DataStorage\Database\Query\From
     * @group framework
     */
    public function testDefault() : void
    {
        self::assertInstanceOf('\phpOMS\DataStorage\Database\Query\Builder', new From($GLOBALS['dbpool']->get()));
    }
}
