<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Math\Matrix\Exception
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Math\Matrix\Exception;

/**
 * Zero devision exception.
 *
 * @package phpOMS\Math\Matrix\Exception
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class InvalidDimensionException extends \UnexpectedValueException
{
    /**
     * Constructor.
     *
     * @param mixed      $message  Exception message
     * @param int        $code     Exception code
     * @param \Exception $previous Previous exception
     *
     * @since 1.0.0
     */
    public function __construct($message, int $code = 0, ?\Exception $previous = null)
    {
        parent::__construct('Dimension "' . $message . '" is not valid.', $code, $previous);
    }
}
