<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   phpOMS\Contract
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Contract;

/**
 * Make a class streamable.
 *
 * @package phpOMS\Contract
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
interface StreamInterface
{
    /**
     * Convert the stream to a string if the stream is readable and the stream is seekable.
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function __toString();

    /**
     * Close the underlying stream
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function close() : void;

    /**
     * Get stream metadata
     *
     * @param string $key Specific metadata to retrieve
     *
     * @return array|mixed|null
     *
     * @since 1.0.0
     */
    public function getMetaData(string $key = null);

    /**
     * Get the stream resource
     *
     * @return resource
     *
     * @since 1.0.0
     */
    public function getStream();

    /**
     * Set the stream that is wrapped by the object
     *
     * @param resource $stream Stream resource to wrap
     * @param null|int $size   Size of the stream in bytes. Only pass if the size cannot be obtained from the stream.
     *
     * @return self
     *
     * @since 1.0.0
     */
    public function setStream($stream, int $size = null) : self;

    /**
     * Detach the current stream resource
     *
     * @return self
     *
     * @since 1.0.0
     */
    public function detachStream() : self;

    /**
     * Get the stream wrapper type
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function getWrapper() : string;

    /**
     * Wrapper specific data attached to this stream.
     *
     * @return array
     *
     * @since 1.0.0
     */
    public function getWrapperData() : array;

    /**
     * Get a label describing the underlying implementation of the stream
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function getStreamType() : string;

    /**
     * Get the URI/filename associated with this stream
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function getUri() : string;

    /**
     * Get the size of the stream if able
     *
     * @return int
     *
     * @since 1.0.0
     */
    public function getSize() : int;

    /**
     * Check if the stream is readable
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function isReadable() : bool;

    /**
     * Check if the stream is repeatable
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function isRepeatable() : bool;

    /**
     * Check if the stream is writable
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function isWritable() : bool;

    /**
     * Check if the stream has been consumed
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function isConsumed() : bool;

    /**
     * Alias of isConsumed
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function feof() : bool;

    /**
     * Check if the stream is a local stream vs a remote stream
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function isLocal() : bool;

    /**
     * Check if the string is repeatable
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function isSeekable() : bool;

    /**
     * Specify the size of the stream in bytes
     *
     * @param int $size Size of the stream contents in bytes
     *
     * @return self
     *
     * @since 1.0.0
     */
    public function setSize(int $size) : self;

    /**
     * Seek to a position in the stream
     *
     * @param int $offset Stream offset
     * @param int $whence Where the offset is applied
     *
     * @return bool Returns TRUE on success or FALSE on failure
     * @link   http://www.php.net/manual/en/function.fseek.php
     *
     * @since 1.0.0
     */
    public function seek(int $offset, int $whence = \SEEK_SET) : bool;

    /**
     * Read data from the stream
     *
     * @param int $length up to length number of bytes read
     *
     * @return string Returns the data read from the stream or FALSE on failure or EOF
     *
     * @since 1.0.0
     */
    public function read(int $length) : ?string;

    /**
     * Write data to the stream
     *
     * @param string $string the string that is to be written
     *
     * @return int returns the number of bytes written to the stream on success or FALSE on failure
     *
     * @since 1.0.0
     */
    public function write(string $string) : int;

    /**
     * Returns the current position of the file read/write pointer
     *
     * @return int Returns the position of the file pointer or false on error
     *
     * @since 1.0.0
     */
    public function ftell() : int;

    /**
     * Rewind to the beginning of the stream
     *
     * @return bool Returns true on success or false on failure
     *
     * @since 1.0.0
     */
    public function rewind() : bool;

    /**
     * Read a line from the stream up to the maximum allowed buffer length
     *
     * @param int $maxLength Maximum buffer length
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function readLine(int $maxLength = null) : ?string;

    /**
     * Set custom data on the stream
     *
     * @param string $key   Key to set
     * @param mixed  $value Value to set
     *
     * @return self
     *
     * @since 1.0.0
     */
    public function setCustomData(string $key, $value) : self;

    /**
     * Get custom data from the stream
     *
     * @param string $key Key to retrieve
     *
     * @return null|mixed
     *
     * @since 1.0.0
     */
    public function getCustomData(string $key);
}
