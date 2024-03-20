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

namespace phpOMS\tests\Model\Html;

use phpOMS\Asset\AssetType;
use phpOMS\Model\Html\Head;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Model\Html\Head::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Model\Html\HeadTest: Html head')]
final class HeadTest extends \PHPUnit\Framework\TestCase
{
    protected Head $head;

    /**
     * {@inheritdoc}
     */
    protected function setUp() : void
    {
        $this->head = new Head();
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The head has the expected default values after initialization')]
    public function testDefault() : void
    {
        self::assertInstanceOf('\phpOMS\Model\Html\Meta', $this->head->meta);
        self::assertEquals('', $this->head->title);
        self::assertEquals('en', $this->head->language);
        self::assertEquals([], $this->head->getStyleAll());
        self::assertEquals([], $this->head->getScriptAll());
        self::assertEquals('', $this->head->renderStyle());
        self::assertEquals('', $this->head->renderScript());
        self::assertEquals('', $this->head->renderAssets());
        self::assertEquals('', $this->head->renderAssetsLate());
        self::assertEquals('<meta name="generator" content="Karaka">', $this->head->render());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The title can be set and returned')]
    public function testTitleInputOutput() : void
    {
        $this->head->title = 'my title';
        self::assertEquals('my title', $this->head->title);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The style can be set and returned')]
    public function testStyleInputOutput() : void
    {
        $this->head->setStyle('base', '#test .class { color: #000; }');
        self::assertEquals(['base' => '#test .class { color: #000; }'], $this->head->getStyleAll());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The script can be set and returned')]
    public function testScriptInputOutput() : void
    {
        $this->head->setScript('key', 'console.log("msg");');
        self::assertEquals(['key' => 'console.log("msg");'], $this->head->getScriptAll());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The assets can be set and rendered')]
    public function testAssetRender() : void
    {
        $this->head->addAsset(AssetType::CSS, '/path/styles.css');
        $this->head->addAsset(AssetType::JS, '/path/logic.js');

        $this->head->setStyle('base', '#test .class { color: #000; }');
        $this->head->setScript('key', 'console.log("msg");');

        self::assertEquals(
            '<meta name="generator" content="Karaka">'
            . '<link rel="stylesheet" type="text/css" href="/path/styles.css">'
            . '<script src="/path/logic.js"></script>'
            . '<style>#test .class { color: #000; }</style>'
            . '<script>console.log("msg");</script>',
            $this->head->render()
        );
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The assets can be set and rendered at the end of the document')]
    public function testAssetLateRender() : void
    {
        $this->head->addAsset(AssetType::JSLATE, '/path/late.js');
        self::assertEquals('<script src="/path/late.js"></script>', $this->head->renderAssetsLate());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The assets can be set and rendered with attributes')]
    public function testAssetRenderWithAttribute() : void
    {
        $this->head->addAsset(AssetType::JS, '/path/late.js', ['testkey' => 'testvalue']);
        self::assertEquals('<script src="/path/late.js" testkey="testvalue"></script>', $this->head->renderAssets());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The assets can be set and rendered at the end of the document with attributes')]
    public function testAssetLateRenderWithAttribute() : void
    {
        $this->head->addAsset(AssetType::JSLATE, '/path/late.js', ['testkey' => 'testvalue']);
        self::assertEquals('<script src="/path/late.js" testkey="testvalue"></script>', $this->head->renderAssetsLate());
    }
}
