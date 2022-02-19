<?php
/**
 * Karaka
 *
 * PHP Version 8.0
 *
 * @package   phpOMS\Math\Exception
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://karaka.app
 */
declare(strict_types=1);

namespace phpOMS\Math\Exception;

/**
 * Zero division exception.
 *
 * @package phpOMS\Math\Exception
 * @license OMS License 1.0
 * @link    https://karaka.app
 * @since   1.0.0
 */
final class ZeroDivisionException extends \UnexpectedValueException
{
    /**
     * Constructor.
     *
     * @param int        $code     Exception code
     * @param \Exception $previous Previous exception
     *
     * @since 1.0.0
     */
    public function __construct(int $code = 0, \Exception $previous = null)
    {
        parent::__construct('Division by zero is not defined.', $code, $previous);
    }
}
