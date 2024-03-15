<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Module
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Module;

use phpOMS\Log\FileLogger;

/**
 * Mull module class.
 *
 * @package phpOMS\Module
 * @license OMS License 2.0
 * @link    https://jingga.app
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
