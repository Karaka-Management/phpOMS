<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\tests\DataStorage\Session;

use phpOMS\DataStorage\Session\HttpSession;

/**
 * @testdox phpOMS\tests\DataStorage\Session\HttpSessionTest: Session data handler for http sessions
 *
 * @internal
 */
class HttpSessionTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox The session has the expected default values after initialization
     * @group framework
     */
    public function testDefault() : void
    {
        $session = new HttpSession();
        self::assertNull($session->get('key'));
        self::assertFalse($session->isLocked());
    }

    /**
     * @testdox Session data can be set and returned
     * @group framework
     */
    public function testInputOutput() : void
    {
        $session = new HttpSession(1, false, 1);
        self::assertTrue($session->set('test', 'value'));
        self::assertEquals('value', $session->get('test'));
    }

    /**
     * @testdox Session data cannot be overwritten
     * @group framework
     */
    public function testInvalidOverwrite() : void
    {
        $session = new HttpSession(1, false, 1);
        $session->set('test', 'value');
        self::assertFalse($session->set('test', 'value2'));
        self::assertEquals('value', $session->get('test'));
    }

    /**
     * @testdox Session data can be forced to overwrite
     * @group framework
     */
    public function testOverwrite() : void
    {
        $session = new HttpSession(1, false, 1);
        $session->set('test', 'value');
        self::assertTrue($session->set('test', 'value2', true));
        self::assertEquals('value2', $session->get('test'));
    }

    /**
     * @testdox Session data can be removed
     * @group framework
     */
    public function testRemove() : void
    {
        $session = new HttpSession(1, false, 1);
        $session->set('test', 'value');
        self::assertTrue($session->remove('test'));
    }

    /**
     * @testdox None-existing session data cannot be removed
     * @group framework
     */
    public function testInvalidRemove() : void
    {
        $session = new HttpSession(1, false, 1);
        $session->set('test', 'value');
        $session->remove('test');

        self::assertFalse($session->remove('test'));
    }

    /**
     * @testdox A session id can be set and returned
     * @group framework
     */
    public function testSessionIdInputOutput() : void
    {
        $session = new HttpSession(1, false, 1);
        $session->setSID('abc');
        self::assertEquals('abc', $session->getSID());
    }

    /**
     * @testdox A session can be locked
     * @group framework
     */
    public function testLockInputOutput() : void
    {
        $session = new HttpSession(1, false, 1);

        $session->lock();
        self::assertTrue($session->isLocked());
    }

    /**
     * @testdox A locked session cannot add or change data
     * @group framework
     */
    public function testLockInvalidSet() : void
    {
        $session = new HttpSession(1, false, 1);

        $session->lock();
        self::assertFalse($session->set('test', 'value'));
    }

    /**
     * @testdox A locked session cannot remove data
     * @group framework
     */
    public function testLockInvalidRemove() : void
    {
        $session = new HttpSession(1, false, 1);

        self::assertTrue($session->set('test', 'value'));
        $session->lock();
        self::assertFalse($session->remove('test'));
    }
}
