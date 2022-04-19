<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Stdlib\Base\Exception
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://karaka.app
 */
declare(strict_types=1);

namespace phpOMS\Stdlib\Base\Exception;

/**
 * Filesystem class.
 *
 * Performing operations on the file system
 *
 * @package phpOMS\Stdlib\Base\Exception
 * @license OMS License 1.0
 * @link    https://karaka.app
 * @since   1.0.0
 */
class InvalidEnumName extends \UnexpectedValueException
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
        parent::__construct('The enum name "' . $message . '" is not valid.', $code, $previous);
    }
}
