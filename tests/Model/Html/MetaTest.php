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

namespace phpOMS\tests\Model\Html;

use phpOMS\Model\Html\Meta;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Model\Html\Meta::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Model\Html\MetaTest: Html meta data')]
final class MetaTest extends \PHPUnit\Framework\TestCase
{
    protected Meta $meta;

    /**
     * {@inheritdoc}
     */
    protected function setUp() : void
    {
        $this->meta = new Meta();
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The meta data has the expected default values after initialization')]
    public function testDefault() : void
    {
        self::assertEquals('', $this->meta->description);
        self::assertEquals('', $this->meta->getCharset());
        self::assertEquals('', $this->meta->author);
        self::assertEquals('', $this->meta->getName(''));
        self::assertEquals('', $this->meta->getProperty(''));
        self::assertEquals('', $this->meta->getItemprop(''));
        self::assertEquals([], $this->meta->getKeywords());
        self::assertEquals('<meta name="generator" content="Karaka">', $this->meta->render());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A keyword can be added and returned')]
    public function testKeywordInputOutput() : void
    {
        $this->meta->addKeyword('orange');
        self::assertEquals(['orange'], $this->meta->getKeywords());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The author can be set and returned')]
    public function testAuthorInputOutput() : void
    {
        $this->meta->author = 'oms';
        self::assertEquals('oms', $this->meta->author);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The charset can be set and returned')]
    public function testCharsetInputOutput() : void
    {
        $this->meta->setCharset('utf-8');
        self::assertEquals('utf-8', $this->meta->getCharset());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The description can be set and returned')]
    public function testDescriptionInputOutput() : void
    {
        $this->meta->description = 'some description';
        self::assertEquals('some description', $this->meta->description);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A property can be set and returned')]
    public function testPropertyInputOutput() : void
    {
        $this->meta->setProperty('property', 'test property');
        self::assertEquals('test property', $this->meta->getProperty('property'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A itemprop can be set and returned')]
    public function testItempropInputOutput() : void
    {
        $this->meta->setItemprop('itemprop', 'test itemprop');
        self::assertEquals('test itemprop', $this->meta->getItemprop('itemprop'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A name can be set and returned')]
    public function testNameInputOutput() : void
    {
        $this->meta->setName('title', 'test title');
        self::assertEquals('test title', $this->meta->getName('title'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The meta data can be rendered')]
    public function testRender() : void
    {
        $this->meta->addKeyword('orange');
        $this->meta->author = 'oms';
        $this->meta->setCharset('utf-8');
        $this->meta->description = 'some description';
        $this->meta->setProperty('og:title', 'TestProperty');
        $this->meta->setItemprop('title', 'TestItemprop');
        $this->meta->setName('title', 'TestName');

        self::assertEquals(
            '<meta name="keywords" content="orange">'
            . '<meta name="author" content="oms">'
            . '<meta name="description" content="some description">'
            . '<meta charset="utf-8">'
            . '<meta name="generator" content="Karaka">'
            . '<meta property="og:title" content="TestProperty">'
            . '<meta itemprop="title" content="TestItemprop">'
            . '<meta name="title" content="TestName">',
            $this->meta->render()
        );
    }
}
