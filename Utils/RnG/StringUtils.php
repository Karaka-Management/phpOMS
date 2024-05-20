<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   phpOMS\Utils\RnG
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.2
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Utils\RnG;

/**
 * String generator.
 *
 * @package phpOMS\Utils\RnG
 * @license OMS License 2.2
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class StringUtils
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
     * Get a random string.
     *
     * @param int    $min     Min. length
     * @param int    $max     Max. length
     * @param string $charset Allowed characters
     *
     * @return string
     *
     * @since 1.0.0
     */
    public static function generateString(int $min = 10, int $max = 10,
        string $charset = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'
    ) : string
    {
        $length           = \mt_rand($min, $max);
        $charactersLength = \strlen($charset);
        $randomString     = '';

        for ($i = 0; $i < $length; ++$i) {
            $randomString .= $charset[\mt_rand(0, $charactersLength - 1)];
        }

        return $randomString;
    }
}
