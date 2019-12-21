<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   phpOMS\DataStorage\Session
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\DataStorage\Session;

/**
 * Console session handler.
 *
 * @package phpOMS\DataStorage\Session
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 *
 * @SuppressWarnings(PHPMD.Superglobals)
 */
final class ConsoleSessionHandler implements \SessionHandlerInterface, \SessionIdInterface
{
    /**
     * File path for session
     *
     * @var   string
     * @since 1.0.0
     */
    private string $savePath;

    /**
     * Constructor
     *
     * @param string $path Path of the session data
     *
     * @since 1.0.0
     */
    public function __construct(string $path)
    {
        $this->savePath = $path;
    }

    /**
     * Create a session id string
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function create_sid() : string
    {
        return \session_create_id('console-');
    }

    /**
     * Open the session storage
     *
     * @param string $savePath    Path of the session data
     * @param string $sessionName Name of the session
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function open($savePath, $sessionName)
    {
        $this->savePath = $savePath;
        if (!\is_dir($this->savePath)) {
            \mkdir($this->savePath, 0777);
        }

        return true;
    }

    /**
     * Close the session
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function close() : bool
    {
        return true;
    }

    /**
     * Read the session data
     *
     * @param string $id Session id
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function read($id)
    {
        return (string) @\file_get_contents($this->savePath . '/sess_' . $id);
    }

    /**
     * Write session data
     *
     * @param string $id   Session id
     * @param string $data Session data
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function write($id, $data)
    {
        return \file_put_contents($this->savePath . '/sess_' . $id, $data) === false ? false : true;
    }

    /**
     * Destroy the session
     *
     * @param string $id Session id
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function destroy($id)
    {
        $file = $this->savePath . '/sess_' . $id;
        if (\file_exists($file)) {
            unlink($file);
        }

        return true;
    }

    /**
     * Garbage collect session data
     *
     * @param int $maxlifetime Maximum session data life time
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function gc($maxlifetime)
    {
        $files = \glob("$this->savePath/sess_*");

        if ($files === false) {
            return false;
        }

        foreach ($files as $file) {
            if (\filemtime($file) + $maxlifetime < \time() && \file_exists($file)) {
                unlink($file);
            }
        }

        return true;
    }
}
