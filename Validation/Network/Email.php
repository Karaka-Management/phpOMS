<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Validation\Network
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Validation\Network;

use phpOMS\Validation\ValidatorAbstract;

/**
 * Validate email.
 *
 * @package phpOMS\Validation\Network
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
abstract class Email extends ValidatorAbstract
{
    /**
     * Constructor.
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    private function __construct()
    {
    }

    /**
     * {@inheritdoc}
     */
    public static function isValid(mixed $value, array $constraints = null) : bool
    {
        if (\filter_var($value, \FILTER_VALIDATE_EMAIL) === false) {
            self::$msg   = 'Invalid Email by filter_var standards';
            self::$error = 1;

            return false;
        }

        self::$msg   = '';
        self::$error = 0;

        return true;
    }
}
