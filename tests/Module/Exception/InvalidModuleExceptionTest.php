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

namespace phpOMS\tests\Module\Exception;

use phpOMS\Module\Exception\InvalidModuleException;

/**
 * @internal
 */
final class InvalidModuleExceptionTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers phpOMS\Module\Exception\InvalidModuleException
     * @group framework
     */
    public function testException() : void
    {
        self::assertInstanceOf(\UnexpectedValueException::class, new InvalidModuleException(''));
    }
}
