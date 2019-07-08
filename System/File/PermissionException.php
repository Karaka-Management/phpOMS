<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    phpOMS\System\File
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\System\File;

/**
 * Permission exception class.
 *
 * @package    phpOMS\System\File
 * @license    OMS License 1.0
 * @link       https://orange-management.org
 * @since      1.0.0
 */
final class PermissionException extends \RuntimeException
{
    /**
     * Constructor.
     *
     * @param string     $message  Exception message
     * @param int        $code     Exception code
     * @param \Exception $previous Previous exception
     *
     * @since  1.0.0
     */
    public function __construct(string $message, int $code = 0, \Exception $previous = null)
    {
        parent::__construct('Insufficient permissions for "' . $message . '" operations.', $code, $previous);
    }
}
