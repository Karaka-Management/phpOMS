<?php
/**
 * Jingga
 *
 * PHP Version 8.2
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
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Utils\IO\Json\InvalidJsonException::class)]
final class InvalidJsonExceptionTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testException() : void
    {
        self::assertInstanceOf(\UnexpectedValueException::class, new InvalidJsonException(''));
    }
}
