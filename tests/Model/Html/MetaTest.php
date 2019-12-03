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

namespace phpOMS\tests\Model\Html;

use phpOMS\Model\Html\Meta;

/**
 * @testdox phpOMS\tests\Model\Html\MetaTest: Html meta data
 *
 * @internal
 */
class MetaTest extends \PHPUnit\Framework\TestCase
{
    protected Meta $meta;

    protected function setUp() : void
    {
        $this->meta = new Meta();
    }

    /**
     * @testdox The meta data has the expected default values after initialization
     * @covers phpOMS\Model\Html\Meta
     * @group framework
     */
    public function testDefault() : void
    {
        self::assertEquals('', $this->meta->getDescription());
        self::assertEquals('', $this->meta->getCharset());
        self::assertEquals('', $this->meta->getAuthor());
        self::assertEquals('', $this->meta->getName(''));
        self::assertEquals('', $this->meta->getProperty(''));
        self::assertEquals('', $this->meta->getItemprop(''));
        self::assertEquals([], $this->meta->getKeywords());
        self::assertEquals('<meta name="generator" content="Orange Management">', $this->meta->render());
    }

    /**
     * @testdox A keyword can be added and returned
     * @covers phpOMS\Model\Html\Meta
     * @group framework
     */
    public function testKeywordInputOutput() : void
    {
        $this->meta->addKeyword('orange');
        self::assertEquals(['orange'], $this->meta->getKeywords());
    }

    /**
     * @testdox The author can be set and returned
     * @covers phpOMS\Model\Html\Meta
     * @group framework
     */
    public function testAuthorInputOutput() : void
    {
        $this->meta->setAuthor('oms');
        self::assertEquals('oms', $this->meta->getAuthor());
    }

    /**
     * @testdox The charset can be set and returned
     * @covers phpOMS\Model\Html\Meta
     * @group framework
     */
    public function testCharsetInputOutput() : void
    {
        $this->meta->setCharset('utf-8');
        self::assertEquals('utf-8', $this->meta->getCharset());
    }

    /**
     * @testdox The description can be set and returned
     * @covers phpOMS\Model\Html\Meta
     * @group framework
     */
    public function testDescriptionInputOutput() : void
    {
        $this->meta->setDescription('some description');
        self::assertEquals('some description', $this->meta->getDescription());
    }

    /**
     * @testdox A property can be set and returned
     * @covers phpOMS\Model\Html\Meta
     * @group framework
     */
    public function testPropertyInputOutput() : void
    {
        $this->meta->setProperty('property', 'test property');
        self::assertEquals('test property', $this->meta->getProperty('property'));
    }

    /**
     * @testdox A itemprop can be set and returned
     * @covers phpOMS\Model\Html\Meta
     * @group framework
     */
    public function testItempropInputOutput() : void
    {
        $this->meta->setItemprop('itemprop', 'test itemprop');
        self::assertEquals('test itemprop', $this->meta->getItemprop('itemprop'));
    }

    /**
     * @testdox A name can be set and returned
     * @covers phpOMS\Model\Html\Meta
     * @group framework
     */
    public function testNameInputOutput() : void
    {
        $this->meta->setName('title', 'test title');
        self::assertEquals('test title', $this->meta->getName('title'));
    }

    /**
     * @testdox The meta data can be rendered
     * @covers phpOMS\Model\Html\Meta
     * @group framework
     */
    public function testRender() : void
    {
        $this->meta->addKeyword('orange');
        $this->meta->setAuthor('oms');
        $this->meta->setCharset('utf-8');
        $this->meta->setDescription('some description');
        $this->meta->setProperty('og:title', 'TestProperty');
        $this->meta->setItemprop('title', 'TestItemprop');
        $this->meta->setName('title', 'TestName');

        self::assertEquals(
            '<meta name="keywords" content="orange">'
            . '<meta name="author" content="oms">'
            . '<meta name="description" content="some description">'
            . '<meta charset="utf-8">'
            . '<meta name="generator" content="Orange Management">'
            . '<meta property="og:title" content="TestProperty">'
            . '<meta itemprop="title" content="TestItemprop">'
            . '<meta name="title" content="TestName">',
            $this->meta->render()
        );
    }
}
