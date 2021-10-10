<?php
/**
 * Orange Management
 *
 * PHP Version 8.0
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\tests\Message;

require_once __DIR__ . '/../../Autoloader.php';

use phpOMS\Message\Mail\Email;
use phpOMS\Message\Mail\Imap;
use phpOMS\Message\Mail\MailHandler;
use phpOMS\Message\Mail\SubmitType;
use phpOMS\System\CharsetType;

/**
 * @testdox phpOMS\tests\Message\MailHandlerTest: Abstract mail handler
 *
 * @internal
 */
class MailHandlerTest extends \PHPUnit\Framework\TestCase
{
    protected MailHandler $handler;

    /**
     * {@inheritdoc}
     */
    protected function setUp() : void
    {
        $this->handler = new MailHandler();
    }

    public function testSendTextWithMail() : void
    {
        $this->handler->setMailer(SubmitType::MAIL);

        if ($this->handler->mailerTool !== '' && !\file_exists(\explode(' ', $this->handler->mailerTool)[0])) {
            self::markTestSkipped();
        }

        $mail = new Email();
        $mail->setFrom('test1@orange-management.email', 'Dennis Eichhorn');
        $mail->addTo('test@orange-management.email', 'Dennis Eichhorn');
        $mail->addCC('test2@orange-management.email', 'Dennis Eichhorn');
        $mail->addBCC('test3@orange-management.email', 'Dennis Eichhorn');
        $mail->addReplyTo('test4@orange-management.email', 'Dennis Eichhorn');
        $mail->subject = 'testSendTextWithMail';
        $mail->body    = "This is some content\n\Image: <img alt=\"image\" src=\"cid:cid1\">";
        $mail->altBody = 'Alt body';
        $mail->addAttachment(__DIR__ . '/files/logo.png', 'logo');
        $mail->addEmbeddedImage(__DIR__ . '/files/logo.png', 'cid1');
        $mail->addStringAttachment('String content', 'string_content_file.txt');
        $mail->addStringEmbeddedImage(\file_get_contents(__DIR__ . '/files/logo.png'), 'cid2');

        self::assertTrue($this->handler->send($mail));
    }

    public function testSendTextWithSendmail() : void
    {
        $this->handler->setMailer(SubmitType::SENDMAIL);

        if ($this->handler->mailerTool !== '' && !\file_exists(\explode(' ', $this->handler->mailerTool)[0])) {
            self::markTestSkipped();
        }

        $mail = new Email();
        $mail->setFrom('test1@orange-management.email', 'Dennis Eichhorn');
        $mail->addTo('test@orange-management.email', 'Dennis Eichhorn');
        $mail->addCC('test2@orange-management.email', 'Dennis Eichhorn');
        $mail->addBCC('test3@orange-management.email', 'Dennis Eichhorn');
        $mail->addReplyTo('test4@orange-management.email', 'Dennis Eichhorn');
        $mail->subject = 'testSendTextWithSendmail';
        $mail->body    = "This is some content\n\Image: <img alt=\"image\" src=\"cid:cid1\">";
        $mail->altBody = 'Alt body';
        $mail->addAttachment(__DIR__ . '/files/logo.png', 'logo');
        $mail->addEmbeddedImage(__DIR__ . '/files/logo.png', 'cid1');
        $mail->addStringAttachment('String content', 'string_content_file.txt');
        $mail->addStringEmbeddedImage(\file_get_contents(__DIR__ . '/files/logo.png'), 'cid2');

        self::assertTrue($this->handler->send($mail));
    }

    public function testSendHtmlWithMail() : void
    {
        $this->handler->setMailer(SubmitType::MAIL);

        if ($this->handler->mailerTool !== '' && !\file_exists(\explode(' ', $this->handler->mailerTool)[0])) {
            self::markTestSkipped();
        }

        $mail = new Email();
        $mail->setFrom('test1@orange-management.email', 'Dennis Eichhorn');
        $mail->addTo('test@orange-management.email', 'Dennis Eichhorn');
        $mail->addCC('test2@orange-management.email', 'Dennis Eichhorn');
        $mail->addBCC('test3@orange-management.email', 'Dennis Eichhorn');
        $mail->addReplyTo('test4@orange-management.email', 'Dennis Eichhorn');
        $mail->subject = 'testSendHtmlWithMail';
        $message       = \file_get_contents(__DIR__ . '/files/utf8.html');
        $mail->charset = CharsetType::UTF_8;
        $mail->body    = '';
        $mail->bodyAlt = '';

        $mail->setHtml(true);
        $mail->msgHTML($message, __DIR__ . '/files');
        $mail->addAttachment(__DIR__ . '/files/logo.png', 'logo');
        $mail->addEmbeddedImage(__DIR__ . '/files/logo.png', 'cid1');
        $mail->addStringAttachment('String content', 'string_content_file.txt');
        $mail->addStringEmbeddedImage(\file_get_contents(__DIR__ . '/files/logo.png'), 'cid2');

        self::assertTrue($this->handler->send($mail));
    }

    public function testSendHtmlWithSendmail() : void
    {
        $this->handler->setMailer(SubmitType::SENDMAIL);

        if ($this->handler->mailerTool !== '' && !\file_exists(\explode(' ', $this->handler->mailerTool)[0])) {
            self::markTestSkipped();
        }

        $mail = new Email();
        $mail->setFrom('test1@orange-management.email', 'Dennis Eichhorn');
        $mail->addTo('test@orange-management.email', 'Dennis Eichhorn');
        $mail->addCC('test2@orange-management.email', 'Dennis Eichhorn');
        $mail->addBCC('test3@orange-management.email', 'Dennis Eichhorn');
        $mail->addReplyTo('test4@orange-management.email', 'Dennis Eichhorn');
        $mail->subject = 'testSendHtmlWithSendmail';
        $message       = \file_get_contents(__DIR__ . '/files/utf8.html');
        $mail->charset = CharsetType::UTF_8;
        $mail->body    = '';
        $mail->bodyAlt = '';

        $mail->setHtml(true);
        $mail->msgHTML($message, __DIR__ . '/files');
        $mail->addAttachment(__DIR__ . '/files/logo.png', 'logo');
        $mail->addEmbeddedImage(__DIR__ . '/files/logo.png', 'cid1');
        $mail->addStringAttachment('String content', 'string_content_file.txt');
        $mail->addStringEmbeddedImage(\file_get_contents(__DIR__ . '/files/logo.png'), 'cid2');

        self::assertTrue($this->handler->send($mail));
    }

    public function testSendInlineWithMail() : void
    {
        $this->handler->setMailer(SubmitType::MAIL);

        if ($this->handler->mailerTool !== '' && !\file_exists(\explode(' ', $this->handler->mailerTool)[0])) {
            self::markTestSkipped();
        }

        $mail = new Email();
        $mail->setFrom('test1@orange-management.email', 'Dennis Eichhorn');
        $mail->addTo('test@orange-management.email', 'Dennis Eichhorn');
        $mail->subject = 'testSendInlineWithMail';
        $mail->setHtml(true);
        $mail->msgHTML("<img alt=\"image\" src=\"cid:cid1\">");
        $mail->addEmbeddedImage(__DIR__ . '/files/logo.png', 'cid1');

        self::assertTrue($this->handler->send($mail));
    }

    public function testSendInlineWithSendmail() : void
    {
        $this->handler->setMailer(SubmitType::SENDMAIL);

        if ($this->handler->mailerTool !== '' && !\file_exists(\explode(' ', $this->handler->mailerTool)[0])) {
            self::markTestSkipped();
        }

        $mail = new Email();
        $mail->setFrom('test1@orange-management.email', 'Dennis Eichhorn');
        $mail->addTo('test@orange-management.email', 'Dennis Eichhorn');
        $mail->subject = 'testSendInlineWithSendmail';
        $mail->setHtml(true);
        $mail->msgHTML("<img alt=\"image\" src=\"cid:cid1\">");
        $mail->addEmbeddedImage(__DIR__ . '/files/logo.png', 'cid1');

        self::assertTrue($this->handler->send($mail));
    }

    public function testSendAttachmentWithMail() : void
    {
        $this->handler->setMailer(SubmitType::MAIL);

        if ($this->handler->mailerTool !== '' && !\file_exists(\explode(' ', $this->handler->mailerTool)[0])) {
            self::markTestSkipped();
        }

        $mail = new Email();
        $mail->setFrom('test1@orange-management.email', 'Dennis Eichhorn');
        $mail->addTo('test@orange-management.email', 'Dennis Eichhorn');
        $mail->subject = 'testSendAttachmentWithMail';
        $mail->addAttachment(__DIR__ . '/files/logo.png', 'logo');

        self::assertTrue($this->handler->send($mail));
    }

    public function testSendAttachmentWithSendmail() : void
    {
        $this->handler->setMailer(SubmitType::SENDMAIL);

        if ($this->handler->mailerTool !== '' && !\file_exists(\explode(' ', $this->handler->mailerTool)[0])) {
            self::markTestSkipped();
        }

        $mail = new Email();
        $mail->setFrom('test1@orange-management.email', 'Dennis Eichhorn');
        $mail->addTo('test@orange-management.email', 'Dennis Eichhorn');
        $mail->subject = 'testSendAttachmentWithSendmail';
        $mail->addAttachment(__DIR__ . '/files/logo.png', 'logo');

        self::assertTrue($this->handler->send($mail));
    }

    public function testSendAltWithMail() : void
    {
        $this->handler->setMailer(SubmitType::MAIL);

        if ($this->handler->mailerTool !== '' && !\file_exists(\explode(' ', $this->handler->mailerTool)[0])) {
            self::markTestSkipped();
        }

        $mail = new Email();
        $mail->setFrom('test1@orange-management.email', 'Dennis Eichhorn');
        $mail->addTo('test@orange-management.email', 'Dennis Eichhorn');
        $mail->subject = 'testSendAltWithMail';
        $mail->altBody = 'Alt body';

        self::assertTrue($this->handler->send($mail));
    }

    public function testSendAltWithSendmail() : void
    {
        $this->handler->setMailer(SubmitType::SENDMAIL);

        if ($this->handler->mailerTool !== '' && !\file_exists(\explode(' ', $this->handler->mailerTool)[0])) {
            self::markTestSkipped();
        }

        $mail = new Email();
        $mail->setFrom('test1@orange-management.email', 'Dennis Eichhorn');
        $mail->addTo('test@orange-management.email', 'Dennis Eichhorn');
        $mail->subject = 'testSendAltWithSendmail';
        $mail->altBody = 'Alt body';

        self::assertTrue($this->handler->send($mail));
    }

    public function testSendAltInlineWithMail() : void
    {
        $this->handler->setMailer(SubmitType::MAIL);

        if ($this->handler->mailerTool !== '' && !\file_exists(\explode(' ', $this->handler->mailerTool)[0])) {
            self::markTestSkipped();
        }

        $mail = new Email();
        $mail->setFrom('test1@orange-management.email', 'Dennis Eichhorn');
        $mail->addTo('test@orange-management.email', 'Dennis Eichhorn');
        $mail->subject = 'testSendAltInlineWithMail';
        $mail->altBody = 'Alt body';
        $mail->setHtml(true);
        $mail->msgHTML("<img alt=\"image\" src=\"cid:cid1\">");
        $mail->addEmbeddedImage(__DIR__ . '/files/logo.png', 'cid1');

        self::assertTrue($this->handler->send($mail));
    }

    public function testSendAltInlineWithSendmail() : void
    {
        $this->handler->setMailer(SubmitType::SENDMAIL);

        if ($this->handler->mailerTool !== '' && !\file_exists(\explode(' ', $this->handler->mailerTool)[0])) {
            self::markTestSkipped();
        }

        $mail = new Email();
        $mail->setFrom('test1@orange-management.email', 'Dennis Eichhorn');
        $mail->addTo('test@orange-management.email', 'Dennis Eichhorn');
        $mail->subject = 'testSendAltInlineWithSendmail';
        $mail->altBody = 'Alt body';
        $mail->setHtml(true);
        $mail->msgHTML("<img alt=\"image\" src=\"cid:cid1\">");
        $mail->addEmbeddedImage(__DIR__ . '/files/logo.png', 'cid1');

        self::assertTrue($this->handler->send($mail));
    }

    public function testSendAltAttachmentWithMail() : void
    {
        $this->handler->setMailer(SubmitType::MAIL);

        if ($this->handler->mailerTool !== '' && !\file_exists(\explode(' ', $this->handler->mailerTool)[0])) {
            self::markTestSkipped();
        }

        $mail = new Email();
        $mail->setFrom('test1@orange-management.email', 'Dennis Eichhorn');
        $mail->addTo('test@orange-management.email', 'Dennis Eichhorn');
        $mail->subject = 'testSendAltAttachmentWithMail';
        $mail->altBody = 'Alt body';
        $mail->addAttachment(__DIR__ . '/files/logo.png', 'logo');

        self::assertTrue($this->handler->send($mail));
    }

    public function testSendAltAttachmentWithSendmail() : void
    {
        $this->handler->setMailer(SubmitType::SENDMAIL);

        if ($this->handler->mailerTool !== '' && !\file_exists(\explode(' ', $this->handler->mailerTool)[0])) {
            self::markTestSkipped();
        }

        $mail = new Email();
        $mail->setFrom('test1@orange-management.email', 'Dennis Eichhorn');
        $mail->addTo('test@orange-management.email', 'Dennis Eichhorn');
        $mail->subject = 'testSendAltAttachmentWithSendmail';
        $mail->altBody = 'Alt body';
        $mail->addAttachment(__DIR__ . '/files/logo.png', 'logo');

        self::assertTrue($this->handler->send($mail));
    }

    public function testSendPlainWithMail() : void
    {
        $this->handler->setMailer(SubmitType::MAIL);

        if ($this->handler->mailerTool !== '' && !\file_exists(\explode(' ', $this->handler->mailerTool)[0])) {
            self::markTestSkipped();
        }

        $mail = new Email();
        $mail->setFrom('test1@orange-management.email', 'Dennis Eichhorn');
        $mail->addTo('test@orange-management.email', 'Dennis Eichhorn');
        $mail->subject = 'testSendPlainWithMail';
        $mail->body = 'Body';

        self::assertTrue($this->handler->send($mail));
    }

    public function testSendPlainWithSendmail() : void
    {
        $this->handler->setMailer(SubmitType::SENDMAIL);

        if ($this->handler->mailerTool !== '' && !\file_exists(\explode(' ', $this->handler->mailerTool)[0])) {
            self::markTestSkipped();
        }

        $mail = new Email();
        $mail->setFrom('test1@orange-management.email', 'Dennis Eichhorn');
        $mail->addTo('test@orange-management.email', 'Dennis Eichhorn');
        $mail->subject = 'testSendPlainWithSendmail';
        $mail->body = 'Body';

        self::assertTrue($this->handler->send($mail));
    }

    public function testReceiveMailWithImap() : void
    {/*
        $this->handler = new Imap();
        $this->handler->connectInbox();

        var_dump($this->handler->getBoxes());*/
    }
}
