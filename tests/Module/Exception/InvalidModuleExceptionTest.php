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

namespace phpOMS\tests\Module\Exception;

use phpOMS\Module\Exception\InvalidModuleException;

/**
 * @internal
 */
class InvalidModuleExceptionTest extends \PHPUnit\Framework\TestCase
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
