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

use phpOMS\Message\Mail\MailBoxInterface;
use phpOMS\Message\Mail\Pop3;

/**
 * @testdox phpOMS\tests\Message\MailHandlerTest: Abstract mail handler
 *
 * @internal
 */
final class Pop3Test extends \PHPUnit\Framework\TestCase
{
    protected MailBoxInterface $handler;

    /**
     * {@inheritdoc}
     */
    protected function setUp() : void
    {
        $this->handler        = new Pop3('testuser', 'testuser', 110);
        $this->handler->host  = '127.0.0.1';
        $this->handler->flags = '/pop3/notls';

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

    /* Not working with pop3
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
    */

    public function testWithActualMail() : void
    {
        \mail('testuser@localhost', 'Test Subject', 'This is my test message!');
        self::assertGreaterThan(0, $mailCount = $this->handler->countMail('INBOX'));
        //self::assertGreaterThan(0, $recentCount = $this->handler->countRecent('INBOX'));
        self::assertGreaterThan(0, \count($this->handler->getHeaders('INBOX')));
    }

    public function testMailboxInfo() : void
    {
        $info = $this->handler->getMailboxInfo('INBOX');

        self::assertGreaterThan(0, $info->messages);
        self::assertGreaterThan(0, $info->recent);
        self::assertGreaterThan(0, $info->unseen);
    }

    public function testCountMail() : void
    {
        self::assertGreaterThan(0, $this->handler->countMail('INBOX'));
    }

    public function testCountRecent() : void
    {
        $this->handler->createBox('INBOX');
        self::assertGreaterThan(0, $this->handler->countRecent('INBOX'));
        $this->handler->deleteBox('INBOX');
    }
}
