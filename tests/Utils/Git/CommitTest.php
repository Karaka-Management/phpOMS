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

use phpOMS\Utils\Git\Author;
use phpOMS\Utils\Git\Branch;
use phpOMS\Utils\Git\Commit;
use phpOMS\Utils\Git\Repository;
use phpOMS\Utils\Git\Tag;

/**
 * @testdox phpOMS\tests\Utils\Git\CommitTest: Git commit
 *
 * @internal
 */
class CommitTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox The commit has the expected default values after initialization
     * @covers phpOMS\Utils\Git\Commit
     * @group framework
     */
    public function testDefault() : void
    {
        $commit = new Commit();
        self::assertEquals('', $commit->getId());
        self::assertEquals('', $commit->getMessage());
        self::assertEquals([], $commit->getFiles());
        self::assertInstanceOf('\phpOMS\Utils\Git\Author', $commit->getAuthor());
        self::assertInstanceOf('\phpOMS\Utils\Git\Branch', $commit->getBranch());
        self::assertInstanceOf('\phpOMS\Utils\Git\Tag', $commit->getTag());
        self::assertInstanceOf('\phpOMS\Utils\Git\Repository', $commit->getRepository());
        self::assertInstanceOf('\DateTime', $commit->getDate());
    }

    /**
     * @testdox A file can be added and returned
     * @covers phpOMS\Utils\Git\Commit
     * @group framework
     */
    public function testFileInputOutput() : void
    {
        $commit = new Commit();

        self::assertTrue($commit->addFile('/some/file/path'));
        self::assertTrue($commit->addFile('/some/file/path2'));
        self::assertEquals([
            '/some/file/path' => [],
            '/some/file/path2' => [],
        ], $commit->getFiles());
    }

    /**
     * @testdox A file can only be added one time
     * @covers phpOMS\Utils\Git\Commit
     * @group framework
     */
    public function testInvalidOverwrite() : void
    {
        $commit = new Commit();

        self::assertTrue($commit->addFile('/some/file/path'));
        self::assertFalse($commit->addFile('/some/file/path'));
    }

    /**
     * @testdox A file can be removed
     * @covers phpOMS\Utils\Git\Commit
     * @group framework
     */
    public function testRemoveFile() : void
    {
        $commit = new Commit();

        self::assertTrue($commit->addFile('/some/file/path'));
        self::assertTrue($commit->addFile('/some/file/path2'));

        self::assertTrue($commit->removeFile('/some/file/path'));
        self::assertEquals([
            '/some/file/path2' => [],
        ], $commit->getFiles());
    }

    /**
     * @testdox A none-existing file cannot be removed
     * @covers phpOMS\Utils\Git\Commit
     * @group framework
     */
    public function testInvalidRemoveFile() : void
    {
        $commit = new Commit();

        self::assertFalse($commit->removeFile('/some/file/path3'));
    }

    /**
     * @testdox A change can be added and returned
     * @covers phpOMS\Utils\Git\Commit
     * @group framework
     */
    public function testChangeInputOutput() : void
    {
        $commit = new Commit();

        $commit->addChanges(__DIR__ . '/CommitTest.php', 1, '<?php', 'test');
        self::assertEquals(
            [
                __DIR__ . '/CommitTest.php' => [
                    1 => [
                        'old' => '<?php',
                        'new' => 'test',
                    ],
                ],
            ], $commit->getFiles());
    }

    /**
     * @testdox Adding the same change throws a Exception
     * @covers phpOMS\Utils\Git\Commit
     * @group framework
     */
    public function testDuplicateLineChange() : void
    {
        self::expectException(\Exception::class);

        $commit = new Commit();
        $commit->addChanges(__DIR__ . '/CommitTest.php', 1, '<?php', 'test');
        $commit->addChanges(__DIR__ . '/CommitTest.php', 1, '<?php', 'test');
    }

    /**
     * @testdox A commit message can be set and returned
     * @covers phpOMS\Utils\Git\Commit
     * @group framework
     */
    public function testMessageInputOutput() : void
    {
        $commit = new Commit();

        $commit->setMessage('My Message');
        self::assertEquals('My Message', $commit->getMessage());
    }

    /**
     * @testdox The author can be set and returned
     * @covers phpOMS\Utils\Git\Commit
     * @group framework
     */
    public function testAuthorInputOutput() : void
    {
        $commit = new Commit();

        $commit->setAuthor(new Author('Orange'));
        self::assertEquals('Orange', $commit->getAuthor()->getName());
    }

    /**
     * @testdox The branch can be set and returned
     * @covers phpOMS\Utils\Git\Commit
     * @group framework
     */
    public function testBranchInputOutput() : void
    {
        $commit = new Commit();

        $commit->setBranch(new Branch('develop'));
        self::assertEquals('develop', $commit->getBranch()->getName());
    }

    /**
     * @testdox The tag can be set and returned
     * @covers phpOMS\Utils\Git\Commit
     * @group framework
     */
    public function testTagInputOutput() : void
    {
        $commit = new Commit();

        $commit->setTag(new Tag('1.0.0'));
        self::assertEquals('1.0.0', $commit->getTag()->getName());
    }

    /**
     * @testdox The date can be set and returned
     * @covers phpOMS\Utils\Git\Commit
     * @group framework
     */
    public function testDateInputOutput() : void
    {
        $commit = new Commit();

        $commit->setDate($date = new \DateTime('now'));
        self::assertEquals($date->format('Y-m-d'), $commit->getDate()->format('Y-m-d'));
    }

    /**
     * @testdox The repository can be set and returned
     * @covers phpOMS\Utils\Git\Commit
     * @group framework
     */
    public function testRepositoryInputOutput() : void
    {
        $commit = new Commit();

        $commit->setRepository(new Repository(\realpath(__DIR__ . '/../../../')));
        self::assertEquals(\realpath(__DIR__ . '/../../../'), $commit->getRepository()->getPath());
    }
}
