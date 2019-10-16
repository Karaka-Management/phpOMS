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

namespace phpOMS\tests\Utils\Git;

use phpOMS\Utils\Git\Tag;

/**
 * @internal
 */
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
