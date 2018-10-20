<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    tests
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */

namespace phpOMS\tests\Message\Mail;

use phpOMS\Message\Mail\Imap;

class ImapTest extends \PHPUnit\Framework\TestCase
{
    public function testDefault()
    {
        $email = new Imap(
            $GLOBALS['CONFIG']['mail']['imap']['host'],
            $GLOBALS['CONFIG']['mail']['imap']['port'],
            30,
            $GLOBALS['CONFIG']['mail']['imap']['ssl']
        );

        self::assertFalse($email->isConnected());
        self::assertTrue($email->connect(
            $GLOBALS['CONFIG']['mail']['imap']['user'],
            $GLOBALS['CONFIG']['mail']['imap']['password']
        ));

        self::assertTrue($email->isConnected());
        self::assertEquals([], $email->getBoxes());
        self::assertEquals([], $email->getQuota());
        self::assertInstanceOf('\phpOMS\Message\Mail\Mail', $email->getEmail('1'));
        self::assertEquals([], $email->getInboxAll());
        self::assertEquals([], $email->getInboxOverview());
        self::assertEquals([], $email->getInboxNew());
        self::assertEquals([], $email->getInboxFrom(''));
        self::assertEquals([], $email->getInboxTo(''));
        self::assertEquals([], $email->getInboxCc(''));
        self::assertEquals([], $email->getInboxBcc(''));
        self::assertEquals([], $email->getInboxAnswered());
        self::assertEquals([], $email->getInboxSubject(''));
        self::assertEquals([], $email->getInboxSince(new \DateTime('now')));
        self::assertEquals([], $email->getInboxUnseen());
        self::assertEquals([], $email->getInboxSeen());
        self::assertEquals([], $email->getInboxDeleted());
        self::assertEquals([], $email->getInboxText(''));
        self::assertEquals([], $email->getMessageOverview(1, 1));
        self::assertEquals(0, $email->countMessages());
        self::assertEquals('', $email->getMessageHeader(1));
    }
}
