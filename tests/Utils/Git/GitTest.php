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
 * @link       https://orange-management.org
 */
 declare(strict_types=1);

namespace phpOMS\tests\Utils\Git;

use phpOMS\Utils\Git\Git;

/**
 * @internal
 */
class GitTest extends \PHPUnit\Framework\TestCase
{
    public function testDefault() : void
    {
        self::assertEquals('/usr/bin/git', Git::getBin());
    }
}
