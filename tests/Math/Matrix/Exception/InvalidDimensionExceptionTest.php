<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://karaka.app
 */
declare(strict_types=1);

namespace phpOMS\tests\Math\Matrix\Exception;

use phpOMS\Math\Matrix\Exception\InvalidDimensionException;

/**
 * @internal
 */
final class InvalidDimensionExceptionTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers phpOMS\Math\Matrix\Exception\InvalidDimensionException
     * @group framework
     */
    public function testException() : void
    {
        self::assertInstanceOf(\UnexpectedValueException::class, new InvalidDimensionException(''));
    }
}
