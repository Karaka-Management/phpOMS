<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    TBD
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */

namespace phpOMS\tests\Model\Html;

use phpOMS\Model\Html\Head;
use phpOMS\Asset\AssetType;

class HeadTest extends \PHPUnit\Framework\TestCase
{
    public function testDefault()
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

    public function testSetGet()
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
}
