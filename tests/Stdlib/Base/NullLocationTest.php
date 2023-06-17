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

namespace phpOMS\tests\Stdlib\Base;

use phpOMS\Stdlib\Base\NullLocation;

/**
 * @internal
 */
final class NullLocationTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers phpOMS\Stdlib\Base\NullLocation
     * @group framework
     */
    public function testNullLocation() : void
    {
        self::assertInstanceOf('\phpOMS\Stdlib\Base\Location', new NullLocation());
    }
}
