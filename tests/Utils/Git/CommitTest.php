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

use phpOMS\Utils\Git\Author;
use phpOMS\Utils\Git\Branch;
use phpOMS\Utils\Git\Commit;
use phpOMS\Utils\Git\Repository;
use phpOMS\Utils\Git\Tag;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Utils\Git\Commit::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Utils\Git\CommitTest: Git commit')]
final class CommitTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The commit has the expected default values after initialization')]
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

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A file can be added and returned')]
    public function testFileInputOutput() : void
    {
        $commit = new Commit();

        self::assertTrue($commit->addFile('/some/file/path'));
        self::assertTrue($commit->addFile('/some/file/path2'));
        self::assertEquals([
            '/some/file/path'  => [],
            '/some/file/path2' => [],
        ], $commit->getFiles());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A file can only be added one time')]
    public function testInvalidOverwrite() : void
    {
        $commit = new Commit();

        self::assertTrue($commit->addFile('/some/file/path'));
        self::assertFalse($commit->addFile('/some/file/path'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A file can be removed')]
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

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A none-existing file cannot be removed')]
    public function testInvalidRemoveFile() : void
    {
        $commit = new Commit();

        self::assertFalse($commit->removeFile('/some/file/path3'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A change can be added and returned')]
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

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Adding the same change throws a Exception')]
    public function testDuplicateLineChange() : void
    {
        $this->expectException(\Exception::class);

        $commit = new Commit();
        $commit->addChanges(__DIR__ . '/CommitTest.php', 1, '<?php', 'test');
        $commit->addChanges(__DIR__ . '/CommitTest.php', 1, '<?php', 'test');
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A commit message can be set and returned')]
    public function testMessageInputOutput() : void
    {
        $commit = new Commit();

        $commit->setMessage('My Message');
        self::assertEquals('My Message', $commit->getMessage());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The author can be set and returned')]
    public function testAuthorInputOutput() : void
    {
        $commit = new Commit();

        $commit->setAuthor(new Author('Orange'));
        self::assertEquals('Orange', $commit->getAuthor()->name);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The branch can be set and returned')]
    public function testBranchInputOutput() : void
    {
        $commit = new Commit();

        $commit->setBranch(new Branch('develop'));
        self::assertEquals('develop', $commit->getBranch()->name);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The tag can be set and returned')]
    public function testTagInputOutput() : void
    {
        $commit = new Commit();

        $commit->setTag(new Tag('1.0.0'));
        self::assertEquals('1.0.0', $commit->getTag()->name);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The date can be set and returned')]
    public function testDateInputOutput() : void
    {
        $commit = new Commit();

        $commit->setDate($date = new \DateTime('now'));
        self::assertEquals($date->format('Y-m-d'), $commit->getDate()->format('Y-m-d'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The repository can be set and returned')]
    public function testRepositoryInputOutput() : void
    {
        $commit = new Commit();

        $commit->setRepository(new Repository(\realpath(__DIR__ . '/../../../')));
        self::assertEquals(\realpath(__DIR__ . '/../../../'), $commit->getRepository()->getPath());
    }
}
