<?php
/**
 * Karaka
 *
 * PHP Version 8.0
 *
 * @package   phpOMS\Module
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://karaka.app
 */
declare(strict_types=1);

namespace phpOMS\Module;

use phpOMS\Log\FileLogger;

/**
 * Module abstraction class.
 *
 * @package phpOMS\Module
 * @license OMS License 1.0
 * @link    https://karaka.app
 * @since   1.0.0
 */
final class NullModule extends ModuleAbstract
{
    /** {@inheritdoc} */
    public function __call(string $name, array $arguments) : void
    {
        self::__callStatic($name, $arguments);
    }

    /** {@inheritdoc} */
    public static function __callStatic(string $name, array $arguments) : void
    {
        FileLogger::getInstance()
            ->error(
                FileLogger::MSG_FULL, [
                    'message' => 'Expected module/controller but got NullModule.',
                    'line'    => __LINE__,
                    'file'    => self::class,
                ]
            );
    }
}
