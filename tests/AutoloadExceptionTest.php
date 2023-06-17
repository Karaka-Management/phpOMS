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

namespace phpOMS\tests;

use phpOMS\AutoloadException;

/**
 * @internal
 */
final class AutoloadExceptionTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers phpOMS\AutoloadException
     * @group framework
     */
    public function testException() : void
    {
        self::assertInstanceOf(\RuntimeException::class, new AutoloadException(''));
    }
}
