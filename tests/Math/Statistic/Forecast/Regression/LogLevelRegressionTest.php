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

use phpOMS\Math\Statistic\Forecast\Regression\LogLevelRegression;

class LogLevelRegressionTest extends \PHPUnit\Framework\TestCase
{
    public function testRegression()
    {
        // ln(y) = -1 + 2 * x => y = e^(-1 + 2 * x)
        $x = [0.25, 0.5, 1, 1.5];
        $y = [0.6065, 1, 2.718, 7.389];

        $reg = LogLevelRegression::getRegression($x, $y);

        self::assertEquals(['b0' => -1, 'b1' => 2], $reg, '', 0.2);
    }
}
