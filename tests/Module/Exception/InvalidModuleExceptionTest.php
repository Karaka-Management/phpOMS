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

namespace phpOMS\tests\Module\Exception;

use phpOMS\Module\Exception\InvalidModuleException;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Module\Exception\InvalidModuleException::class)]
final class InvalidModuleExceptionTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testException() : void
    {
        self::assertInstanceOf(\UnexpectedValueException::class, new InvalidModuleException(''));
    }
}
