<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.2
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\tests\Utils\Git;

use phpOMS\Utils\Git\Author;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Utils\Git\Author::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Utils\Git\AuthorTest: Git author')]
final class AuthorTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The author has the expected default values after initialization')]
    public function testDefault() : void
    {
        $author = new Author();
        self::assertEquals('', $author->name);
        self::assertEquals('', $author->getEmail());
        self::assertEquals(0, $author->getCommitCount());
        self::assertEquals(0, $author->getAdditionCount());
        self::assertEquals(0, $author->getRemovalCount());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The author name and email can be set during initialization and returned')]
    public function testConstructInputOutput() : void
    {
        $author = new Author('test', 'email');
        self::assertEquals('test', $author->name);
        self::assertEquals('email', $author->getEmail());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The commit count can be set and returned')]
    public function testCommitCountInputOutput() : void
    {
        $author = new Author('test', 'email');

        $author->setCommitCount(1);
        self::assertEquals(1, $author->getCommitCount());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The addition count can be set and returned')]
    public function testAdditionCountInputOutput() : void
    {
        $author = new Author('test', 'email');

        $author->setAdditionCount(2);
        self::assertEquals(2, $author->getAdditionCount());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The removal count can be set and returned')]
    public function testRemovalCountInputOutput() : void
    {
        $author = new Author('test', 'email');

        $author->setRemovalCount(3);
        self::assertEquals(3, $author->getRemovalCount());
    }
}
