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

namespace phpOMS\tests\Utils\Git;

use phpOMS\Utils\Git\Commit;
use phpOMS\Utils\Git\Author;
use phpOMS\Utils\Git\Branch;
use phpOMS\Utils\Git\Tag;
use phpOMS\Utils\Git\Repository;

class CommitTest extends \PHPUnit\Framework\TestCase
{
    public function testDefault()
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

    public function testGetSet()
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

        $commit->setMessage('My Message');
        self::assertEquals('My Message', $commit->getMessage());

        $commit->setAuthor(new Author('Orange'));
        self::assertEquals('Orange', $commit->getAuthor()->getName());

        $commit->setBranch(new Branch('develop'));
        self::assertEquals('develop', $commit->getBranch()->getName());

        $commit->setTag(new Tag('1.0.0'));
        self::assertEquals('1.0.0', $commit->getTag()->getName());

        $commit->setDate($date = new \DateTime('now'));
        self::assertEquals($date->format('Y-m-d'), $commit->getDate()->format('Y-m-d'));

        $commit->setRepository(new Repository(realpath(__DIR__ . '/../../../')));
        self::assertEquals(realpath(__DIR__ . '/../../../'), $commit->getRepository()->getPath());
    }
}
