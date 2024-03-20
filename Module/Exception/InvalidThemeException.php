<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   phpOMS\Module\Exception
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Module\Exception;

/**
 * Zero devision exception.
 *
 * @package phpOMS\Module\Exception
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class InvalidThemeException extends \UnexpectedValueException
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
    public function __construct(string $message, int $code = 0, ?\Exception $previous = null)
    {
        parent::__construct('Data for theme "' . $message . '" could be found.', $code, $previous);
    }
}
