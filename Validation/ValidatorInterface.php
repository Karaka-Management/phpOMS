<?php
/**
 * Orange Management
 *
 * PHP Version 7.1
 *
 * @package    phpOMS\Validation
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */
declare(strict_types=1);

namespace phpOMS\Validation;

/**
 * Validator interface.
 *
 * @package    phpOMS\Validation
 * @license    OMS License 1.0
 * @link       http://website.orange-management.de
 * @since      1.0.0
 */
interface ValidatorInterface
{

    /**
     * Check if value is valid.
     *
     * @param mixed $value       Value to validate
     * @param array $constraints Constraints for validation
     *
     * @return bool
     *
     * @since  1.0.0
     */
    public static function isValid($value, array $constraints = null);

    /**
     * Get most recent error string.
     *
     * @return string
     *
     * @since  1.0.0
     */
    public static function getMessage() : string;

    /**
     * Get most recent error code.
     *
     * @return int
     *
     * @since  1.0.0
     */
    public static function getErrorCode() : int;

    /**
     * Reset error information
     *
     * @return void
     *
     * @since  1.0.0
     */
    public static function resetError() : void;
}
