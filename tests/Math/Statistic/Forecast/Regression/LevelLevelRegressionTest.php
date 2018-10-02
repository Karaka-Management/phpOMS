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

namespace phpOMS\tests\Math\Statistic\Forecast\Regression;

use phpOMS\Math\Statistic\Forecast\Regression\LevelLevelRegression;

class LevelLevelRegressionTest extends \PHPUnit\Framework\TestCase
{
    public function testRegression()
    {
        // y = 3 + 4 * x
        $x = [0, 1, 2, 3, 4];
        $y = [3, 7, 11, 15, 19];

        $reg = LevelLevelRegression::getRegression($x, $y);

        self::assertEquals(['b0' => 3, 'b1' => 4], $reg, '', 0.2);
        self::assertEquals(4, LevelLevelRegression::getSlope($reg['b1'], 0, 0));
        self::assertEquals(22, LevelLevelRegression::getElasticity($reg['b1'], 11, 2));
    }
}
