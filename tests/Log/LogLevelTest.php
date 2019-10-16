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

namespace phpOMS\tests\Log;

require_once __DIR__ . '/../Autoloader.php';

use phpOMS\Log\LogLevel;

/**
 * @internal
 */
class LogLevelTest extends \PHPUnit\Framework\TestCase
{
    public function testEnums() : void
    {
        self::assertCount(8, LogLevel::getConstants());
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
