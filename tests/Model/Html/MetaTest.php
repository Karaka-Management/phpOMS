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

use phpOMS\Model\Html\Meta;

class MetaTest extends \PHPUnit\Framework\TestCase
{
    public function testDefault()
    {
        $meta = new Meta();
        self::assertEquals('', $meta->getDescription());
        self::assertEquals('', $meta->getCharset());
        self::assertEquals('', $meta->getAuthor());
        self::assertEquals([], $meta->getKeywords());
        self::assertEquals('<meta name="generator" content="Orange Management">', $meta->render());
    }
}
