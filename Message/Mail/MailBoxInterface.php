<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Message\Mail
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Message\Mail;

/**
 * Message interface.
 *
 * @package phpOMS\Message\Mail
 * @license OMS License 2.0
 * @link    https://jingga.app
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
     * Count unseen mail in mailbox
     *
     * @param string $box Box to count the mail in
     *
     * @return int
     *
     * @since 1.0.0
     */
    public function countUnseen(string $box) : int;

    /**
     * Get messages by search criterium
     *
     * @param string    $box     Box to count the mail in
     * @param string    $subject Subject
     * @param string    $body    Body
     * @param string    $to      To
     * @param string    $cc      CC
     * @param string    $from    From
     * @param string    $bcc     BCC
     * @param \DateTime $before  Message before
     * @param \DateTime $sicne   Message since
     * @param \DateTime $on      Message on date
     * @param bool      $deleted Message is deleted
     * @param bool      $flagged Message is flagged (false = any message)
     *
     * @return array
     *
     * @since 1.0.0
     */
    public function search(
        string $box,
        string $subject = '',
        string $body = '',
        string $to = '',
        string $cc = '',
        string $from = '',
        string $bcc = '',
        \DateTime $before = null,
        \DateTime $since = null,
        \DateTime $on = null,
        bool $deleted = false,
        bool $flagged = false
    ) : array;

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
