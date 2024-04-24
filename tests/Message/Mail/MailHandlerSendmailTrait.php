<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\tests\Message\Mail;

require_once __DIR__ . '/../../Autoloader.php';

use phpOMS\Message\Mail\Email;
use phpOMS\Message\Mail\SubmitType;
use phpOMS\System\CharsetType;

trait MailHandlerSendmailTrait
{
    public function testSendTextWithSendmail() : void
    {
        $this->handler->setMailer(SubmitType::SENDMAIL);

        if ($this->handler->mailerTool !== '' && !\file_exists(\explode(' ', $this->handler->mailerTool)[0])) {
            self::markTestSkipped();
        }

        $mail                      = new Email();
        $mail->priority            = 1;
        $mail->confirmationAddress = 'test1@jingga.app';
        $mail->setFrom('test1@jingga.app', 'Dennis Eichhorn');
        $mail->addTo('test@jingga.app', 'Dennis Eichhorn');
        $mail->addCC('test2@jingga.app', 'Dennis Eichhorn');
        $mail->addBCC('test3@jingga.app', 'Dennis Eichhorn');
        $mail->addReplyTo('test4@jingga.app', 'Dennis Eichhorn');
        $mail->subject = 'testSendTextWithSendmail';
        $mail->body    = "This is some content\n\Image: <img alt=\"image\" src=\"cid:cid1\">";
        $mail->bodyAlt = 'Alt body';
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

        $mail                      = new Email();
        $mail->priority            = 1;
        $mail->confirmationAddress = 'test1@jingga.app';
        $mail->setFrom('test1@jingga.app', 'Dennis Eichhorn');
        $mail->addTo('test@jingga.app', 'Dennis Eichhorn');
        $mail->addCC('test2@jingga.app', 'Dennis Eichhorn');
        $mail->addBCC('test3@jingga.app', 'Dennis Eichhorn');
        $mail->addReplyTo('test4@jingga.app', 'Dennis Eichhorn');
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

    public function testSendInlineWithSendmail() : void
    {
        $this->handler->setMailer(SubmitType::SENDMAIL);

        if ($this->handler->mailerTool !== '' && !\file_exists(\explode(' ', $this->handler->mailerTool)[0])) {
            self::markTestSkipped();
        }

        $mail                      = new Email();
        $mail->priority            = 1;
        $mail->confirmationAddress = 'test1@jingga.app';
        $mail->setFrom('test1@jingga.app', 'Dennis Eichhorn');
        $mail->addTo('test@jingga.app', 'Dennis Eichhorn');
        $mail->subject = 'testSendInlineWithSendmail';
        $mail->setHtml(true);
        $mail->msgHTML("<img alt=\"image\" src=\"cid:cid1\">");
        $mail->addEmbeddedImage(__DIR__ . '/files/logo.png', 'cid1');

        self::assertTrue($this->handler->send($mail));
    }

    public function testSendAttachmentWithSendmail() : void
    {
        $this->handler->setMailer(SubmitType::SENDMAIL);

        if ($this->handler->mailerTool !== '' && !\file_exists(\explode(' ', $this->handler->mailerTool)[0])) {
            self::markTestSkipped();
        }

        $mail                      = new Email();
        $mail->priority            = 1;
        $mail->confirmationAddress = 'test1@jingga.app';
        $mail->setFrom('test1@jingga.app', 'Dennis Eichhorn');
        $mail->addTo('test@jingga.app', 'Dennis Eichhorn');
        $mail->subject = 'testSendAttachmentWithSendmail';
        $mail->addAttachment(__DIR__ . '/files/logo.png', 'logo');

        self::assertTrue($this->handler->send($mail));
    }

    public function testSendAltWithSendmail() : void
    {
        $this->handler->setMailer(SubmitType::SENDMAIL);

        if ($this->handler->mailerTool !== '' && !\file_exists(\explode(' ', $this->handler->mailerTool)[0])) {
            self::markTestSkipped();
        }

        $mail                      = new Email();
        $mail->priority            = 1;
        $mail->confirmationAddress = 'test1@jingga.app';
        $mail->setFrom('test1@jingga.app', 'Dennis Eichhorn');
        $mail->addTo('test@jingga.app', 'Dennis Eichhorn');
        $mail->subject = 'testSendAltWithSendmail';
        $mail->bodyAlt = 'Alt body';

        self::assertTrue($this->handler->send($mail));
    }

    public function testSendAltInlineWithSendmail() : void
    {
        $this->handler->setMailer(SubmitType::SENDMAIL);

        if ($this->handler->mailerTool !== '' && !\file_exists(\explode(' ', $this->handler->mailerTool)[0])) {
            self::markTestSkipped();
        }

        $mail                      = new Email();
        $mail->priority            = 1;
        $mail->confirmationAddress = 'test1@jingga.app';
        $mail->setFrom('test1@jingga.app', 'Dennis Eichhorn');
        $mail->addTo('test@jingga.app', 'Dennis Eichhorn');
        $mail->subject = 'testSendAltInlineWithSendmail';
        $mail->bodyAlt = 'Alt body';
        $mail->setHtml(true);
        $mail->msgHTML("<img alt=\"image\" src=\"cid:cid1\">");
        $mail->addEmbeddedImage(__DIR__ . '/files/logo.png', 'cid1');

        self::assertTrue($this->handler->send($mail));
    }

    public function testSendAltAttachmentWithSendmail() : void
    {
        $this->handler->setMailer(SubmitType::SENDMAIL);

        if ($this->handler->mailerTool !== '' && !\file_exists(\explode(' ', $this->handler->mailerTool)[0])) {
            self::markTestSkipped();
        }

        $mail                      = new Email();
        $mail->priority            = 1;
        $mail->confirmationAddress = 'test1@jingga.app';
        $mail->setFrom('test1@jingga.app', 'Dennis Eichhorn');
        $mail->addTo('test@jingga.app', 'Dennis Eichhorn');
        $mail->subject = 'testSendAltAttachmentWithSendmail';
        $mail->bodyAlt = 'Alt body';
        $mail->addAttachment(__DIR__ . '/files/logo.png', 'logo');

        self::assertTrue($this->handler->send($mail));
    }

    public function testSendPlainWithSendmail() : void
    {
        $this->handler->setMailer(SubmitType::SENDMAIL);

        if ($this->handler->mailerTool !== '' && !\file_exists(\explode(' ', $this->handler->mailerTool)[0])) {
            self::markTestSkipped();
        }

        $mail                      = new Email();
        $mail->priority            = 1;
        $mail->confirmationAddress = 'test1@jingga.app';
        $mail->setFrom('test1@jingga.app', 'Dennis Eichhorn');
        $mail->addTo('test@jingga.app', 'Dennis Eichhorn');
        $mail->subject = 'testSendPlainWithSendmail';
        $mail->body    = 'Body';

        self::assertTrue($this->handler->send($mail));
    }

    public function testSendPlainDKIMWithSendmail() : void
    {
        $this->handler->setMailer(SubmitType::SENDMAIL);

        if ($this->handler->mailerTool !== '' && !\file_exists(\explode(' ', $this->handler->mailerTool)[0])) {
            self::markTestSkipped();
        }

        $mail                      = new Email();
        $mail->priority            = 1;
        $mail->confirmationAddress = 'test1@jingga.app';
        $mail->setFrom('test1@jingga.app', 'Dennis Eichhorn');
        $mail->addTo('test@jingga.app', 'Dennis Eichhorn');
        $mail->subject         = 'testSendPlainDKIMWithSendmail';
        $mail->body            = 'Body';
        $mail->dkimPrivatePath = __DIR__ . '/dkim.pem';

        self::assertTrue($this->handler->send($mail));
    }

    public function testSendPlainDKIMSignWithSendmail() : void
    {
        $this->handler->setMailer(SubmitType::SENDMAIL);

        if ($this->handler->mailerTool !== '' && !\file_exists(\explode(' ', $this->handler->mailerTool)[0])) {
            self::markTestSkipped();
        }

        $mail                      = new Email();
        $mail->priority            = 1;
        $mail->confirmationAddress = 'test1@jingga.app';
        $mail->setFrom('test1@jingga.app', 'Dennis Eichhorn');
        $mail->addTo('test@jingga.app', 'Dennis Eichhorn');
        $mail->subject = 'testSendPlainDKIMSignWithSendmail';
        $mail->body    = 'Body';

        $mail->dkimDomain      = 'jingga.app';
        $mail->dkimPrivatePath = __DIR__ . '/dkim.pem';
        $mail->dkimSelector    = 'phpOMS';
        $mail->dkimPass        = '';

        self::assertTrue($this->handler->send($mail));
    }

    public function testSendPlainSignWithSendmail() : void
    {
        $this->handler->setMailer(SubmitType::SENDMAIL);

        if ($this->handler->mailerTool !== '' && !\file_exists(\explode(' ', $this->handler->mailerTool)[0])) {
            self::markTestSkipped();
        }

        $mail                      = new Email();
        $mail->priority            = 1;
        $mail->confirmationAddress = 'test1@jingga.app';
        $mail->setFrom('test1@jingga.app', 'Dennis Eichhorn');
        $mail->addTo('test@jingga.app', 'Dennis Eichhorn');
        $mail->subject = 'testSendPlainSignWithSendmail';
        $mail->body    = 'Body';

        $mail->sign(__DIR__ . '/cert.pem', __DIR__ . '/key.pem', 'password');

        self::assertTrue($this->handler->send($mail));
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('dataICalMethodSendmail')]
    public function testSendICalAltWithSendmail(string $methodLine, string $expected) : void
    {
        $this->handler->setMailer(SubmitType::SENDMAIL);

        if ($this->handler->mailerTool !== '' && !\file_exists(\explode(' ', $this->handler->mailerTool)[0])) {
            self::markTestSkipped();
        }

        $mail                      = new Email();
        $mail->priority            = 1;
        $mail->confirmationAddress = 'test1@jingga.app';
        $mail->setFrom('test1@jingga.app', 'Dennis Eichhorn');
        $mail->addTo('test@jingga.app', 'Dennis Eichhorn');
        $mail->subject = 'testSendICalAltWithSendmail';
        $mail->body    = 'Ical test';
        $mail->bodyAlt = 'Ical test';
        $mail->ical    = 'BEGIN:VCALENDAR'
            . "\r\nVERSION:2.0"
            . "\r\nPRODID:-//phpOMS//Karaka Calendar//EN"
            . $methodLine
            . "\r\nCALSCALE:GREGORIAN"
            . "\r\nX-MICROSOFT-CALSCALE:GREGORIAN"
            . "\r\nBEGIN:VEVENT"
            . "\r\nUID:201909250755-42825@test"
            . "\r\nDTSTART;20190930T080000Z"
            . "\r\nSEQUENCE:2"
            . "\r\nTRANSP:OPAQUE"
            . "\r\nSTATUS:CONFIRMED"
            . "\r\nDTEND:20190930T084500Z"
            . "\r\nLOCATION:[London] London Eye"
            . "\r\nSUMMARY:Test ICal method"
            . "\r\nATTENDEE;CN=Attendee, Test;ROLE=OPT-PARTICIPANT;PARTSTAT=NEEDS-ACTION;RSVP="
            . "\r\n TRUE:MAILTO:attendee-test@example.com"
            . "\r\nCLASS:PUBLIC"
            . "\r\nDESCRIPTION:Some plain text"
            . "\r\nORGANIZER;CN=\"Example, Test\":MAILTO:test@example.com"
            . "\r\nDTSTAMP:20190925T075546Z"
            . "\r\nCREATED:20190925T075709Z"
            . "\r\nLAST-MODIFIED:20190925T075546Z"
            . "\r\nEND:VEVENT"
            . "\r\nEND:VCALENDAR";

        $expected = 'Content-Type: text/calendar; method=' . $expected . ';';

        self::assertTrue($this->handler->send($mail));
        self::assertStringContainsString(
            $expected,
            $mail->bodyMime,
            'Wrong ICal method in Content-Type header'
        );
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('dataICalMethodSendmail')]
    public function testSendICalAltAttachmentWithSendmail(string $methodLine, string $expected) : void
    {
        $this->handler->setMailer(SubmitType::SENDMAIL);

        if ($this->handler->mailerTool !== '' && !\file_exists(\explode(' ', $this->handler->mailerTool)[0])) {
            self::markTestSkipped();
        }

        $mail                      = new Email();
        $mail->priority            = 1;
        $mail->confirmationAddress = 'test1@jingga.app';
        $mail->setFrom('test1@jingga.app', 'Dennis Eichhorn');
        $mail->addTo('test@jingga.app', 'Dennis Eichhorn');
        $mail->addAttachment(__DIR__ . '/files/logo.png', 'logo');
        $mail->subject = 'testSendICalAltAttachmentWithSendmail';
        $mail->body    = 'Ical test';
        $mail->bodyAlt = 'Ical test';
        $mail->ical    = 'BEGIN:VCALENDAR'
            . "\r\nVERSION:2.0"
            . "\r\nPRODID:-//phpOMS//Karaka Calendar//EN"
            . $methodLine
            . "\r\nCALSCALE:GREGORIAN"
            . "\r\nX-MICROSOFT-CALSCALE:GREGORIAN"
            . "\r\nBEGIN:VEVENT"
            . "\r\nUID:201909250755-42825@test"
            . "\r\nDTSTART;20190930T080000Z"
            . "\r\nSEQUENCE:2"
            . "\r\nTRANSP:OPAQUE"
            . "\r\nSTATUS:CONFIRMED"
            . "\r\nDTEND:20190930T084500Z"
            . "\r\nLOCATION:[London] London Eye"
            . "\r\nSUMMARY:Test ICal method"
            . "\r\nATTENDEE;CN=Attendee, Test;ROLE=OPT-PARTICIPANT;PARTSTAT=NEEDS-ACTION;RSVP="
            . "\r\n TRUE:MAILTO:attendee-test@example.com"
            . "\r\nCLASS:PUBLIC"
            . "\r\nDESCRIPTION:Some plain text"
            . "\r\nORGANIZER;CN=\"Example, Test\":MAILTO:test@example.com"
            . "\r\nDTSTAMP:20190925T075546Z"
            . "\r\nCREATED:20190925T075709Z"
            . "\r\nLAST-MODIFIED:20190925T075546Z"
            . "\r\nEND:VEVENT"
            . "\r\nEND:VCALENDAR";

        $expected = 'Content-Type: text/calendar; method=' . $expected . ';';

        self::assertTrue($this->handler->send($mail));
        self::assertStringContainsString(
            $expected,
            $mail->bodyMime,
            'Wrong ICal method in Content-Type header'
        );
    }

    public static function dataICalMethodSendmail() : array
    {
        return [
            'Valid method: request (default)' => [
                'methodLine' => "\r\nMETHOD:REQUEST",
                'expected'   => 'REQUEST',
            ],
            // Test ICal invalid method to use default (REQUEST).
            'Invalid method' => [
                'methodLine' => "\r\nMETHOD:INVALID",
                'expected'   => 'REQUEST',
            ],
            // Test ICal missing method to use default (REQUEST).
            'Missing method' => [
                'methodLine' => '',
                'expected'   => 'REQUEST',
            ],
        ];
    }
}
