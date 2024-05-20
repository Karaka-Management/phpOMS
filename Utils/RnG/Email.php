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
 * Email generator.
 *
 * @package phpOMS\Utils\RnG
 * @license OMS License 2.2
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class Email
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
     * Get a random email.
     *
     * @return string
     *
     * @since 1.0.0
     */
    public static function generateEmail() : string
    {
        $count = \count(Text::LOREM_IPSUM) - 1;

        return Text::LOREM_IPSUM[\mt_rand(0, $count)]
            . '@' . Text::LOREM_IPSUM[\mt_rand(0, $count)]
            . '.com';
    }
}
