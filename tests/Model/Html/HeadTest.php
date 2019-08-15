<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package    tests
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       https://orange-management.org
 */
 declare(strict_types=1);

namespace phpOMS\tests\Model\Html;

use phpOMS\Asset\AssetType;
use phpOMS\Model\Html\Head;

/**
 * @internal
 */
class HeadTest extends \PHPUnit\Framework\TestCase
{
    public function testDefault() : void
    {
        $head = new Head();
        self::assertInstanceOf('\phpOMS\Model\Html\Meta', $head->getMeta());
        self::assertEquals('', $head->getTitle());
        self::assertEquals('en', $head->getLanguage());
        self::assertEquals([], $head->getStyleAll());
        self::assertEquals([], $head->getScriptAll());
        self::assertEquals('', $head->renderStyle());
        self::assertEquals('', $head->renderScript());
        self::assertEquals('', $head->renderAssets());
        self::assertEquals('', $head->renderAssetsLate());
        self::assertEquals('<meta name="generator" content="Orange Management">', $head->render());
    }

    public function testSetGet() : void
    {
        $head = new Head();

        $head->setTitle('my title');
        self::assertEquals('my title', $head->getTitle());

        $head->addAsset(AssetType::CSS, '/path/styles.css');
        $head->addAsset(AssetType::JS, '/path/logic.js');
        $head->addAsset(AssetType::JSLATE, '/path/late.js');

        $head->setStyle('base', '#test .class { color: #000; }');
        self::assertEquals(['base' => '#test .class { color: #000; }'], $head->getStyleAll());

        $head->setScript('key', 'console.log("msg");');
        self::assertEquals(['key' => 'console.log("msg");'], $head->getScriptAll());

        $head->setLanguage('en');
        self::assertEquals('en', $head->getLanguage());

        self::assertEquals(
            '<meta name="generator" content="Orange Management">'
            . '<link rel="stylesheet" type="text/css" href="/path/styles.css">'
            . '<script src="/path/logic.js"></script>'
            . '<style>#test .class { color: #000; }</style>'
            . '<script>console.log("msg");</script>',
            $head->render()
        );

        self::assertEquals('<script src="/path/late.js"></script>', $head->renderAssetsLate());
    }

    public function testAssetWithAttribute() : void
    {
        $head = new Head();

        $head->addAsset(AssetType::JSLATE, '/path/late.js', ['testkey' => 'testvalue']);
        self::assertEquals('<script src="/path/late.js" testkey="testvalue"></script>', $head->renderAssetsLate());

        $head->addAsset(AssetType::JS, '/path/late.js', ['testkey' => 'testvalue']);
        self::assertEquals('<script src="/path/late.js" testkey="testvalue"></script>', $head->renderAssets());
    }
}
