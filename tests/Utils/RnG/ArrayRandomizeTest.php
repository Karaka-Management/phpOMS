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

namespace phpOMS\tests\Utils\RnG;

use phpOMS\Utils\RnG\ArrayRandomize;

/**
 * @internal
 */
class ArrayRandomizeTest extends \PHPUnit\Framework\TestCase
{
    public function testRandomize() : void
    {
        $orig = [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20];

        self::assertNotEquals($orig, ArrayRandomize::yates($orig));
        self::assertNotEquals($orig, ArrayRandomize::knuth($orig));
    }
}
