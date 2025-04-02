<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.2
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\tests\DataStorage;

require_once __DIR__ . '/../Autoloader.php';

use phpOMS\DataStorage\LockException;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\DataStorage\LockException::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\DataStorage\LockExceptionTest: Lock exception')]
final class LockExceptionTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The lock exception is an instance of the runtime exception')]
    public function testException() : void
    {
        self::assertInstanceOf(\RuntimeException::class, new LockException(''));
    }
}
