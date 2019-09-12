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

use phpOMS\Utils\Git\Branch;

/**
 * @internal
 */
class BranchTest extends \PHPUnit\Framework\TestCase
{
    public function testDefault() : void
    {
        $branch = new Branch();
        self::assertEquals('', $branch->getName());
    }

    public function testGetSet() : void
    {
        $branch = new Branch('test');
        self::assertEquals('test', $branch->getName());
    }
}
