<?php
/**
 * Jingga
 *
 * PHP Version 8.2
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
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Uri\InvalidUriException::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Uri\InvalidUriExceptionTest: Invalid uri exception')]
final class InvalidUriExceptionTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The invalid uri exception is an unexpected value exception')]
    public function testException() : void
    {
        self::assertInstanceOf(\UnexpectedValueException::class, new InvalidUriException(''));
    }
}
