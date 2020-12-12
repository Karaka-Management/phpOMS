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
 * Validate hostname.
 *
 * @package phpOMS\Validation\Network
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
abstract class Hostname extends ValidatorAbstract
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
        //return \filter_var(\gethostbyname($value), \FILTER_VALIDATE_IP) !== false;

        if (empty($value)
            || \strlen($value) > 256
            || !\preg_match('/^([a-zA-Z\d.-]*|\[[a-fA-F\d:]+])$/', $value)
        ) {
            return false;
        } elseif (\strlen($value) > 2 && \substr($value, 0, 1) === '[' && \substr($value, -1, 1) === ']') {
            return \filter_var(\substr($value, 1, -1), \FILTER_VALIDATE_IP, \FILTER_FLAG_IPV6) !== false;
        } elseif (\is_numeric(str_replace('.', '', $value))) {
            return \filter_var($value, \FILTER_VALIDATE_IP, \FILTER_FLAG_IPV4) !== false;
        } elseif (\filter_var('http://' . $value, FILTER_VALIDATE_URL) !== false) {
            return true;
        }

        return false;
    }
}
