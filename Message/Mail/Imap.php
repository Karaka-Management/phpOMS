<?php
/**
 * Orange Management
 *
 * PHP Version 8.0
 *
 * @package   phpOMS\Message\Mail
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Message\Mail;

/**
 * Imap mail class.
 *
 * @package phpOMS\Message\Mail
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
class Imap extends MailHandler implements MailBoxInterface
{
    /**
     * Destructor.
     *
     * @since 1.0.0
     */
    public function __destruct()
    {
        $this->inboxClose();
        parent::__destruct();
    }

    /**
     * Connect to server
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function connectInbox() : bool
    {
        $this->mailbox = ($tmp = \imap_open(
            '{'
            . $this->host . ':' . $this->port . '/imap'
            . ($this->encryption !== EncryptionType::NONE ? '/ssl' : '')
            . '}',
            $this->username, $this->password
        ) === false) ? null : $tmp;
    }

    public function getBoxes() : array
    {
        $list = \imap_list($this->mailbox, '{' . $this->host . ':' . $this->port . '}');
        if (!\is_array($list)) {
            return [];
        }

        foreach ($list as $key => $value) {
            $list[$key] = \imap_utf7_decode($value);
        }

        return $list;
    }

    public function renameBox(string $old, string $new) : bool
    {
        return \imap_renamemailbox($this->mailbox, $old, \imap_utf7_encode($new));
    }

    public function deleteBox(string $box) : bool
    {
        return \imap_deletemailbox($this->mailbox, $box);
    }

    public function createBox(string $box) : bool
    {
        return \imap_createmailbox($this->mailbox, \imap_utf7_encode($box));
    }

    public function countMail(string $box) : int
    {
        if ($this->box !== $box) {
            \imap_reopen($this->box, $box);
            $this->box = $box;
        }

        return \imap_num_msg($this->mailbox);
    }

    public function getMailboxInfo(string $box) : object
    {
        if ($this->box !== $box) {
            \imap_reopen($this->box, $box);
            $this->box = $box;
        }

        return \imap_status($this->mailbox);
    }

    public function getRecentCount(string $box) : int
    {
        if ($this->box !== $box) {
            \imap_reopen($this->box, $box);
            $this->box = $box;
        }

        return \imap_num_recent($this->mailbox);
    }

    public function copyMail(string|array $messages, string $box) : bool
    {
        return \imap_mail_copy($this->mailbox, !\is_string($messages) ? \implode(',', $messages) : $messages, $box);
    }

    public function moveMail(string|array $messages, string $box) : bool
    {
        return \imap_mail_copy($this->mailbox, !\is_string($messages) ? \implode(',', $messages) : $messages, $box);
    }

    public function deleteMail(int $msg) : bool
    {
        return \imap_delete($this->mailbox, $msg);
    }

    public function getHeaders(string $box) : array
    {
        if ($this->box !== $box) {
            \imap_reopen($this->box, $box);
            $this->box = $box;
        }

        return \imap_headers($this->mailbox);
    }

    public function getHeaderInfo(int $msg) : object
    {
        return \imap_headerinfo($this->mailbox, $msg);
    }

    public function getMail(int $msg) : Email
    {
        return new Email();
    }

    /**
     * Close mailbox
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function inboxClose() : void
    {
        if ($this->mailbox !== null) {
            \imap_close($this->mailbox);
        }
    }
}
