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

use phpOMS\Utils\Git\Git;

/**
 * @testdox phpOMS\tests\Utils\Git\GitTest: Git utilities
 *
 * @internal
 */
final class GitTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox The git path can be returned
     * @covers phpOMS\Utils\Git\Git
     * @group framework
     */
    public function testBinary() : void
    {
        self::assertEquals('/usr/bin/git', Git::getBin());
    }
}
