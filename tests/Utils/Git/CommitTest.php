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

use phpOMS\Utils\Git\Author;
use phpOMS\Utils\Git\Branch;
use phpOMS\Utils\Git\Commit;
use phpOMS\Utils\Git\Repository;
use phpOMS\Utils\Git\Tag;

class CommitTest extends \PHPUnit\Framework\TestCase
{
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

    public function testAddRemoveFile() : void
    {
        $commit = new Commit();

        self::assertTrue($commit->addFile('/some/file/path'));
        self::assertFalse($commit->addFile('/some/file/path'));
        self::assertTrue($commit->addFile('/some/file/path2'));
        self::assertEquals([
            '/some/file/path' => [],
            '/some/file/path2' => []
        ], $commit->getFiles());

        self::assertFalse($commit->removeFile('/some/file/path3'));
        self::assertTrue($commit->removeFile('/some/file/path'));
        self::assertEquals([
            '/some/file/path2' => []
        ], $commit->getFiles());
    }

    public function testChanges() : void
    {
        $commit = new Commit();

        $commit->addChanges(__DIR__ . '/CommitTest.php', 1, '<?php', 'test');
        self::assertEquals(
            [
                __DIR__ . '/CommitTest.php' => [
                    1 => [
                        'old' => '<?php',
                        'new' => 'test'
                    ]
                ]
            ], $commit->getFiles());
    }

    /**
     * @expectedException \Exception
     */
    public function testDuplicateLineChange() : void
    {
        $commit = new Commit();
        $commit->addChanges(__DIR__ . '/CommitTest.php', 1, '<?php', 'test');
        $commit->addChanges(__DIR__ . '/CommitTest.php', 1, '<?php', 'test');
    }

    public function testMessage() : void
    {
        $commit = new Commit();

        $commit->setMessage('My Message');
        self::assertEquals('My Message', $commit->getMessage());
    }

    public function testAuthor() : void
    {
        $commit = new Commit();

        $commit->setAuthor(new Author('Orange'));
        self::assertEquals('Orange', $commit->getAuthor()->getName());
    }

    public function testBranch() : void
    {
        $commit = new Commit();

        $commit->setBranch(new Branch('develop'));
        self::assertEquals('develop', $commit->getBranch()->getName());
    }

    public function testTag() : void
    {
        $commit = new Commit();

        $commit->setTag(new Tag('1.0.0'));
        self::assertEquals('1.0.0', $commit->getTag()->getName());
    }

    public function testDate() : void
    {
        $commit = new Commit();

        $commit->setDate($date = new \DateTime('now'));
        self::assertEquals($date->format('Y-m-d'), $commit->getDate()->format('Y-m-d'));
    }

    public function testRepository() : void
    {
        $commit = new Commit();

        $commit->setRepository(new Repository(\realpath(__DIR__ . '/../../../')));
        self::assertEquals(\realpath(__DIR__ . '/../../../'), $commit->getRepository()->getPath());
    }
}
