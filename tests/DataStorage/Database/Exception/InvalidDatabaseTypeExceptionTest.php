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

namespace phpOMS\tests\DataStorage\Database\Exception;

use phpOMS\DataStorage\Database\Exception\InvalidDatabaseTypeException;

/**
 * @internal
 */
final class InvalidDatabaseTypeExceptionTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers phpOMS\DataStorage\Database\Exception\InvalidDatabaseTypeException
     * @group framework
     */
    public function testException() : void
    {
        self::assertInstanceOf(\InvalidArgumentException::class, new InvalidDatabaseTypeException(''));
    }
}
