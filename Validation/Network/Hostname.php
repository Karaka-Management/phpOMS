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
 * Validate hostname.
 *
 * @package phpOMS\Validation\Network
 * @license OMS License 2.0
 * @link    https://jingga.app
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
     *
     * A IPv6 string MUST be in [...] to be successfully validated
     */
    public static function isValid(mixed $value, ?array $constraints = null) : bool
    {
        //return \filter_var(\gethostbyname($value), \FILTER_VALIDATE_IP) !== false;

        if (!\is_string($value)) {
            return false;
        }

        if (empty($value)
            || \strlen($value) > 256
            || !\preg_match('/^([a-zA-Z\d.-]*|\[[a-fA-F\d:]+\])$/', $value)
        ) {
            return false;
        } elseif (\strlen($value) > 2 && \substr($value, 0, 1) === '[' && \substr($value, -1, 1) === ']') {
            return \filter_var(\substr($value, 1, -1), \FILTER_VALIDATE_IP, \FILTER_FLAG_IPV6) !== false;
        } elseif (\is_numeric(\str_replace('.', '', $value))) {
            return \filter_var($value, \FILTER_VALIDATE_IP, \FILTER_FLAG_IPV4) !== false;
        } elseif (\filter_var('http://' . $value, \FILTER_VALIDATE_URL) !== false) {
            return true;
        }

        return false;
    }
}
