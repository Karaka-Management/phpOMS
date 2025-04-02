<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.2
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\tests\DataStorage\Session;

use phpOMS\DataStorage\Session\HttpSession;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\DataStorage\Session\HttpSessionTest: Session data handler for http sessions')]
final class HttpSessionTest extends \PHPUnit\Framework\TestCase
{
    protected HttpSession $session;

    /**
     * {@inheritdoc}
     */
    protected function setUp() : void
    {
        $this->session = new HttpSession(1, '', 1);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The session has the expected default values after initialization')]
    public function testDefault() : void
    {
        $session = new HttpSession();
        self::assertNull($session->get('key'));
        self::assertFalse($session->isLocked());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Session data can be set and returned')]
    public function testInputOutput() : void
    {
        self::assertTrue($this->session->set('test', 'value'));
        self::assertEquals('value', $this->session->get('test'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Session data cannot be overwritten')]
    public function testInvalidOverwrite() : void
    {
        $this->session->set('test', 'value');
        self::assertFalse($this->session->set('test', 'value2'));
        self::assertEquals('value', $this->session->get('test'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Session data can be forced to overwrite')]
    public function testOverwrite() : void
    {
        $this->session->set('test', 'value');
        self::assertTrue($this->session->set('test', 'value2', true));
        self::assertEquals('value2', $this->session->get('test'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Session data can be removed')]
    public function testRemove() : void
    {
        $this->session->set('test', 'value');
        self::assertTrue($this->session->remove('test'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('None-existing session data cannot be removed')]
    public function testInvalidRemove() : void
    {
        $this->session->set('test', 'value');
        $this->session->remove('test');

        self::assertFalse($this->session->remove('test'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A session id can be set and returned')]
    public function testSessionIdInputOutput() : void
    {
        $this->session->setSID('abc');
        self::assertEquals('abc', $this->session->getSID());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A session can be locked')]
    public function testLockInputOutput() : void
    {
        $this->session->lock();
        self::assertTrue($this->session->isLocked());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Locked sessions cannot be saved')]
    public function testInvalidLockSave() : void
    {
        $this->session->lock();
        self::assertFalse($this->session->save());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A locked session cannot add or change data')]
    public function testLockInvalidSet() : void
    {
        $this->session->lock();
        self::assertFalse($this->session->set('test', 'value'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A locked session cannot remove data')]
    public function testLockInvalidRemove() : void
    {
        self::assertTrue($this->session->set('test', 'value'));
        $this->session->lock();
        self::assertFalse($this->session->remove('test'));
    }
}
