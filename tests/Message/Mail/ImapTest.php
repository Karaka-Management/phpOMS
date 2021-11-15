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

namespace phpOMS\tests\Message\Mail;

require_once __DIR__ . '/../../Autoloader.php';

use phpOMS\Message\Mail\Imap;
use phpOMS\Message\Mail\MailBoxInterface;

/**
 * @testdox phpOMS\tests\Message\MailHandlerTest: Abstract mail handler
 *
 * @internal
 */
final class ImapTest extends \PHPUnit\Framework\TestCase
{
    protected MailBoxInterface $handler;

    /**
     * {@inheritdoc}
     */
    protected function setUp() : void
    {
        $this->handler        = new Imap('testuser', 'testuser', 143);
        $this->handler->host  = '127.0.0.1';
        $this->handler->flags = '/imap/notls/norsh/novalidate-cert';

        try {
            if (!$this->handler->connectInbox()) {
                self::markTestSkipped();
            }
        } catch (\Throwable $t) {
            self::markTestSkipped();
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown() : void
    {
        $this->handler->closeInbox();
    }

    public function testConnect() : void
    {
        $this->handler->closeInbox();
        self::assertTrue($this->handler->connectInbox());
    }

    public function testBoxes() : void
    {
        self::assertTrue(\in_array('INBOX', $this->handler->getBoxes()));
    }

    public function testBoxesInputOutputDelte() : void
    {
        $startCount = \count($this->handler->getBoxes());

        self::assertTrue($this->handler->createBox('TestBox'));
        self::assertTrue(\in_array('TestBox', $this->handler->getBoxes()));
        self::assertCount($startCount + 1, $this->handler->getBoxes());

        self::assertTrue($this->handler->renameBox('TestBox', 'NewTestBox'));
        self::assertTrue(\in_array('NewTestBox', $this->handler->getBoxes()));
        self::assertCount($startCount + 1, $this->handler->getBoxes());

        self::assertTrue($this->handler->deleteBox('NewTestBox'));
        self::assertCount($startCount, $this->handler->getBoxes());
    }

    public function testMailboxInfo() : void
    {
        $this->handler->createBox('INBOX.TestBox');
        $info = $this->handler->getMailboxInfo('INBOX.TestBox');
        $this->handler->deleteBox('INBOX.TestBox');

        self::assertEquals(0, $info->messages);
        self::assertEquals(0, $info->recent);
        self::assertEquals(0, $info->unseen);
    }

    public function testCountMail() : void
    {
        $this->handler->createBox('INBOX.TestBox');
        self::assertEquals(0, $this->handler->countMail('INBOX.TestBox'));
        $this->handler->deleteBox('INBOX.TestBox');
    }

    public function testCountRecent() : void
    {
        $this->handler->createBox('INBOX.TestBox');
        self::assertEquals(0, $this->handler->countRecent('INBOX.TestBox'));
        $this->handler->deleteBox('INBOX.TestBox');
    }

    public function testWithActualMail() : void
    {
        \mail('testuser@localhost', 'Test Subject', 'This is my test message!');
        self::assertGreaterThan(0, $mailCount = $this->handler->countMail('INBOX'));
        //self::assertGreaterThan(0, $recentCount = $this->handler->countRecent('INBOX'));
        self::assertGreaterThan(0, \count($this->handler->getHeaders('INBOX')));
    }
}
