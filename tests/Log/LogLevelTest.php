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

namespace phpOMS\tests\Log;

require_once __DIR__ . '/../Autoloader.php';

use phpOMS\Log\LogLevel;

/**
 * @testdox phpOMS\tests\Log\LogLevelTest: Log level enum
 * @internal
 */
final class LogLevelTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox The log level enum has the correct number of log levels
     * @group framework
     * @coversNothing
     */
    public function testEnumCount() : void
    {
        self::assertCount(8, LogLevel::getConstants());
    }

    /**
     * @testdox The log level enum has only unique values
     * @group framework
     * @coversNothing
     */
    public function testUnique() : void
    {
        self::assertEquals(LogLevel::getConstants(), \array_unique(LogLevel::getConstants()));
    }

    /**
     * @testdox The log level enum has the correct values
     * @group framework
     * @coversNothing
     */
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
