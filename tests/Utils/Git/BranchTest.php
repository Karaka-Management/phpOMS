<?php
/**
 * Orange Management
 *
 * PHP Version 8.0
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\tests\Utils\Git;

use phpOMS\Utils\Git\Branch;

/**
 * @testdox phpOMS\tests\Utils\Git\BranchTest: Git branch
 *
 * @internal
 */
final class BranchTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox The branch has the expected default values after initialization
     * @covers phpOMS\Utils\Git\Branch
     * @group framework
     */
    public function testDefault() : void
    {
        $branch = new Branch();
        self::assertEquals('', $branch->name);
    }

    /**
     * @testdox The branch name can be set during initialization and returned
     * @covers phpOMS\Utils\Git\Branch
     * @group framework
     */
    public function testConstructInputOutput() : void
    {
        $branch = new Branch('test');
        self::assertEquals('test', $branch->name);
    }
}
