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

use phpOMS\Utils\Git\Git;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Utils\Git\Git::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Utils\Git\GitTest: Git utilities')]
final class GitTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The git path can be returned')]
    public function testBinary() : void
    {
        self::assertEquals('/usr/bin/git', Git::getBin());
    }
}
