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

namespace phpOMS\tests\DataStorage\Session;

use phpOMS\DataStorage\Session\FileSession;

/**
 * @testdox phpOMS\tests\DataStorage\Session\FileSessionTest: File session handler
 *
 * @internal
 */
final class FileSessionTest extends \PHPUnit\Framework\TestCase
{
    protected FileSession $session;

    /**
     * {@inheritdoc}
     */
    protected function setUp() : void
    {
        $this->session = new FileSession();
    }

    /**
     * @testdox The session has the expected default values after initialization
     * @group framework
     */
    public function testDefault() : void
    {
        self::assertNull($this->session->get('key'));
        self::assertFalse($this->session->isLocked());
    }

    /**
     * @testdox Session data can be set and returned
     * @group framework
     */
    public function testInputOutput() : void
    {
        self::assertTrue($this->session->set('test', 'value'));
        self::assertEquals('value', $this->session->get('test'));
    }

    /**
     * @testdox Session data cannot be overwritten
     * @group framework
     */
    public function testInvalidOverwrite() : void
    {
        $this->session->set('test', 'value');
        self::assertFalse($this->session->set('test', 'value2'));
        self::assertEquals('value', $this->session->get('test'));
    }

    /**
     * @testdox Session data can be forced to overwrite
     * @group framework
     */
    public function testOverwrite() : void
    {
        $this->session->set('test', 'value');
        self::assertTrue($this->session->set('test', 'value2', true));
        self::assertEquals('value2', $this->session->get('test'));
    }

    /**
     * @testdox Session data can be removed
     * @group framework
     */
    public function testRemove() : void
    {
        $this->session->set('test', 'value');
        self::assertTrue($this->session->remove('test'));
    }

    /**
     * @testdox None-existing session data cannot be removed
     * @group framework
     */
    public function testInvalidRemove() : void
    {
        $this->session->set('test', 'value');
        $this->session->remove('test');

        self::assertFalse($this->session->remove('test'));
    }

    /**
     * @testdox A session id can be set and returned
     * @group framework
     */
    public function testSessionIdInputOutput() : void
    {
        $this->session->setSID('abc');
        self::assertEquals('abc', $this->session->getSID());
    }

    /**
     * @testdox A session can be locked
     * @group framework
     */
    public function testLockInputOutput() : void
    {
        $this->session->lock();
        self::assertTrue($this->session->isLocked());
    }

    /**
     * @testdox Session data can be saved
     * @group framework
     */
    public function testSave() : void
    {
        self::assertTrue($this->session->save());
    }

    /**
     * @testdox Locked sessions cannot be saved
     * @group framework
     */
    public function testInvalidLockSave() : void
    {
        $this->session->lock();
        self::assertFalse($this->session->save());
    }

    /**
     * @testdox A locked session cannot add or change data
     * @group framework
     */
    public function testLockInvalidSet() : void
    {
        $this->session->lock();
        self::assertFalse($this->session->set('test', 'value'));
    }

    /**
     * @testdox A locked session cannot remove data
     * @group framework
     */
    public function testLockInvalidRemove() : void
    {
        self::assertTrue($this->session->set('test', 'value'));
        $this->session->lock();
        self::assertFalse($this->session->remove('test'));
    }
}
