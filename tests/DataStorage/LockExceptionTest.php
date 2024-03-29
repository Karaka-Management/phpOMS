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

namespace phpOMS\tests\DataStorage;

require_once __DIR__ . '/../Autoloader.php';

use phpOMS\DataStorage\LockException;

/**
 * @testdox phpOMS\tests\DataStorage\LockExceptionTest: Lock exception
 * @internal
 */
final class LockExceptionTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox The lock exception is an instance of the runtime exception
     * @covers phpOMS\DataStorage\LockException
     * @group framework
     */
    public function testException() : void
    {
        self::assertInstanceOf(\RuntimeException::class, new LockException(''));
    }
}
