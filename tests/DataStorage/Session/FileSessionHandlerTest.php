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

use phpOMS\DataStorage\Session\FileSessionHandler;
use phpOMS\System\File\Local\Directory;

/**
 * @testdox phpOMS\tests\DataStorage\Session\FileSessionHandlerTest: File session handler
 *
 * @internal
 */
final class FileSessionHandlerTest extends \PHPUnit\Framework\TestCase
{
    protected FileSessionHandler $sh;

    /**
     * {@inheritdoc}
     */
    protected function setUp() : void
    {
        if (\is_dir(__DIR__ . '/test')) {
            Directory::delete(__DIR__ . '/test');
        }

        $this->sh = new FileSessionHandler(__DIR__ . '/test');
    }

    protected function tearDown() : void
    {
        if (\is_dir(__DIR__ . '/test')) {
            Directory::delete(__DIR__ . '/test');
        }
    }

    /**
     * @testdox A session id can be generated
     * @group framework
     */
    public function testCreateSid() : void
    {
        self::assertMatchesRegularExpression('/^s\-[a-z0-9]+/', $this->sh->create_sid());
    }

    /**
     * @testdox The session path can be accessed
     * @group framework
     */
    public function testSessionPath() : void
    {
        self::assertTrue($this->sh->open(__DIR__ . '/test', ''));
    }

    /**
     * @testdox A invalid session path cannot be accessed
     * @group framework
     */
    public function testInvalidSessionPath() : void
    {
        self::assertFalse($this->sh->open(__DIR__ . '/invalid', ''));
    }

    /**
     * @testdox A session can be closed
     * @group framework
     */
    public function testSessionClose() : void
    {
        self::assertTrue($this->sh->close());
    }

    /**
     * @testdox A valid session id can store and return data
     * @group framework
     */
    public function testSessionInputOutput() : void
    {
        $id = $this->sh->create_sid();
        self::assertTrue($this->sh->write($id, 'test'));
        self::assertEquals('test', $this->sh->read($id));
    }

    /**
     * @testdox A invalid session id doesn't return any data
     * @group framework
     */
    public function testReadInvalidSessionId() : void
    {
        self::assertEquals('', $this->sh->read('invalid'));
    }

    /**
     * @testdox A session can be destroyed
     * @group framework
     */
    public function testSessionDestroy() : void
    {
        $id = $this->sh->create_sid();
        self::assertTrue($this->sh->write($id, 'test'));
        self::assertEquals('test', $this->sh->read($id));

        $this->sh->destroy($id);
        self::assertEquals('', $this->sh->read($id));
    }

    /**
     * @testdox Sessions can be removed based on a timeout
     * @group framework
     */
    public function testSessionTimeoutDestroy() : void
    {
        $id = $this->sh->create_sid();
        self::assertTrue($this->sh->write($id, 'test'));
        self::assertEquals('test', $this->sh->read($id));

        \sleep(2);

        $this->sh->gc(0);
        self::assertEquals('', $this->sh->read($id));
    }
}
