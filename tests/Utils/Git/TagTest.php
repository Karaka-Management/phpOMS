<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://karaka.app
 */
declare(strict_types=1);

namespace phpOMS\tests\Utils\Git;

use phpOMS\Utils\Git\Tag;

/**
 * @testdox phpOMS\tests\Utils\Git\TagTest: Git tag
 *
 * @internal
 */
final class TagTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox The tag has the expected default values after initialization
     * @covers phpOMS\Utils\Git\Tag
     * @group framework
     */
    public function testDefault() : void
    {
        $tag = new Tag();
        self::assertEquals('', $tag->getMessage());
        self::assertEquals('', $tag->getName());
    }

    /**
     * @testdox The tag name can be set during initialization and returned
     * @covers phpOMS\Utils\Git\Tag
     * @group framework
     */
    public function testConstructorInputOutput() : void
    {
        $tag = new Tag('test');
        self::assertEquals('test', $tag->getName());
    }

    /**
     * @testdox The message can be set and returned
     * @covers phpOMS\Utils\Git\Tag
     * @group framework
     */
    public function testMessageInputOutput() : void
    {
        $tag = new Tag('test');

        $tag->setMessage('msg');
        self::assertEquals('msg', $tag->getMessage());
    }
}
