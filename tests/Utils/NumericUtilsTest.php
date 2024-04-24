<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\tests\Utils;

use phpOMS\Utils\NumericUtils;

require_once __DIR__ . '/../Autoloader.php';

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Utils\NumericUtils::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Utils\NumericUtilsTest: Numeric utilities')]
final class NumericUtilsTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Integers can be unsigned right shifted')]
    public function testShift() : void
    {
        self::assertEquals(10, NumericUtils::uRightShift(10, 0));
        self::assertEquals(3858, NumericUtils::uRightShift(123456, 5));
    }
}
