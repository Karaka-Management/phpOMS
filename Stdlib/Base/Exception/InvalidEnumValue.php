<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    phpOMS\Stdlib\Base\Exception
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Stdlib\Base\Exception;

/**
 * Filesystem class.
 *
 * Performing operations on the file system
 *
 * @package    phpOMS\Stdlib\Base\Exception
 * @license    OMS License 1.0
 * @link       https://orange-management.org
 * @since      1.0.0
 */
class InvalidEnumValue extends \UnexpectedValueException
{

    /**
     * Constructor.
     *
     * @param mixed      $message  Exception message
     * @param int        $code     Exception code
     * @param \Exception $previous Previous exception
     *
     * @since  1.0.0
     */
    public function __construct($message, int $code = 0, \Exception $previous = null)
    {
        parent::__construct('The enum value "' . $message . '" is not valid.', $code, $previous);
    }
}
