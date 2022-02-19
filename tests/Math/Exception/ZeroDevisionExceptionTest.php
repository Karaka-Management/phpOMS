<?php
/**
 * Karaka
 *
 * PHP Version 8.0
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://karaka.app
 */
declare(strict_types=1);

namespace phpOMS\tests\Math\Exception;

use phpOMS\Math\Exception\ZeroDivisionException;

/**
 * @internal
 */
final class ZeroDivisionExceptionTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers phpOMS\Math\Exception\ZeroDivisionException
     * @group framework
     */
    public function testException() : void
    {
        self::assertInstanceOf(\UnexpectedValueException::class, new ZeroDivisionException());
    }
}
