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
use phpOMS\System\CharsetType;
use phpOMS\System\MimeType;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Message\MailHandlerTest: Abstract mail handler')]
final class EmailTest extends \PHPUnit\Framework\TestCase
{
    protected Email $mail;

    /**
     * {@inheritdoc}
     */
    protected function setUp() : void
    {
        $this->mail = new Email();
    }

    public function testDefault() : void
    {
        self::assertEquals(CharsetType::ISO_8859_1, $this->mail->charset);
        self::assertEquals('', $this->mail->subject);
        self::assertEquals('', $this->mail->body);
        self::assertEquals('', $this->mail->bodyAlt);
        self::assertFalse($this->mail->hasAttachment());
        self::assertFalse($this->mail->hasInlineImage());
    }

    public function testFromInputOutput() : void
    {
        self::assertTrue($this->mail->setFrom('test@karaka.app', 'Test Name'));
        self::assertEquals([0 => 'test@karaka.app', 1 => 'Test Name'], $this->mail->from);
    }

    public function testInvalidFromInputOutput() : void
    {
        self::assertFalse($this->mail->setFrom('Test Name <test-^invalid>'));
        self::assertEquals([], $this->mail->from);
    }

    public function testContentTypeInputOutput() : void
    {
        $this->mail->setHtml(true);
        self::assertEquals(MimeType::M_HTML, $this->mail->getContentType());
        self::assertTrue($this->mail->isHtml());

        $this->mail->setHtml(false);
        self::assertEquals(MimeType::M_TEXT, $this->mail->getContentType());
        self::assertFalse($this->mail->isHtml());
    }

    public function testAddTo() : void
    {
        self::assertTrue($this->mail->addTo('test@karaka.app', 'Test Name'));
        self::assertTrue($this->mail->addTo('test2@karaka.app', 'Test Name 2'));

        self::assertEquals(
            [
                'test@karaka.app'  => ['test@karaka.app', 'Test Name'],
                'test2@karaka.app' => ['test2@karaka.app', 'Test Name 2'],
            ],
            $this->mail->to
        );
    }

    public function testInvalidAddTo() : void
    {
        self::assertFalse($this->mail->addTo('test-^invalid', 'Test Name'));
        self::assertEquals([], $this->mail->to);
    }

    public function testAddCC() : void
    {
        self::assertTrue($this->mail->addCC('test@karaka.app', 'Test Name'));
        self::assertTrue($this->mail->addCC('test2@karaka.app', 'Test Name 2'));

        self::assertEquals(
            [
                'test@karaka.app'  => ['test@karaka.app', 'Test Name'],
                'test2@karaka.app' => ['test2@karaka.app', 'Test Name 2'],
            ],
            $this->mail->cc
        );
    }

    public function testInvalidAddCC() : void
    {
        self::assertFalse($this->mail->addCC('test-^invalid', 'Test Name'));
        self::assertEquals([], $this->mail->cc);
    }

    public function testAddBCC() : void
    {
        self::assertTrue($this->mail->addBCC('test@karaka.app', 'Test Name'));
        self::assertTrue($this->mail->addBCC('test2@karaka.app', 'Test Name 2'));

        self::assertEquals(
            [
                'test@karaka.app'  => ['test@karaka.app', 'Test Name'],
                'test2@karaka.app' => ['test2@karaka.app', 'Test Name 2'],
            ],
            $this->mail->bcc
        );
    }

    public function testInvalidAddBCC() : void
    {
        self::assertFalse($this->mail->addBCC('test-^invalid', 'Test Name'));
        self::assertEquals([], $this->mail->bcc);
    }

    public function testAddReplyTo() : void
    {
        self::assertTrue($this->mail->addReplyTo('test@karaka.app', 'Test Name'));
        self::assertTrue($this->mail->addReplyTo('test2@karaka.app', 'Test Name 2'));

        self::assertEquals(
            [
                'test@karaka.app'  => ['test@karaka.app', 'Test Name'],
                'test2@karaka.app' => ['test2@karaka.app', 'Test Name 2'],
            ],
            $this->mail->replyTo
        );
    }

    public function testInvalidAddReplyTo() : void
    {
        self::assertFalse($this->mail->addReplyTo('test-^invalid', 'Test Name'));
        self::assertEquals([], $this->mail->replyTo);
    }

    public function testMissingAddressPreSend() : void
    {
        self::assertFalse($this->mail->preSend(''));
    }

    public function testAddrFormat() : void
    {
        self::assertEquals('test@karaka.app', $this->mail->addrFormat(['test@karaka.app']));
        self::assertEquals('Test Name <test@karaka.app>', $this->mail->addrFormat(['test@karaka.app', 'Test Name']));
    }

    public function testCustomHeaderInputOutput() : void
    {
        self::assertTrue($this->mail->addCustomHeader('name', 'value'));
        self::assertEquals([['name', 'value']], $this->mail->getCustomHeaders());
    }

    public function testInvalidCustomHeaderInputOutput() : void
    {
        self::assertFalse($this->mail->addCustomHeader('', ''));
        self::assertEquals([], $this->mail->getCustomHeaders());
    }

    public function testEmailParsing() : void
    {
        self::assertEquals(
            [['name' => 'Test Name', 'address' => 'test@karaka.app']],
            Email::parseAddresses('Test Name <test@karaka.app>')
        );

        self::assertEquals(
            [['name' => '', 'address' => 'test@karaka.app']],
            Email::parseAddresses('test@karaka.app')
        );

        self::assertEquals(
            [['name' => 'Test Name', 'address' => 'test@karaka.app']],
            Email::parseAddresses('Test Name <test@karaka.app>', false)
        );

        self::assertEquals(
            [['name' => '', 'address' => 'test@karaka.app']],
            Email::parseAddresses('test@karaka.app', false)
        );
    }

    public function testHtml() : void
    {
        $message             = \file_get_contents(__DIR__ . '/files/utf8.html');
        $this->mail->charset = CharsetType::UTF_8;
        $this->mail->body    = '';
        $this->mail->bodyAlt = '';

        $this->mail->msgHTML($message, __DIR__ . '/files');
        //$this->mail->subject = 'msgHTML';

        self::assertNotEmpty($this->mail->body);
        self::assertNotEmpty($this->mail->bodyAlt);
        self::assertTrue(\stripos($this->mail->body, 'cid:') !== false);
    }

    public function testAttachment() : void
    {
        self::assertTrue($this->mail->addAttachment(__DIR__ . '/files/logo.png'));
        self::assertTrue($this->mail->hasAttachment());
        self::assertCount(1, $this->mail->getAttachments());
    }

    public function testStringAttachment() : void
    {
        self::assertTrue($this->mail->addStringAttachment('string', __DIR__ . '/files/logo.png'));
        self::assertTrue($this->mail->hasAttachment());
        self::assertCount(1, $this->mail->getAttachments());
    }

    public function testEmbeddedImage() : void
    {
        self::assertTrue($this->mail->addEmbeddedImage(__DIR__ . '/files/logo.png', 'cid'));
        self::assertTrue($this->mail->hasInlineImage());
        self::assertCount(1, $this->mail->getAttachments());
    }

    public function testStringEmbeddedImage() : void
    {
        self::assertTrue($this->mail->addStringEmbeddedImage('string', 'cid', __DIR__ . '/files/logo.png'));
        self::assertTrue($this->mail->hasInlineImage());
        self::assertCount(1, $this->mail->getAttachments());
    }

    public function testInvalidAttachmentPath() : void
    {
        self::assertFalse($this->mail->addAttachment(__DIR__ . '/invalid.txt'));
    }

    public function testInvalidEmbeddedImage() : void
    {
        self::assertFalse($this->mail->addEmbeddedImage(__DIR__ . '/invalid.png', ''));
    }

    public function testQuotedPrintableDkimHeader() : void
    {
        self::assertEquals(
            "J'interdis=20aux=20marchands=20de=20vanter=20trop=20leurs=20marchandises.=20Car=20ils=20se=20font=20vite=20p=C3=A9dagogues=20et=20t'enseignent=20comme=20but=20ce=20qui=20n'est=20par=20essence=20qu'un=20moyen,=20et=20te=20trompant=20ainsi=20sur=20la=20route=20=C3=A0=20suivre=20les=20voil=C3=A0=20bient=C3=B4t=20qui=20te=20d=C3=A9gradent,=20car=20si=20leur=20musique=20est=20vulgaire=20ils=20te=20fabriquent=20pour=20te=20la=20vendre=20une=20=C3=A2me=20vulgaire.",
            $this->mail->dkimQP("J'interdis aux marchands de vanter trop leurs marchandises. Car ils se font vite pédagogues et t'enseignent comme but ce qui n'est par essence qu'un moyen, et te trompant ainsi sur la route à suivre les voilà bientôt qui te dégradent, car si leur musique est vulgaire ils te fabriquent pour te la vendre une âme vulgaire.")
        );
    }

    public function testCanonicalizedDkimHeader() : void
    {
        self::assertEquals("header1:value1\r\nheader2:value2", $this->mail->dkimHeaderC("HEADER1:value1\t\nheader2:value2"));
    }

    public function testCanonicalizedDkimBody() : void
    {
        self::assertEquals("Test\r\n string\r\n", $this->mail->dkimBodyC("Test\n string\t"));
        self::assertEquals("\r\n", $this->mail->dkimBodyC(''));
    }
}
