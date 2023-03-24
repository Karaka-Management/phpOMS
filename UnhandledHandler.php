<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   phpOMS
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS;

use phpOMS\Log\FileLogger;

/**
 * Default exception and error handler.
 *
 * @package phpOMS
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class UnhandledHandler
{
    /**
     * Exception handler.
     *
     * @param \Throwable $e Exception
     *
     * @return void
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    public static function exceptionHandler(\Throwable $e) : void
    {
        $logger = FileLogger::getInstance(__DIR__ . '/../Logs');
        $logger->critical(FileLogger::MSG_FULL, [
            'message' => $e->getMessage(),
            'line'    => $e->getLine(),
            'file'    => $e->getFile(),
        ]);
    }

    /**
     * Error handler.
     *
     * @param int    $errno   Error number
     * @param string $errstr  Error message
     * @param string $errfile Error file
     * @param int    $errline Error line
     *
     * @return bool Returns true if the error could be logged otherwise false is returned
     *
     * @since 1.0.0
     */
    public static function errorHandler(int $errno, string $errstr, string $errfile, int $errline) : bool
    {
        $logger = FileLogger::getInstance(__DIR__ . '/../Logs');

        if (!(\error_reporting() & $errno)) {
            \error_clear_last();

            return false;
        }

        \error_clear_last();

        $logger->error(FileLogger::MSG_FULL, [
            'message' => 'Undefined error',
            'str'     => $errstr,
            'line'    => $errline,
            'file'    => $errfile,
        ]);

        return true;
    }

    /**
     * Shutdown handler.
     *
     * @return void
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    public static function shutdownHandler() : void
    {
        $e = \error_get_last();
        \error_clear_last();

        if ($e === null) {
            return;
        }

        $logger = FileLogger::getInstance(__DIR__ . '/../Logs');
        $logger->warning(FileLogger::MSG_FULL, [
            'message' => $e['message'],
            'line'    => $e['line'],
            'file'    => $e['file'],
        ]);
    }
}
