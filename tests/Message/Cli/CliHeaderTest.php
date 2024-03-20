<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\tests\Message\Cli;

use phpOMS\Localization\Localization;
use phpOMS\Message\Cli\CliHeader;
use phpOMS\Message\Http\RequestStatusCode;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Message\Cli\CliHeader::class)]
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Message\Cli\CliHeader::class)]
final class CliHeaderTest extends \PHPUnit\Framework\TestCase
{
    private CliHeader $header;

    /**
     * {@inheritdoc}
     */
    protected function setUp() : void
    {
        $this->header = new CliHeader();
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testDefaults() : void
    {
        self::assertFalse($this->header->isLocked());
        self::assertEquals(0, $this->header->status);
        self::assertEquals('1.0', $this->header->getProtocolVersion());
        self::assertEquals('', $this->header->getReasonPhrase());
        self::assertEquals([], $this->header->get('key'));
        self::assertEquals([], $this->header->get());
        self::assertFalse($this->header->has('key'));
        self::assertInstanceOf(Localization::class, $this->header->l11n);
        self::assertEquals(0, $this->header->account);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testValueInputOutput() : void
    {
        self::assertTrue($this->header->set('key', 'header'));
        self::assertEquals(['header'], $this->header->get('key'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testHasKey() : void
    {
        self::assertTrue($this->header->set('key', 'header'));
        self::assertTrue($this->header->has('key'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testInvalidOverwrite() : void
    {
        self::assertTrue($this->header->set('key', 'header'));
        self::assertFalse($this->header->set('key', 'header2'));
        self::assertEquals(['header'], $this->header->get('key'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testOverwrite() : void
    {
        self::assertTrue($this->header->set('key', 'header'));
        self::assertTrue($this->header->set('key', 'header3', true));
        self::assertEquals(['header3'], $this->header->get('key'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testRemove() : void
    {
        self::assertTrue($this->header->set('key', 'header'));
        self::assertTrue($this->header->remove('key'));
        self::assertFalse($this->header->has('key'));
        self::assertFalse($this->header->remove('key'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The header can generate default http headers based on status codes')]
    public function testHeaderGeneration() : void
    {
        self::markTestIncomplete();
        $this->header->generate(RequestStatusCode::R_500);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testAccount() : void
    {
        $this->header->account = 2;
        self::assertEquals(2, $this->header->account);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testLockedHeaderSet() : void
    {
        $this->header->lock();
        self::assertTrue($this->header->isLocked());
        self::assertFalse($this->header->set('key', 'value'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testLockedHeaderRemove() : void
    {
        $this->header->lock();
        self::assertTrue($this->header->isLocked());
        self::assertFalse($this->header->remove('key'));
    }
}
