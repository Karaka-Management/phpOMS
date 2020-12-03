<?php
/**
 * Orange Management
 *
 * PHP Version 8.0
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\tests\DataStorage\Database\Query;

use phpOMS\DataStorage\Database\Query\Into;

/**
 * @internal
 */
class IntoTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers phpOMS\DataStorage\Database\Query\Into
     * @group framework
     */
    public function testDefault() : void
    {
        self::assertInstanceOf('\phpOMS\DataStorage\Database\Query\Builder', new Into($GLOBALS['dbpool']->get()));
    }
}
