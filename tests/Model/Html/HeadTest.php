<?php
/**
 * Orange Management
 *
 * PHP Version 7.1
 *
 * @package    TBD
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */

namespace phpOMS\tests\Model\Html;

use phpOMS\Model\Html\Head;

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
}
