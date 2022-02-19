<?php
/**
 * Karaka
 *
 * PHP Version 8.0
 *
 * @package   phpOMS\System\File
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://karaka.app
 */
declare(strict_types=1);

namespace phpOMS\System\File;

/**
 * Path exception class.
 *
 * @package phpOMS\System\File
 * @license OMS License 1.0
 * @link    https://karaka.app
 * @since   1.0.0
 */
final class PathException extends \UnexpectedValueException
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
    public function __construct(string $message, int $code = 0, \Exception $previous = null)
    {
        parent::__construct('The path "' . $message . '" is not a valid path.', $code, $previous);
    }
}
