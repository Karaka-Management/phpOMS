<?php
/**
 * Orange Management
 *
 * PHP Version 8.0
 *
 * @package   phpOMS\Uri
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Uri;

/**
 * Uri exception.
 *
 * @package phpOMS\Uri
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
class InvalidUriException extends \UnexpectedValueException
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
        parent::__construct('The uri "' . $message . '" is not valid.', $code, $previous);
    }
}
