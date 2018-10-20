<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    phpOMS\Utils\IO\Zip
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */
declare(strict_types=1);

namespace phpOMS\Utils\IO\Zip;

/**
 * Zip class for handling zip files.
 *
 * Providing basic zip support
 *
 * @package    phpOMS\Utils\IO\Zip
 * @license    OMS License 1.0
 * @link       http://website.orange-management.de
 * @since      1.0.0
 */
class Gz implements ArchiveInterface
{
    /**
     * {@inheritdoc}
     */
    public static function pack($source, string $destination, bool $overwrite = true) : bool
    {
        $destination = \str_replace('\\', '/', $destination);
        if (!$overwrite && \file_exists($destination)) {
            return false;
        }

        $gz  = \gzopen($destination, 'w');
        $src = \fopen($source, 'r');
        if ($gz === false || $src === false) {
            return false;
        }

        while (!\feof($src)) {
            $read = \fread($src, 4096);
            \gzwrite($gz, $read === false ? '' : $read);
        }

        \fclose($src);

        return \gzclose($gz);
    }

    /**
     * {@inheritdoc}
     */
    public static function unpack(string $source, string $destination) : bool
    {
        $destination = \str_replace('\\', '/', $destination);
        if (\file_exists($destination)) {
            return false;
        }

        $gz   = \gzopen($source, 'r');
        $dest = \fopen($destination, 'w');
        if ($gz === false || $dest === false) {
            return false;
        }

        while (!\gzeof($gz)) {
            \fwrite($dest, \gzread($gz, 4096));
        }

        \fclose($dest);

        return \gzclose($gz);
    }
}
