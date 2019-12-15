<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   phpOMS\Validation\Network
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Validation\Network;

use phpOMS\Validation\ValidatorAbstract;

/**
 * Validate email.
 *
 * @package phpOMS\Validation\Network
 * @license OMS License 1.0
 * @link    https://orange-management.org
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
    public static function isValid($value, array $constraints = null) : bool
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
