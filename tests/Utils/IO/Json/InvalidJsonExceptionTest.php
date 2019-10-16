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

namespace phpOMS\tests\Utils\IO\Json;

use phpOMS\Utils\IO\Json\InvalidJsonException;

/**
 * @internal
 */
class InvalidJsonExceptionTest extends \PHPUnit\Framework\TestCase
{
    public function testException() : void
    {
        self::assertInstanceOf(\UnexpectedValueException::class, new InvalidJsonException(''));
    }
}
