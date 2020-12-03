<?php
/**
 * Orange Management
 *
 * PHP Version 8.0
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\tests\DataStorage;

require_once __DIR__ . '/../Autoloader.php';

use phpOMS\DataStorage\LockException;

/**
 * @internal
 */
class LockExceptionTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers phpOMS\DataStorage\LockException
     * @group framework
     */
    public function testException() : void
    {
        self::assertInstanceOf(\RuntimeException::class, new LockException(''));
    }
}
