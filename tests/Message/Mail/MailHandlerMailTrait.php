<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\tests\Message\Mail;

require_once __DIR__ . '/../../Autoloader.php';

use phpOMS\Message\Mail\Email;
use phpOMS\Message\Mail\SubmitType;
use phpOMS\System\CharsetType;
use phpOMS\System\OperatingSystem;
use phpOMS\System\SystemType;

trait MailHandlerMailTrait
{
    public function testSendTextWithMail() : void
    {
        $this->handler->setMailer(SubmitType::MAIL);

        if (($this->handler->mailerTool !== '' && !\file_exists(\explode(' ', $this->handler->mailerTool)[0]))
            || ($this->handler->mailerTool === '' && OperatingSystem::getSystem() !== SystemType::WIN && (\stripos($sendmailPath = \ini_get('sendmail_path'), 'sendmail') === false) || !\file_exists(\explode(' ', $sendmailPath)[0]))
        ) {
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
        $mail->subject = 'testSendTextWithMail';
        $mail->body    = "This is some content\n\Image: <img alt=\"image\" src=\"cid:cid1\">";
        $mail->bodyAlt = 'Alt body';
        $mail->addAttachment(__DIR__ . '/files/logo.png', 'logo');
        $mail->addEmbeddedImage(__DIR__ . '/files/logo.png', 'cid1');
        $mail->addStringAttachment('String content', 'string_content_file.txt');
        $mail->addStringEmbeddedImage(\file_get_contents(__DIR__ . '/files/logo.png'), 'cid2');

        self::assertTrue($this->handler->send($mail));
    }

    public function testSendHtmlWithMail() : void
    {
        $this->handler->setMailer(SubmitType::MAIL);

        if (($this->handler->mailerTool !== '' && !\file_exists(\explode(' ', $this->handler->mailerTool)[0]))
            || ($this->handler->mailerTool === '' && OperatingSystem::getSystem() !== SystemType::WIN && (\stripos($sendmailPath = \ini_get('sendmail_path'), 'sendmail') === false) || !\file_exists(\explode(' ', $sendmailPath)[0]))
        ) {
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

    public function testSendInlineWithMail() : void
    {
        $this->handler->setMailer(SubmitType::MAIL);

        if (($this->handler->mailerTool !== '' && !\file_exists(\explode(' ', $this->handler->mailerTool)[0]))
            || ($this->handler->mailerTool === '' && OperatingSystem::getSystem() !== SystemType::WIN && (\stripos($sendmailPath = \ini_get('sendmail_path'), 'sendmail') === false) || !\file_exists(\explode(' ', $sendmailPath)[0]))
        ) {
            self::markTestSkipped();
        }

        $mail                      = new Email();
        $mail->priority            = 1;
        $mail->confirmationAddress = 'test1@jingga.app';
        $mail->setFrom('test1@jingga.app', 'Dennis Eichhorn');
        $mail->addTo('test@jingga.app', 'Dennis Eichhorn');
        $mail->subject = 'testSendInlineWithMail';
        $mail->setHtml(true);
        $mail->msgHTML("<img alt=\"image\" src=\"cid:cid1\">");
        $mail->addEmbeddedImage(__DIR__ . '/files/logo.png', 'cid1');

        self::assertTrue($this->handler->send($mail));
    }

    public function testSendAttachmentWithMail() : void
    {
        $this->handler->setMailer(SubmitType::MAIL);

        if (($this->handler->mailerTool !== '' && !\file_exists(\explode(' ', $this->handler->mailerTool)[0]))
            || ($this->handler->mailerTool === '' && OperatingSystem::getSystem() !== SystemType::WIN && (\stripos($sendmailPath = \ini_get('sendmail_path'), 'sendmail') === false) || !\file_exists(\explode(' ', $sendmailPath)[0]))
        ) {
            self::markTestSkipped();
        }

        $mail                      = new Email();
        $mail->priority            = 1;
        $mail->confirmationAddress = 'test1@jingga.app';
        $mail->setFrom('test1@jingga.app', 'Dennis Eichhorn');
        $mail->addTo('test@jingga.app', 'Dennis Eichhorn');
        $mail->subject = 'testSendAttachmentWithMail';
        $mail->addAttachment(__DIR__ . '/files/logo.png', 'logo');

        self::assertTrue($this->handler->send($mail));
    }

    public function testSendAltWithMail() : void
    {
        $this->handler->setMailer(SubmitType::MAIL);

        if (($this->handler->mailerTool !== '' && !\file_exists(\explode(' ', $this->handler->mailerTool)[0]))
            || ($this->handler->mailerTool === '' && OperatingSystem::getSystem() !== SystemType::WIN && (\stripos($sendmailPath = \ini_get('sendmail_path'), 'sendmail') === false) || !\file_exists(\explode(' ', $sendmailPath)[0]))
        ) {
            self::markTestSkipped();
        }

        $mail                      = new Email();
        $mail->priority            = 1;
        $mail->confirmationAddress = 'test1@jingga.app';
        $mail->setFrom('test1@jingga.app', 'Dennis Eichhorn');
        $mail->addTo('test@jingga.app', 'Dennis Eichhorn');
        $mail->subject = 'testSendAltWithMail';
        $mail->bodyAlt = 'Alt body';

        self::assertTrue($this->handler->send($mail));
    }

    public function testSendAltInlineWithMail() : void
    {
        $this->handler->setMailer(SubmitType::MAIL);

        if (($this->handler->mailerTool !== '' && !\file_exists(\explode(' ', $this->handler->mailerTool)[0]))
            || ($this->handler->mailerTool === '' && OperatingSystem::getSystem() !== SystemType::WIN && (\stripos($sendmailPath = \ini_get('sendmail_path'), 'sendmail') === false) || !\file_exists(\explode(' ', $sendmailPath)[0]))
        ) {
            self::markTestSkipped();
        }

        $mail                      = new Email();
        $mail->priority            = 1;
        $mail->confirmationAddress = 'test1@jingga.app';
        $mail->setFrom('test1@jingga.app', 'Dennis Eichhorn');
        $mail->addTo('test@jingga.app', 'Dennis Eichhorn');
        $mail->subject = 'testSendAltInlineWithMail';
        $mail->bodyAlt = 'Alt body';
        $mail->setHtml(true);
        $mail->msgHTML("<img alt=\"image\" src=\"cid:cid1\">");
        $mail->addEmbeddedImage(__DIR__ . '/files/logo.png', 'cid1');

        self::assertTrue($this->handler->send($mail));
    }

    public function testSendAltAttachmentWithMail() : void
    {
        $this->handler->setMailer(SubmitType::MAIL);

        if (($this->handler->mailerTool !== '' && !\file_exists(\explode(' ', $this->handler->mailerTool)[0]))
            || ($this->handler->mailerTool === '' && OperatingSystem::getSystem() !== SystemType::WIN && (\stripos($sendmailPath = \ini_get('sendmail_path'), 'sendmail') === false) || !\file_exists(\explode(' ', $sendmailPath)[0]))
        ) {
            self::markTestSkipped();
        }

        $mail                      = new Email();
        $mail->priority            = 1;
        $mail->confirmationAddress = 'test1@jingga.app';
        $mail->setFrom('test1@jingga.app', 'Dennis Eichhorn');
        $mail->addTo('test@jingga.app', 'Dennis Eichhorn');
        $mail->subject = 'testSendAltAttachmentWithMail';
        $mail->bodyAlt = 'Alt body';
        $mail->addAttachment(__DIR__ . '/files/logo.png', 'logo');

        self::assertTrue($this->handler->send($mail));
    }

    public function testSendPlainWithMail() : void
    {
        $this->handler->setMailer(SubmitType::MAIL);

        if (($this->handler->mailerTool !== '' && !\file_exists(\explode(' ', $this->handler->mailerTool)[0]))
            || ($this->handler->mailerTool === '' && OperatingSystem::getSystem() !== SystemType::WIN && (\stripos($sendmailPath = \ini_get('sendmail_path'), 'sendmail') === false) || !\file_exists(\explode(' ', $sendmailPath)[0]))
        ) {
            self::markTestSkipped();
        }

        $mail                      = new Email();
        $mail->priority            = 1;
        $mail->confirmationAddress = 'test1@jingga.app';
        $mail->setFrom('test1@jingga.app', 'Dennis Eichhorn');
        $mail->addTo('test@jingga.app', 'Dennis Eichhorn');
        $mail->subject = 'testSendPlainWithMail';
        $mail->body    = 'Body';

        self::assertTrue($this->handler->send($mail));
    }

    public function testSendPlainDKIMWithMail() : void
    {
        $this->handler->setMailer(SubmitType::MAIL);

        if (($this->handler->mailerTool !== '' && !\file_exists(\explode(' ', $this->handler->mailerTool)[0]))
            || ($this->handler->mailerTool === '' && OperatingSystem::getSystem() !== SystemType::WIN && (\stripos($sendmailPath = \ini_get('sendmail_path'), 'sendmail') === false) || !\file_exists(\explode(' ', $sendmailPath)[0]))
        ) {
            self::markTestSkipped();
        }

        $mail                      = new Email();
        $mail->priority            = 1;
        $mail->confirmationAddress = 'test1@jingga.app';
        $mail->setFrom('test1@jingga.app', 'Dennis Eichhorn');
        $mail->addTo('test@jingga.app', 'Dennis Eichhorn');
        $mail->subject         = 'testSendPlainDKIMWithMail';
        $mail->body            = 'Body';
        $mail->dkimPrivatePath = __DIR__ . '/dkim.pem';

        self::assertTrue($this->handler->send($mail));
    }

    public function testSendPlainDKIMSignWithMail() : void
    {
        $this->handler->setMailer(SubmitType::MAIL);

        if (($this->handler->mailerTool !== '' && !\file_exists(\explode(' ', $this->handler->mailerTool)[0]))
            || ($this->handler->mailerTool === '' && OperatingSystem::getSystem() !== SystemType::WIN && (\stripos($sendmailPath = \ini_get('sendmail_path'), 'sendmail') === false) || !\file_exists(\explode(' ', $sendmailPath)[0]))
        ) {
            self::markTestSkipped();
        }

        $mail                      = new Email();
        $mail->priority            = 1;
        $mail->confirmationAddress = 'test1@jingga.app';
        $mail->setFrom('test1@jingga.app', 'Dennis Eichhorn');
        $mail->addTo('test@jingga.app', 'Dennis Eichhorn');
        $mail->subject = 'testSendPlainDKIMSignWithMail';
        $mail->body    = 'Body';

        $mail->dkimDomain      = 'jingga.app';
        $mail->dkimPrivatePath = __DIR__ . '/dkim.pem';
        $mail->dkimSelector    = 'phpOMS';
        $mail->dkimPass        = '';

        self::assertTrue($this->handler->send($mail));
    }

    public function testSendPlainSignWithMail() : void
    {
        $this->handler->setMailer(SubmitType::MAIL);

        if (($this->handler->mailerTool !== '' && !\file_exists(\explode(' ', $this->handler->mailerTool)[0]))
            || ($this->handler->mailerTool === '' && OperatingSystem::getSystem() !== SystemType::WIN && (\stripos($sendmailPath = \ini_get('sendmail_path'), 'sendmail') === false) || !\file_exists(\explode(' ', $sendmailPath)[0]))
        ) {
            self::markTestSkipped();
        }

        $mail                      = new Email();
        $mail->priority            = 1;
        $mail->confirmationAddress = 'test1@jingga.app';
        $mail->setFrom('test1@jingga.app', 'Dennis Eichhorn');
        $mail->addTo('test@jingga.app', 'Dennis Eichhorn');
        $mail->subject = 'testSendPlainSignWithMail';
        $mail->body    = 'Body';

        $mail->sign(__DIR__ . '/cert.pem', __DIR__ . '/key.pem', 'password');

        self::assertTrue($this->handler->send($mail));
    }

    /**
     * @dataProvider dataICalMethodMail
     */
    public function testSendICalAltWithMail(string $methodLine, string $expected) : void
    {
        $this->handler->setMailer(SubmitType::MAIL);

        if (($this->handler->mailerTool !== '' && !\file_exists(\explode(' ', $this->handler->mailerTool)[0]))
            || ($this->handler->mailerTool === '' && OperatingSystem::getSystem() !== SystemType::WIN && (\stripos($sendmailPath = \ini_get('sendmail_path'), 'sendmail') === false) || !\file_exists(\explode(' ', $sendmailPath)[0]))
        ) {
            self::markTestSkipped();
        }

        $mail                      = new Email();
        $mail->priority            = 1;
        $mail->confirmationAddress = 'test1@jingga.app';
        $mail->setFrom('test1@jingga.app', 'Dennis Eichhorn');
        $mail->addTo('test@jingga.app', 'Dennis Eichhorn');
        $mail->subject    = 'testSendICalAltWithMail';
        $mail->body       = 'Ical test';
        $mail->bodyAlt    = 'Ical test';
        $mail->ical       = 'BEGIN:VCALENDAR'
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

    /**
     * @dataProvider dataICalMethodMail
     */
    public function testSendICalAltAttachmentWithMail(string $methodLine, string $expected) : void
    {
        $this->handler->setMailer(SubmitType::MAIL);

        if (($this->handler->mailerTool !== '' && !\file_exists(\explode(' ', $this->handler->mailerTool)[0]))
            || ($this->handler->mailerTool === '' && OperatingSystem::getSystem() !== SystemType::WIN && (\stripos($sendmailPath = \ini_get('sendmail_path'), 'sendmail') === false) || !\file_exists(\explode(' ', $sendmailPath)[0]))
        ) {
            self::markTestSkipped();
        }

        $mail                      = new Email();
        $mail->priority            = 1;
        $mail->confirmationAddress = 'test1@jingga.app';
        $mail->setFrom('test1@jingga.app', 'Dennis Eichhorn');
        $mail->addTo('test@jingga.app', 'Dennis Eichhorn');
        $mail->addAttachment(__DIR__ . '/files/logo.png', 'logo');
        $mail->subject    = 'testSendICalAltAttachmentWithMail';
        $mail->body       = 'Ical test';
        $mail->bodyAlt    = 'Ical test';
        $mail->ical       = 'BEGIN:VCALENDAR'
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

    public function dataICalMethodMail()
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
