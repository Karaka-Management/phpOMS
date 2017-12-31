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
declare(strict_types = 1);

namespace phpOMS\Validation;

/**
 * Validator abstract.
 *
 * @package    phpOMS\Validation
 * @license    OMS License 1.0
 * @link       http://website.orange-management.de
 * @since      1.0.0
 */
abstract class ValidatorAbstract implements ValidatorInterface
{

    /**
     * Error code.
     *
     * @var int
     * @since 1.0.0
     */
    protected static $error = 0;

    /**
     * Message string.
     *
     * @var string
     * @since 1.0.0
     */
    protected static $msg = '';

    /**
     * {@inheritdoc}
     */
    public static function getMessage() : string
    {
        return self::$msg;
    }

    /**
     * {@inheritdoc}
     */
    public static function getErrorCode() : int
    {
        return self::$error;
    }

    /**
     * Reset error information
     *
     * @return bool
     *
     * @since  1.0.0
     */
    public static function resetError() /* : void */
    {
        self::$error = 0;
        self::$msg = '';
    }
}
