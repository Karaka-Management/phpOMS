<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Validation
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Validation;

/**
 * Validator interface.
 *
 * @package phpOMS\Validation
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
interface ValidatorInterface
{
    /**
     * Check if value is valid.
     *
     * @param mixed      $value       Value to validate
     * @param null|array $constraints Constraints for validation
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public static function isValid(mixed $value, array $constraints = null) : bool;

    /**
     * Get most recent error string.
     *
     * @return string
     *
     * @since 1.0.0
     */
    public static function getMessage() : string;

    /**
     * Get most recent error code.
     *
     * @return int
     *
     * @since 1.0.0
     */
    public static function getErrorCode() : int;

    /**
     * Reset error information
     *
     * @return void
     *
     * @since 1.0.0
     */
    public static function resetError() : void;
}
