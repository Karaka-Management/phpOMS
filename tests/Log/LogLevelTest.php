<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.2
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\tests\Log;

require_once __DIR__ . '/../Autoloader.php';

use phpOMS\Log\LogLevel;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Log\LogLevelTest: Log level enum')]
final class LogLevelTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The log level enum has the correct number of log levels')]
    #[\PHPUnit\Framework\Attributes\CoversNothing]
    public function testEnumCount() : void
    {
        self::assertCount(8, LogLevel::getConstants());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The log level enum has only unique values')]
    #[\PHPUnit\Framework\Attributes\CoversNothing]
    public function testUnique() : void
    {
        self::assertEquals(LogLevel::getConstants(), \array_unique(LogLevel::getConstants()));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The log level enum has the correct values')]
    #[\PHPUnit\Framework\Attributes\CoversNothing]
    public function testEnums() : void
    {
        self::assertEquals('emergency', LogLevel::EMERGENCY);
        self::assertEquals('alert', LogLevel::ALERT);
        self::assertEquals('critical', LogLevel::CRITICAL);
        self::assertEquals('error', LogLevel::ERROR);
        self::assertEquals('warning', LogLevel::WARNING);
        self::assertEquals('notice', LogLevel::NOTICE);
        self::assertEquals('info', LogLevel::INFO);
        self::assertEquals('debug', LogLevel::DEBUG);
    }
}
