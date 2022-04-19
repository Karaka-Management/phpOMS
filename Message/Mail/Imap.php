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
 * Imap mail class.
 *
 * @package phpOMS\Message\Mail
 * @license OMS License 1.0
 * @link    https://karaka.app
 * @since   1.0.0
 */
class Imap implements MailBoxInterface
{
    /**
     * Connection flags
     *
     * @var string
     * @since 1.0.0
     */
    public string $flags = '/imap';

    /**
     * Current inbox
     *
     * Boxes can be in parent boxes. The path must be delimitted with . e.g. INBOX.Subdir1.Subdir2
     *
     * @var string
     * @since 1.0.0
     */
    private $box = null;

    /**
     * Host.
     *
     * @var string
     * @since 1.0.0
     */
    public string $host = 'localhost';

    /**
     * The default port.
     *
     * @var int
     * @since 1.0.0
     */
    public int $port = 110;

    /**
     * Encryption
     *
     * @var string
     * @since 1.0.0
     */
    public string $encryption = EncryptionType::NONE;

    /**
     * Username.
     *
     * @var string
     * @since 1.0.0
     */
    public string $username = '';

    /**
     * Password.
     *
     * @var string
     * @since 1.0.0
     */
    public string $password = '';

    /**
     * {@inheritdoc}
     */
    public function __construct(string $user = '', string $pass = '', int $port = 143, string $encryption = EncryptionType::NONE)
    {
        $this->username   = $user;
        $this->password   = $pass;
        $this->port       = $port;
        $this->encryption = $encryption;
        $this->flags .= $this->encryption !== EncryptionType::NONE ? '/ssl' : '';
    }

    /**
     * Destructor.
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    public function __destruct()
    {
        $this->closeInbox();
    }

    /**
     * {@inheritdoc}
     */
    public function connectInbox() : bool
    {
        $this->mailbox = ($tmp = \imap_open(
            '{' . $this->host . ':' . $this->port . $this->flags . '}',
            $this->username, $this->password
        )) === false ? null : $tmp;

        return \is_resource($this->mailbox);
    }

    /**
     * Get mailboxes
     *
     * @return array
     *
     * @since 1.0.0
     */
    public function getBoxes() : array
    {
        $list = \imap_list($this->mailbox, $reference = '{' . $this->host . ':' . $this->port . '}', '*');
        if (!\is_array($list)) {
            return []; // @codeCoverageIgnore
        }

        foreach ($list as $key => $value) {
            $list[$key] = \str_replace($reference, '', \imap_utf7_decode($value));
        }

        return $list;
    }

    /**
     * Rename mailbox
     *
     * @param string $old Old name
     * @param string $new New name
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function renameBox(string $old, string $new) : bool
    {
        return \imap_renamemailbox(
            $this->mailbox,
            \imap_utf7_encode('{' . $this->host . ':' . $this->port . '}' . $old),
            \imap_utf7_encode('{' . $this->host . ':' . $this->port . '}' . $new)
        );
    }

    /**
     * Delete mailbox
     *
     * @param string $box Box to delete
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function deleteBox(string $box) : bool
    {
        return \imap_deletemailbox(
            $this->mailbox,
            \imap_utf7_encode('{' . $this->host . ':' . $this->port . '}' . $box)
        );
    }

    /**
     * Create mailbox
     *
     * @param string $box Box to create
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function createBox(string $box) : bool
    {
        return \imap_createmailbox(
            $this->mailbox,
            \imap_utf7_encode('{' . $this->host . ':' . $this->port . '}' . $box)
        );
    }

    /**
     * {@inheritdoc}
     */
    public function countMail(string $box) : int
    {
        if ($this->box !== $box) {
            \imap_reopen($this->mailbox, '{' . $this->host . ':' . $this->port . $this->flags . '}' . $box);
            $this->box = $box;
        }

        return \imap_num_msg($this->mailbox);
    }

    /**
     * {@inheritdoc}
     */
    public function getMailboxInfo(string $box) : object
    {
        return \imap_status($this->mailbox, '{' . $this->host . ':' . $this->port . '}' . $box,  \SA_ALL);
    }

    /**
     * {@inheritdoc}
     */
    public function countRecent(string $box) : int
    {
        if ($this->box !== $box) {
            \imap_reopen($this->mailbox, '{' . $this->host . ':' . $this->port . $this->flags . '}' . $box);
            $this->box = $box;
        }

        return \imap_num_recent($this->mailbox);
    }

    /**
     * Copy message to another mailbox
     *
     * @param string|array $messages Messages to copy
     * @param string       $box      Box to copy messages to
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function copyMail(string | array $messages, string $box) : bool
    {
        return \imap_mail_copy($this->mailbox, !\is_string($messages) ? \implode(',', $messages) : $messages, '{' . $this->host . ':' . $this->port . '}' . $box);
    }

    /**
     * Move message to another mailbox
     *
     * @param string|array $messages Messages to copy
     * @param string       $box      Box to copy messages to
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function moveMail(string | array $messages, string $box) : bool
    {
        return \imap_mail_copy($this->mailbox, !\is_string($messages) ? \implode(',', $messages) : $messages, '{' . $this->host . ':' . $this->port . '}' . $box);
    }

    /**
     * Delete message
     *
     * @param int $msg Message number (not uid)
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function deleteMail(int $msg) : bool
    {
        return \imap_delete($this->mailbox, $msg);
    }

    /**
     * {@inheritdoc}
     */
    public function getHeaders(string $box) : array
    {
        if ($this->box !== $box) {
            \imap_reopen($this->mailbox, '{' . $this->host . ':' . $this->port . $this->flags . '}' . $box);
            $this->box = $box;
        }

        return \imap_headers($this->mailbox);
    }

    /**
     * Get message header information
     *
     * @param int $msg Message number (not uid)
     *
     * @return object
     *
     * @since 1.0.0
     */
    public function getHeaderInfo(int $msg) : object
    {
        return \imap_headerinfo($this->mailbox, $msg);
    }

    /**
     * {@inheritdoc}
     */
    public function getMail(int $msg) : Email
    {
        return new Email();
    }

    /**
     * {@inheritdoc}
     */
    public function closeInbox() : void
    {
        if (\is_resource($this->mailbox)) {
            \imap_close($this->mailbox);
        }
    }
}
