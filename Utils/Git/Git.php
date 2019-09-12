<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   phpOMS\Utils\Git
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Utils\Git;

use phpOMS\System\File\PathException;

/**
 * Gray encoding class
 *
 * @package phpOMS\Utils\Git
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 * @codeCoverageIgnore
 */
class Git
{
    /**
     * Git path.
     *
     * @var   string
     * @since 1.0.0
     */
    protected static string $bin = '/usr/bin/git';

    /**
     * Test git.
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public static function test() : bool
    {
        $pipes    = [];
        $resource = \proc_open(\escapeshellarg(self::getBin()), [1 => ['pipe', 'w'], 2 => ['pipe', 'w']], $pipes);

        $stdout = \stream_get_contents($pipes[1]);
        $stderr = \stream_get_contents($pipes[2]);

        foreach ($pipes as $pipe) {
            \fclose($pipe);
        }

        return $resource !== false && \proc_close($resource) !== 127;
    }

    /**
     * Get git binary.
     *
     * @return string
     *
     * @since 1.0.0
     */
    public static function getBin() : string
    {
        return self::$bin;
    }

    /**
     * Set git binary.
     *
     * @param string $path Git path
     *
     * @return void
     *
     * @throws PathException This exception is thrown if the binary path doesn't exist
     *
     * @since 1.0.0
     */
    public static function setBin(string $path) : void
    {
        if (\realpath($path) === false) {
            throw new PathException($path);
        }

        self::$bin = \realpath($path);
    }
}
