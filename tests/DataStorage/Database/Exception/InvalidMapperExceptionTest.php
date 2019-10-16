<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\tests\DataStorage\Database\Exception;

use phpOMS\DataStorage\Database\Exception\InvalidMapperException;

/**
 * @internal
 */
class InvalidMapperExceptionTest extends \PHPUnit\Framework\TestCase
{
    public function testException() : void
    {
        self::assertInstanceOf(\RuntimeException::class, new InvalidMapperException(''));
    }
}
