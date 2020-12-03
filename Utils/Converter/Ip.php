<?php
/**
 * Orange Management
 *
 * PHP Version 8.0
 *
 * @package   phpOMS\Utils\Converter
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Utils\Converter;

/**
 * Ip converter.
 *
 * @package phpOMS\Utils\Converter
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
final class Ip
{
    public const IP_TABLE_ITERATIONS = 100;

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
     * Convert ip to float
     *
     * @param string $ip IP
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function ip2Float(string $ip) : float
    {
        $split = \explode('.', $ip);

        return ((int) $split[0] ?? 0) * (256 ** 3)
            + ((int) $split[1] ?? 0) * (256 ** 2)
            + ((int) $split[2] ?? 0) * (256 ** 1)
            + ((int) $split[3] ?? 0);
    }
}
