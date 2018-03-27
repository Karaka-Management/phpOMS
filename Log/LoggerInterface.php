<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    phpOMS\Log
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */
declare(strict_types=1);

namespace phpOMS\Log;

/**
 * Logging interface.
 *
 * @package    phpOMS\Log
 * @license    OMS License 1.0
 * @link       http://website.orange-management.de
 * @since      1.0.0
 */
interface LoggerInterface
{

    /**
     * System is unusable.
     *
     * @param string $message Logging message schema
     * @param array  $context Context to log
     *
     * @return void
     */
    public function emergency(string $message, array $context = []) : void;

    /**
     * Action must be taken immediately.
     *
     * Example: Entire website down, database unavailable, etc. This should
     * trigger the SMS alerts and wake you up.
     *
     * @param string $message Logging message schema
     * @param array  $context Context to log
     *
     * @return void
     */
    public function alert(string $message, array $context = []) : void;

    /**
     * Critical conditions.
     *
     * Example: Application component unavailable, unexpected exception.
     *
     * @param string $message Logging message schema
     * @param array  $context Context to log
     *
     * @return void
     */
    public function critical(string $message, array $context = []) : void;

    /**
     * Runtime errors that do not require immediate action but should typically
     * be logged and monitored.
     *
     * @param string $message Logging message schema
     * @param array  $context Context to log
     *
     * @return void
     */
    public function error(string $message, array $context = []) : void;

    /**
     * Exceptional occurrences that are not errors.
     *
     * Example: Use of deprecated APIs, poor use of an API, undesirable things
     * that are not necessarily wrong.
     *
     * @param string $message Logging message schema
     * @param array  $context Context to log
     *
     * @return void
     */
    public function warning(string $message, array $context = []) : void;

    /**
     * Normal but significant events.
     *
     * @param string $message Logging message schema
     * @param array  $context Context to log
     *
     * @return void
     */
    public function notice(string $message, array $context = []) : void;

    /**
     * Interesting events.
     *
     * Example: User logs in, SQL logs.
     *
     * @param string $message Logging message schema
     * @param array  $context Context to log
     *
     * @return void
     */
    public function info(string $message, array $context = []) : void;

    /**
     * Detailed debug information.
     *
     * @param string $message Logging message schema
     * @param array  $context Context to log
     *
     * @return void
     */
    public function debug(string $message, array $context = []) : void;

    /**
     * Logs with an arbitrary level.
     *
     * @param string $level   Log level/severeness
     * @param string $message Logging message schema
     * @param array  $context Context to log
     *
     * @return void
     */
    public function log(string $level, string $message, array $context = []) : void;
}
