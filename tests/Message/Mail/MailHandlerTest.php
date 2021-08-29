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
        if (!\file_exists('/usr/sbin/sendmail') && empty(\ini_get('sendmail_path'))) {
            self::markTestSkipped();
        }

        $this->handler->setMailer(SubmitType::MAIL);

        $mail = new Email();
        $mail->setFrom('test1@orange-management.email', 'Dennis Eichhorn');
        $mail->addTo('test@orange-management.email', 'Dennis Eichhorn');
        $mail->addCC('test2@orange-management.email', 'Dennis Eichhorn');
        $mail->addBCC('test3@orange-management.email', 'Dennis Eichhorn');
        $mail->addReplyTo('test4@orange-management.email', 'Dennis Eichhorn');
        $mail->subject = 'testSendTextWithMail';
        $mail->body    = 'This is some content';
        $mail->addAttachment(__DIR__ . '/files/logo.png', 'logo');

        self::assertTrue($this->handler->send($mail));
    }

    public function testSendTextWithSendmail() : void
    {
        if (!\file_exists('/usr/sbin/sendmail') && empty(\ini_get('sendmail_path'))) {
            self::markTestSkipped();
        }

        $this->handler->setMailer(SubmitType::SENDMAIL);

        $mail = new Email();
        $mail->setFrom('test1@orange-management.email', 'Dennis Eichhorn');
        $mail->addTo('test@orange-management.email', 'Dennis Eichhorn');
        $mail->addCC('test2@orange-management.email', 'Dennis Eichhorn');
        $mail->addBCC('test3@orange-management.email', 'Dennis Eichhorn');
        $mail->addReplyTo('test4@orange-management.email', 'Dennis Eichhorn');
        $mail->subject = 'testSendTextWithSendmail';
        $mail->body    = 'This is some content';
        $mail->addAttachment(__DIR__ . '/files/logo.png', 'logo');

        self::assertTrue($this->handler->send($mail));
    }

    public function testSendHtmlWithMail() : void
    {
        if (!\file_exists('/usr/sbin/sendmail') && empty(\ini_get('sendmail_path'))) {
            self::markTestSkipped();
        }

        $this->handler->setMailer(SubmitType::MAIL);

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

        $mail->msgHTML($message, __DIR__ . '/files');
        $mail->addAttachment(__DIR__ . '/files/logo.png', 'logo');

        self::assertTrue($this->handler->send($mail));
    }

    public function testSendHtmlWithSendmail() : void
    {
        if (!\file_exists('/usr/sbin/sendmail') && empty(\ini_get('sendmail_path'))) {
            self::markTestSkipped();
        }

        $this->handler->setMailer(SubmitType::SENDMAIL);

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

        $mail->msgHTML($message, __DIR__ . '/files');
        $mail->addAttachment(__DIR__ . '/files/logo.png', 'logo');

        self::assertTrue($this->handler->send($mail));
    }

    public function testReceiveMailWithImap() : void
    {/*
        $this->handler = new Imap();
        $this->handler->connectInbox();

        var_dump($this->handler->getBoxes());*/
    }
}
