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

namespace phpOMS\tests\Utils\IO\Json;

use phpOMS\Utils\IO\Json\InvalidJsonException;

/**
 * @internal
 */
final class InvalidJsonExceptionTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers phpOMS\Utils\IO\Json\InvalidJsonException
     * @group framework
     */
    public function testException() : void
    {
        self::assertInstanceOf(\UnexpectedValueException::class, new InvalidJsonException(''));
    }
}
