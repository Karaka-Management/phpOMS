<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    TBD
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */
declare(strict_types=1);

namespace phpOMS\Stdlib\Base\Exception;

/**
 * Filesystem class.
 *
 * Performing operations on the file system
 *
 * @package    Framework
 * @license    OMS License 1.0
 * @link       http://website.orange-management.de
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
