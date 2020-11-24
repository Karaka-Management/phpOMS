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

use phpOMS\Asset\AssetType;
use phpOMS\Model\Html\Head;

/**
 * @testdox phpOMS\tests\Model\Html\HeadTest: Html head
 *
 * @internal
 */
class HeadTest extends \PHPUnit\Framework\TestCase
{
    protected Head $head;

    protected function setUp() : void
    {
        $this->head = new Head();
    }

    /**
     * @testdox The head has the expected default values after initialization
     * @covers phpOMS\Model\Html\Head
     * @group framework
     */
    public function testDefault() : void
    {
        self::assertInstanceOf('\phpOMS\Model\Html\Meta', $this->head->meta);
        self::assertEquals('', $this->head->title);
        self::assertEquals('en', $this->head->getLanguage());
        self::assertEquals([], $this->head->getStyleAll());
        self::assertEquals([], $this->head->getScriptAll());
        self::assertEquals('', $this->head->renderStyle());
        self::assertEquals('', $this->head->renderScript());
        self::assertEquals('', $this->head->renderAssets());
        self::assertEquals('', $this->head->renderAssetsLate());
        self::assertEquals('<meta name="generator" content="Orange Management">', $this->head->render());
    }

    /**
     * @testdox The title can be set and returned
     * @covers phpOMS\Model\Html\Head
     * @group framework
     */
    public function testTitleInputOutput() : void
    {
        $this->head->title = 'my title';
        self::assertEquals('my title', $this->head->title);
    }

    /**
     * @testdox The style can be set and returned
     * @covers phpOMS\Model\Html\Head
     * @group framework
     */
    public function testStyleInputOutput() : void
    {
        $this->head->setStyle('base', '#test .class { color: #000; }');
        self::assertEquals(['base' => '#test .class { color: #000; }'], $this->head->getStyleAll());
    }

    /**
     * @testdox The script can be set and returned
     * @covers phpOMS\Model\Html\Head
     * @group framework
     */
    public function testScriptInputOutput() : void
    {
        $this->head->setScript('key', 'console.log("msg");');
        self::assertEquals(['key' => 'console.log("msg");'], $this->head->getScriptAll());
    }

    /**
     * @testdox The language can be set and returned
     * @covers phpOMS\Model\Html\Head
     * @group framework
     */
    public function testLanguageInputOutput() : void
    {
        $this->head->setLanguage('en');
        self::assertEquals('en', $this->head->getLanguage());
    }

    /**
     * @testdox The assets can be set and rendered
     * @covers phpOMS\Model\Html\Head
     * @group framework
     */
    public function testAssetRender() : void
    {
        $this->head->addAsset(AssetType::CSS, '/path/styles.css');
        $this->head->addAsset(AssetType::JS, '/path/logic.js');

        $this->head->setStyle('base', '#test .class { color: #000; }');
        $this->head->setScript('key', 'console.log("msg");');

        self::assertEquals(
            '<meta name="generator" content="Orange Management">'
            . '<link rel="stylesheet" type="text/css" href="/path/styles.css">'
            . '<script src="/path/logic.js"></script>'
            . '<style>#test .class { color: #000; }</style>'
            . '<script>console.log("msg");</script>',
            $this->head->render()
        );
    }

    /**
     * @testdox The assets can be set and rendered at the end of the document
     * @covers phpOMS\Model\Html\Head
     * @group framework
     */
    public function testAssetLateRender() : void
    {
        $this->head->addAsset(AssetType::JSLATE, '/path/late.js');
        self::assertEquals('<script src="/path/late.js"></script>', $this->head->renderAssetsLate());
    }

    /**
     * @testdox The assets can be set and rendered with attributes
     * @covers phpOMS\Model\Html\Head
     * @group framework
     */
    public function testAssetRenderWithAttribute() : void
    {
        $this->head->addAsset(AssetType::JS, '/path/late.js', ['testkey' => 'testvalue']);
        self::assertEquals('<script src="/path/late.js" testkey="testvalue"></script>', $this->head->renderAssets());
    }

    /**
     * @testdox The assets can be set and rendered at the end of the document with attributes
     * @covers phpOMS\Model\Html\Head
     * @group framework
     */
    public function testAssetLateRenderWithAttribute() : void
    {
        $this->head->addAsset(AssetType::JSLATE, '/path/late.js', ['testkey' => 'testvalue']);
        self::assertEquals('<script src="/path/late.js" testkey="testvalue"></script>', $this->head->renderAssetsLate());
    }
}
