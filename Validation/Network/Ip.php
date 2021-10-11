<?php
/**
 * Orange Management
 *
 * PHP Version 8.0
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
 * Validate IP.
 *
 * @package phpOMS\Validation\Network
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
abstract class Ip extends ValidatorAbstract
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
        return filter_var($value, \FILTER_VALIDATE_IP) !== false;
    }

    /**
     * Validate IPv6
     *
     * @param mixed $value to validate
     *
     * @return bool Returns true if value is valid ip6 otherwise false
     *
     * @since 1.0.0
     */
    public static function isValidIpv6(mixed $value) : bool
    {
        return filter_var($value, \FILTER_VALIDATE_IP, \FILTER_FLAG_IPV6) !== false;
    }

    /**
     * Validate IPv4
     *
     * @param mixed $value to validate
     *
     * @return bool eturns true if value is valid ip4 otherwise false
     *
     * @since 1.0.0
     */
    public static function isValidIpv4(mixed $value) : bool
    {
        return filter_var($value, \FILTER_VALIDATE_IP, \FILTER_FLAG_IPV4) !== false;
    }
}
