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

namespace phpOMS\tests\Uri;

require_once __DIR__ . '/../Autoloader.php';

use phpOMS\Uri\InvalidUriException;

/**
 * @internal
 */
final class InvalidUriExceptionTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers phpOMS\Uri\InvalidUriException
     * @group framework
     */
    public function testException() : void
    {
        self::assertInstanceOf(\UnexpectedValueException::class, new InvalidUriException(''));
    }
}
