<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   phpOMS\Utils
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Utils;

/**
 * Image utils class.
 *
 * This class provides static helper functionalities for images.
 *
 * @package phpOMS\Utils
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
final class ImageUtils
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
     * Decode base64 image.
     *
     * @param string $img Encoded image
     *
     * @return string Decoded image
     *
     * @since 1.0.0
     */
    public static function decodeBase64Image(string $img) : string
    {
        $img = \str_replace('data:image/png;base64,', '', $img);
        $img = \str_replace(' ', '+', $img);

        return (string) \base64_decode($img);
    }
}
