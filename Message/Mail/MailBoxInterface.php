<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Message\Mail
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://karaka.app
 */
declare(strict_types=1);

namespace phpOMS\Message\Mail;

/**
 * Message interface.
 *
 * @package phpOMS\Message\Mail
 * @license OMS License 1.0
 * @link    https://karaka.app
 * @since   1.0.0
 */
interface MailBoxInterface
{
    /**
     * Connect to server
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function connectInbox() : bool;

    /**
     * Close mailbox
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function closeInbox() : void;

    /**
     * Count mail in mailbox
     *
     * @param string $box Box to count the mail in
     *
     * @return int
     *
     * @since 1.0.0
     */
    public function countMail(string $box) : int;

    /**
     * Count recent mail in mailbox
     *
     * @param string $box Box to count the mail in
     *
     * @return int
     *
     * @since 1.0.0
     */
    public function countRecent(string $box) : int;

    /**
     * Get all message headers from a mailbox
     *
     * @param string $box Mailbox
     *
     * @return array
     *
     * @since 1.0.0
     */
    public function getHeaders(string $box) : array;

    /**
     * Get mailbox information summary
     *
     * @param string $box Box to check
     *
     * @return object
     *
     * @since 1.0.0
     */
    public function getMailboxInfo(string $box) : object;

    /**
     * Get message
     *
     * @param int $msg Message number (not uid)
     *
     * @return Email
     *
     * @since 1.0.0
     */
    public function getMail(int $msg) : Email;
}
