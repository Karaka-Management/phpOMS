<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    tests
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */

namespace phpOMS\tests\Utils\Git;

use phpOMS\Utils\Git\Tag;

class TagTest extends \PHPUnit\Framework\TestCase
{
    public function testDefault() : void
    {
        $tag = new Tag();
        self::assertEquals('', $tag->getMessage());
        self::assertEquals('', $tag->getName());
    }

    public function testGetSet() : void
    {
        $tag = new Tag('test');
        self::assertEquals('test', $tag->getName());

        $tag->setMessage('msg');
        self::assertEquals('msg', $tag->getMessage());
    }
}
