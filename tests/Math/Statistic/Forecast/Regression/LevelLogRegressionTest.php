<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    TBD
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */

namespace phpOMS\tests\Math\Statistic\Forecast\Regression;

use phpOMS\Math\Statistic\Forecast\Regression\LevelLogRegression;
use phpOMS\Math\Statistic\Forecast\Regression\LevelLevelRegression;

class LevelLogRegressionTest extends \PHPUnit\Framework\TestCase
{
    public function testRegression()
    {
        // y = 1 + log(x)
        $x = [0.25, 0.5, 1, 1.5];
        $y = [-0.386, 0.307, 1, 1.405];

        $reg = LevelLogRegression::getRegression($x, $y);

        self::assertEquals(['b0' => 1, 'b1' => 1], $reg, '', 0.2);
    }
}
