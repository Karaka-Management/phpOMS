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
 * @link       http://website.orange-management.de
 */

namespace phpOMS\tests\Math\Statistic;

use phpOMS\Math\Statistic\Basic;

class BasicTest extends \PHPUnit\Framework\TestCase
{
    public function testFrequency()
    {
        self::assertEquals(
            [1 / 10, 2 / 10, 3 / 10, 4 / 10],
            Basic::frequency([1, 2, 3, 4])
        );

        self::assertEquals(
            [1 / 10, 2 / 10, 3 / 10, [1 / 6, 2 / 6, 3 / 6], 4 / 10],
            Basic::frequency([1, 2, 3, [1, 2, 3], 4])
        );
    }
}
