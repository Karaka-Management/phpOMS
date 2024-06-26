<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   phpOMS\Utils\IO\Json
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Utils\IO\Json;

/**
 * Json decoding exception class.
 *
 * @package phpOMS\Utils\IO\Json
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class InvalidJsonException extends \UnexpectedValueException
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
    public function __construct($message, $code = 0, ?\Exception $previous = null)
    {
        parent::__construct('Couldn\'t parse "' . $message . '" as valid json.', $code, $previous);
    }
}
