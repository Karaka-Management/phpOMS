<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\DataStorage\Database\Exception
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\DataStorage\Database\Exception;

/**
 * Permission exception class.
 *
 * @package phpOMS\DataStorage\Database\Exception
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class InvalidMapperException extends \RuntimeException
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
        if ($message === '') {
            parent::__construct('Empty mapper.', $code, $previous);
        } else {
            parent::__construct('Mapper "' . $message . '" is invalid.', $code, $previous);
        }
    }
}
