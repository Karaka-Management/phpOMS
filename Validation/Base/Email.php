<?php
/**
 * Orange Management
 *
 * PHP Version 7.1
 *
 * @category   TBD
 * @package    TBD
 * @author     OMS Development Team <dev@oms.com>
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://orange-management.com
 */
declare(strict_types=1);

namespace phpOMS\Validation\Base;

use phpOMS\Validation\ValidatorAbstract;

/**
 * Validator abstract.
 *
 * @category   Validation
 * @package    Framework
 * @author     OMS Development Team <dev@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
class Email extends ValidatorAbstract
{

    /**
     * Constructor.
     *
     * @since  1.0.0
     */
    private function __construct()
    {
    }

    /**
     * {@inheritdoc}
     */
    public static function isValid(string $value) : bool
    {
        if (filter_var($value, FILTER_VALIDATE_EMAIL) === false) {
            self::$msg   = 'Invalid Email by filter_var standards';
            self::$error = 1;

            return false;
        }

        self::$msg   = '';
        self::$error = 0;

        return true;
    }
}
