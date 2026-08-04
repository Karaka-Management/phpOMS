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

use phpOMS\Utils\Git\Branch;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Utils\Git\Branch::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Utils\Git\BranchTest: Git branch')]
final class BranchTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The branch has the expected default values after initialization')]
    public function testDefault() : void
    {
        $branch = new Branch();
        self::assertEquals('', $branch->name);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The branch name can be set during initialization and returned')]
    public function testConstructInputOutput() : void
    {
        $branch = new Branch('test');
        self::assertEquals('test', $branch->name);
    }
}
