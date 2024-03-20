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

namespace phpOMS\tests\Uri;

require_once __DIR__ . '/../Autoloader.php';

use phpOMS\Uri\InvalidUriException;

/**
 * @testdox phpOMS\tests\Uri\InvalidUriExceptionTest: Invalid uri exception
 * @internal
 */
final class InvalidUriExceptionTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox The invalid uri exception is an unexpected value exception
     * @covers \phpOMS\Uri\InvalidUriException
     * @group framework
     */
    public function testException() : void
    {
        self::assertInstanceOf(\UnexpectedValueException::class, new InvalidUriException(''));
    }
}
