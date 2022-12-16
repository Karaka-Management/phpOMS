<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\DataStorage\Database\Exception
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\DataStorage\Database\Exception;

/**
 * Permission exception class.
 *
 * @package phpOMS\DataStorage\Database\Exception
 * @license OMS License 1.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class InvalidConnectionConfigException extends \InvalidArgumentException
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
    public function __construct(string $message = '', int $code = 0, \Exception $previous = null)
    {
        parent::__construct('Missing config value for "' . $message . '".', $code, $previous);
    }
}
