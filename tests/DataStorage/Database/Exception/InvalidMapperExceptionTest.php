<?php
/**
 * Karaka
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

use phpOMS\DataStorage\Database\Exception\InvalidMapperException;

/**
 * @internal
 */
final class InvalidMapperExceptionTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers phpOMS\DataStorage\Database\Exception\InvalidMapperException
     * @group framework
     */
    public function testException() : void
    {
        self::assertInstanceOf(\RuntimeException::class, new InvalidMapperException(''));

        $e = new InvalidMapperException('TestMapper');
        self::assertStringContainsString('TestMapper', $e->getMessage());
    }
}
