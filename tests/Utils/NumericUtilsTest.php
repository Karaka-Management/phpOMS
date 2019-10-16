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

namespace phpOMS\tests\Utils;

use phpOMS\Utils\NumericUtils;

require_once __DIR__ . '/../Autoloader.php';

/**
 * @internal
 */
class NumericUtilsTest extends \PHPUnit\Framework\TestCase
{
    public function testShift() : void
    {
        self::assertEquals(10, NumericUtils::uRightShift(10, 0));
        self::assertEquals(3858, NumericUtils::uRightShift(123456, 5));
    }
}
