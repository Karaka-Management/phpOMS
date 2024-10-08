<?php
/**
 * Jingga
 *
 * PHP Version 8.2
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
 * Validator abstract.
 *
 * @package phpOMS\Validation
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
abstract class ValidatorAbstract implements ValidatorInterface
{
    /**
     * Error code.
     *
     * @var int
     * @since 1.0.0
     */
    protected static int $error = 0;

    /**
     * Message string.
     *
     * @var string
     * @since 1.0.0
     */
    protected static string $msg = '';

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
     * {@inheritdoc}
     */
    public static function resetError() : void
    {
        self::$error = 0;
        self::$msg   = '';
    }
}
