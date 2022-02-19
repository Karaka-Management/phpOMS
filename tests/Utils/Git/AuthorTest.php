<?php
/**
 * Karaka
 *
 * PHP Version 8.0
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://karaka.app
 */
declare(strict_types=1);

namespace phpOMS\tests\Utils\Git;

use phpOMS\Utils\Git\Author;

/**
 * @testdox phpOMS\tests\Utils\Git\AuthorTest: Git author
 *
 * @internal
 */
final class AuthorTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox The author has the expected default values after initialization
     * @covers phpOMS\Utils\Git\Author
     * @group framework
     */
    public function testDefault() : void
    {
        $author = new Author();
        self::assertEquals('', $author->name);
        self::assertEquals('', $author->getEmail());
        self::assertEquals(0, $author->getCommitCount());
        self::assertEquals(0, $author->getAdditionCount());
        self::assertEquals(0, $author->getRemovalCount());
    }

    /**
     * @testdox The author name and email can be set during initialization and returned
     * @covers phpOMS\Utils\Git\Author
     * @group framework
     */
    public function testConstructInputOutput() : void
    {
        $author = new Author('test', 'email');
        self::assertEquals('test', $author->name);
        self::assertEquals('email', $author->getEmail());
    }

    /**
     * @testdox The commit count can be set and returned
     * @covers phpOMS\Utils\Git\Author
     * @group framework
     */
    public function testCommitCountInputOutput() : void
    {
        $author = new Author('test', 'email');

        $author->setCommitCount(1);
        self::assertEquals(1, $author->getCommitCount());
    }

    /**
     * @testdox The addition count can be set and returned
     * @covers phpOMS\Utils\Git\Author
     * @group framework
     */
    public function testAdditionCountInputOutput() : void
    {
        $author = new Author('test', 'email');

        $author->setAdditionCount(2);
        self::assertEquals(2, $author->getAdditionCount());
    }

    /**
     * @testdox The removal count can be set and returned
     * @covers phpOMS\Utils\Git\Author
     * @group framework
     */
    public function testRemovalCountInputOutput() : void
    {
        $author = new Author('test', 'email');

        $author->setRemovalCount(3);
        self::assertEquals(3, $author->getRemovalCount());
    }
}
