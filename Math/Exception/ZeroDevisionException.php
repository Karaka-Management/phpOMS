<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    phpOMS\Math\Exception
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Math\Exception;

/**
 * Zero devision exception.
 *
 * @package    phpOMS\Math\Exception
 * @license    OMS License 1.0
 * @link       https://orange-management.org
 * @since      1.0.0
 */
final class ZeroDevisionException extends \UnexpectedValueException
{
    /**
     * Constructor.
     *
     * @param int        $code     Exception code
     * @param \Exception $previous Previous exception
     *
     * @since  1.0.0
     */
    public function __construct(int $code = 0, \Exception $previous = null)
    {
        parent::__construct('Devision by zero is not defined.', $code, $previous);
    }
}
