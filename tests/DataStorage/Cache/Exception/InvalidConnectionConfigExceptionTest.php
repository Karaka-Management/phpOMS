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

namespace phpOMS\tests\DataStorage\Cache\Exception;

use phpOMS\DataStorage\Cache\Exception\InvalidConnectionConfigException;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\DataStorage\Cache\Exception\InvalidConnectionConfigException::class)]
final class InvalidConnectionConfigExceptionTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testException() : void
    {
        self::assertInstanceOf(\InvalidArgumentException::class, new InvalidConnectionConfigException(''));
    }
}
