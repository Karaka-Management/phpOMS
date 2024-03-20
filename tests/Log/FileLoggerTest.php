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

use phpOMS\Log\FileLogger;
use phpOMS\Log\LogLevel;
use phpOMS\Utils\TestUtils;

require_once __DIR__ . '/../Autoloader.php';

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Log\FileLogger::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Log\FileLoggerTest: File logger for saving log information in a local file')]
final class FileLoggerTest extends \PHPUnit\Framework\TestCase
{
    protected FileLogger $log;

    /**
     * {@inheritdoc}
     */
    protected function setUp() : void
    {
        if (\is_file(__DIR__ . '/' . \date('Y-m-d') . '.log')) {
            \unlink(__DIR__ . '/' . \date('Y-m-d') . '.log');
        }

        if (\is_file(__DIR__ . '/test.log')) {
            \unlink(__DIR__ . '/test.log');
        }

        $this->log = new FileLogger(__DIR__ . '/test.log', false);
    }

    protected function tearDown() : void
    {
        if (\is_file(__DIR__ . '/' . \date('Y-m-d') . '.log')) {
            \unlink(__DIR__ . '/' . \date('Y-m-d') . '.log');
        }

        if (\is_file(__DIR__ . '/test.log')) {
            \unlink(__DIR__ . '/test.log');
        }
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The logger has the expected default values after initialization')]
    public function testDefault() : void
    {
        self::assertEquals([], $this->log->countLogs());
        self::assertEquals([], $this->log->getHighestPerpetrator());
        self::assertEquals([], $this->log->get());
        self::assertEquals([], $this->log->getByLine());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The file logger can automatically create a new instance if none exists')]
    public function testFileLoggerInstance() : void
    {
        if (\is_file(__DIR__ . '/named.log')) {
            \unlink(__DIR__ . '/named.log');
        }

        $instance = FileLogger::getInstance(__DIR__ . '/named.log', false);
        TestUtils::getMember($instance, 'instance');

        $log = FileLogger::getInstance(__DIR__ . '/named.log', false);
        self::assertInstanceOf(FileLogger::class, $log);

        TestUtils::setMember($instance, 'instance', $instance);

        if (\is_file(__DIR__ . '/named.log')) {
            \unlink(__DIR__ . '/named.log');
        }
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A log file for the output can be specified for the file logger')]
    public function testNamedLogFile() : void
    {
        if (\is_file(__DIR__ . '/named.log')) {
            \unlink(__DIR__ . '/named.log');
        }

        $log = new FileLogger(__DIR__ . '/named.log', false);

        $log->info('something');
        self::assertTrue(\is_file(__DIR__ . '/named.log'));

        if (\is_file(__DIR__ . '/named.log')) {
            \unlink(__DIR__ . '/named.log');
        }
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('If no log file name is specified a log file per date is created')]
    public function testUnnamedLogFile() : void
    {
        $log = new FileLogger(__DIR__, false);

        $log->info('something');
        self::assertTrue(\is_file(__DIR__ . '/' . \date('Y-m-d') . '.log'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('If no logs are performed no log file will be created')]
    public function testNoFileIfNoLog() : void
    {
        $log = new FileLogger(__DIR__, false);

        self::assertFalse(\is_file(__DIR__ . '/' . \date('Y-m-d') . '.log'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Logs with different levels get correctly stored in the log file')]
    public function testLogInputOutput() : void
    {
        $this->log->emergency(FileLogger::MSG_FULL, [
            'message' => 'emergency1',
            'line'    => 11,
            'file'    => self::class,
        ]);

        $this->log->alert(FileLogger::MSG_FULL, [
            'message' => 'alert2',
            'line'    => 11,
            'file'    => self::class,
        ]);

        $this->log->critical(FileLogger::MSG_FULL, [
            'message' => 'critical3',
            'line'    => 11,
            'file'    => self::class,
        ]);

        $this->log->error(FileLogger::MSG_FULL, [
            'message' => 'error4',
            'line'    => 11,
            'file'    => self::class,
        ]);

        $this->log->warning(FileLogger::MSG_FULL, [
            'message' => 'warning5',
            'line'    => 11,
            'file'    => self::class,
        ]);

        $this->log->notice(FileLogger::MSG_FULL, [
            'message' => 'notice6',
            'line'    => 11,
            'file'    => self::class,
        ]);

        $this->log->info(FileLogger::MSG_FULL, [
            'message' => 'info7',
            'line'    => 11,
            'file'    => self::class,
        ]);

        $this->log->debug(FileLogger::MSG_FULL, [
            'message' => 'debug8',
            'line'    => 11,
            'file'    => self::class,
        ]);

        $this->log->log(LogLevel::DEBUG, FileLogger::MSG_FULL, [
            'message' => 'log9',
            'line'    => 11,
            'file'    => self::class,
        ]);

        $this->log->console(FileLogger::MSG_FULL, false, [
            'message' => 'console10',
            'line'    => 11,
            'file'    => self::class,
        ]);

        $logContent = \file_get_contents(__DIR__ . '/test.log');

        self::assertTrue(\stripos($logContent, 'emergency1') !== false);
        self::assertTrue(\stripos($logContent, 'alert2') !== false);
        self::assertTrue(\stripos($logContent, 'critical3') !== false);
        self::assertTrue(\stripos($logContent, 'error4') !== false);
        self::assertTrue(\stripos($logContent, 'warning5') !== false);
        self::assertTrue(\stripos($logContent, 'notice6') !== false);
        self::assertTrue(\stripos($logContent, 'info7') !== false);
        self::assertTrue(\stripos($logContent, 'debug8') !== false);
        self::assertTrue(\stripos($logContent, 'log9') !== false);
        self::assertTrue(\stripos($logContent, 'console10') !== false);

        self::assertEquals(1, $this->log->countLogs()['emergency'] ?? 0);
        self::assertEquals(1, $this->log->countLogs()['alert'] ?? 0);
        self::assertEquals(1, $this->log->countLogs()['critical'] ?? 0);
        self::assertEquals(1, $this->log->countLogs()['error'] ?? 0);
        self::assertEquals(1, $this->log->countLogs()['warning'] ?? 0);
        self::assertEquals(1, $this->log->countLogs()['notice'] ?? 0);
        self::assertEquals(2, $this->log->countLogs()['info'] ?? 0);
        self::assertEquals(2, $this->log->countLogs()['debug'] ?? 0);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Log files can be analyzed for the highest perpetrator (IP address)')]
    public function testPerpetrator() : void
    {
        $this->log->emergency(FileLogger::MSG_FULL, [
            'message' => 'msg',
            'line'    => 11,
            'file'    => self::class,
        ]);

        self::assertEquals(['0.0.0.0' => 1], $this->log->getHighestPerpetrator());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Logs can be read from the log file')]
    public function testReadLogs() : void
    {
        $this->log->emergency(FileLogger::MSG_FULL, [
            'message' => 'emergency1',
            'line'    => 11,
            'file'    => self::class,
        ]);

        $this->log->info(FileLogger::MSG_FULL, [
            'message' => 'info2',
            'line'    => 11,
            'file'    => self::class,
        ]);

        $this->log->error(FileLogger::MSG_FULL, [
            'message' => 'error3',
            'line'    => 11,
            'file'    => self::class,
        ]);

        $this->log->warning(FileLogger::MSG_FULL, [
            'message' => 'warning4',
            'line'    => 11,
            'file'    => self::class,
        ]);

        $logs = $this->log->get(2, 1);

        self::assertEquals(2, \count($logs));
        self::assertEquals('info2', $logs[2][7]);
        self::assertEquals('error3', $logs[3][7]);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Invalid log reads return empty log data')]
    public function testInvalidReadLogs() : void
    {
        $this->log->emergency(FileLogger::MSG_FULL, [
            'message' => 'emergency1',
            'line'    => 11,
            'file'    => self::class,
        ]);

        $logs = $this->log->get(2, 1);

        self::assertEquals([], $logs);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A line can be read from a log file')]
    public function testReadLine() : void
    {
        $this->log->alert(FileLogger::MSG_FULL, [
            'message' => 'msg',
            'line'    => 11,
            'file'    => self::class,
        ]);

        self::assertEquals('alert', $this->log->getByLine(1)[1]);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('None-existing lines return on read empty log data')]
    public function testInvalidReadLine() : void
    {
        $this->log->emergency(FileLogger::MSG_FULL, [
            'message' => 'msg',
            'line'    => 11,
            'file'    => self::class,
        ]);

        self::assertEquals([], $this->log->getByLine(2));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A verbose file logger automatically outputs log data')]
    public function testVerboseLogger() : void
    {
        $this->log = new FileLogger(__DIR__, true);

        \ob_start();
        $this->log->info('my log message');
        $ob = \ob_get_clean();
        \ob_clean();

        self::assertEquals('my log message' . "\n", $ob);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A verbose console log outputs log data')]
    public function testVerboseLog() : void
    {
        $this->log = new FileLogger(__DIR__, false);

        \ob_start();
        $this->log->console('my log message', true);
        $ob = \ob_get_clean();
        \ob_clean();

        self::assertTrue(\stripos($ob, 'my log message') !== false);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A invalid log type throws a InvalidEnumValue')]
    public function testLogException() : void
    {
        $this->expectException(\phpOMS\Stdlib\Base\Exception\InvalidEnumValue::class);

        $this->log = new FileLogger(__DIR__ . '/test.log');
        $this->log->log('testException', FileLogger::MSG_FULL, [
            'message' => 'msg',
            'line'    => 11,
            'file'    => self::class,
        ]);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The logger can perform timings for internal duration logging')]
    public function testTiming() : void
    {
        self::assertTrue(FileLogger::startTimeLog('test'));
        self::assertGreaterThan(0.0, FileLogger::endTimeLog('test'));
    }
}
