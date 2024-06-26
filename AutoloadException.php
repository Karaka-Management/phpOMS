<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   phpOMS
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS;

/**
 * Autoloader exception
 *
 * This exception is thrown if a file couldn't be autoloaded
 *
 * @package phpOMS
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class AutoloadException extends \RuntimeException
{
    /**
     * Constructor.
     *
     * @param string     $message  Exception message
     * @param int        $code     Exception code
     * @param \Exception $previous Previous exception
     *
     * @since 1.0.0
     */
    public function __construct(string $message, int $code = 0, ?\Exception $previous = null)
    {
        parent::__construct('File "' . $message . '" could not get loaded.', $code, $previous);
    }
}
