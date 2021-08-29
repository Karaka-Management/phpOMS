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
use phpOMS\System\CharsetType;

/**
 * @testdox phpOMS\tests\Message\MailHandlerTest: Abstract mail handler
 *
 * @internal
 */
class EmailTestTest extends \PHPUnit\Framework\TestCase
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

    public function testEmailParsing() : void
    {
        self::assertEquals(
            [['name' => 'Test Name', 'address' => 'test@orange-management.org']],
            Email::parseAddresses('Test Name <test@orange-management.org>')
        );

        self::assertEquals(
            [['name' => 'Test Name', 'address' => 'test@orange-management.org']],
            Email::parseAddresses('Test Name <test@orange-management.org>', false)
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
        self::assertTrue($this->mail->addAttachment(__DIR__ . '/files/logo.png', 'logo'));
        self::assertTrue($this->mail->hasAttachment());
        self::assertCount(1, $this->mail->getAttachments());
    }
}
