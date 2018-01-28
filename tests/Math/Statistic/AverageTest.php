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

use phpOMS\Math\Statistic\Average;

class AverageTest extends \PHPUnit\Framework\TestCase
{
    public function testAverage()
    {
        self::assertEquals(-3/2, Average::averageDatasetChange([6, 7, 6, 3, 0]));
    }
}
