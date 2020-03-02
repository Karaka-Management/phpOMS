<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   phpOMS\Message
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Message;

use phpOMS\Contract\StreamInterface;

/**
 * Upload interface.
 *
 * @package phpOMS\Message
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
interface UploadedFileInterface
{

    /**
     * Retrieve a stream representing the uploaded file.
     *
     * @since 1.0.0
     */
    public function getStream() : StreamInterface;

    /**
     * Move the uploaded file to a new location.
     *
     * @param string $targetPath Path to new location
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function moveTo(string $targetPath) : void;

    /**
     * Retrieve the file size.
     *
     * @return int
     *
     * @since 1.0.0
     */
    public function getSize() : int;

    /**
     * Retrieve the error associated with the uploaded file.
     *
     * @return int
     *
     * @since 1.0.0
     */
    public function getError() : int;

    /**
     * Retrieve the filename sent by the client.
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function getClientFilename() : string;

    /**
     * Retrieve the media type sent by the client.
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function getClientMediaType() : string;
}
