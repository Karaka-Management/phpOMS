<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package    phpOMS\Math\Number
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Math\Number;

/**
 * Natura number class
 *
 * @package    phpOMS\Math\Number
 * @license    OMS License 1.0
 * @link       https://orange-management.org
 * @since      1.0.0
 */
final class Natural
{
    /**
     * Constructor.
     *
     * @since  1.0.0
     * @codeCoverageIgnore
     */
    private function __construct()
    {

    }

    /**
     * Is natural number.
     *
     * @param mixed $value Value to test
     *
     * @return bool
     *
     * @since  1.0.0
     */
    public static function isNatural($value) : bool
    {
        return \is_int($value) && $value >= 0;
    }
}
