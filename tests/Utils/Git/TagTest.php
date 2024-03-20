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

namespace phpOMS\tests\Utils\Git;

use phpOMS\Utils\Git\Tag;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Utils\Git\Tag::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Utils\Git\TagTest: Git tag')]
final class TagTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The tag has the expected default values after initialization')]
    public function testDefault() : void
    {
        $tag = new Tag();
        self::assertEquals('', $tag->getMessage());
        self::assertEquals('', $tag->getName());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The tag name can be set during initialization and returned')]
    public function testConstructorInputOutput() : void
    {
        $tag = new Tag('test');
        self::assertEquals('test', $tag->getName());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The message can be set and returned')]
    public function testMessageInputOutput() : void
    {
        $tag = new Tag('test');

        $tag->setMessage('msg');
        self::assertEquals('msg', $tag->getMessage());
    }
}
