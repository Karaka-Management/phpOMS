<?php
/**
 * Orange Management
 *
 * PHP Version 7.1
 *
 * @package    TBD
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */

namespace phpOMS\tests\Math\Statistic;

use phpOMS\Math\Statistic\MeasureOfDispersion;

class MeasureOfDispersionTest extends \PHPUnit\Framework\TestCase
{
    public function testRange()
    {
        self::assertEquals((float) (9 - 1), MeasureOfDispersion::range([4, 5, 9, 1, 3]));
    }
}
