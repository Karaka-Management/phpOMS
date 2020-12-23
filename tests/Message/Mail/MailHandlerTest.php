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

use phpOMS\Message\Mail\MailHandler;
use phpOMS\Message\Mail\SubmitType;
use phpOMS\Message\Mail\Email;
use phpOMS\Message\Mail\Imap;

/**
 * @testdox phpOMS\tests\Message\MailHandlerTest: Abstract mail handler
 *
 * @internal
 */
class MailHandlerTest extends \PHPUnit\Framework\TestCase
{
    public function testSendTextWithMail() : void
    {
        $mailer = new MailHandler();
        $mailer->setMailer(SubmitType::MAIL);

        $mail = new Email();
        $mail->setFrom('dennis.eichhorn@orange-management.org', 'Dennis Eichhorn');
        $mail->addTo('info@orange-management.org', 'Dennis Eichhorn');
        $mail->subject = 'Test email';
        $mail->body    = 'This is some content';

        self::assertTrue($mailer->send($mail));
    }

    public function testReceiveMailWithImap() : void
    {/*
        $mailer = new Imap();
        $mailer->connectInbox();

        var_dump($mailer->getBoxes());*/
    }
}
