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

use phpOMS\Math\Statistic\Forecast\Regression\LogLogRegression;

class LogLogRegressionTest extends \PHPUnit\Framework\TestCase
{
    public function testRegression()
    {
        // ln(y) = 2 + 3 * ln(x) => y = e^(2 + 3 * ln(x))
        $x = [0.25, 0.5, 1, 1.5];
        $y = [0.115, 0.924, 7.389, 24.938];

        $reg = LogLogRegression::getRegression($x, $y);

        self::assertEquals(['b0' => 2, 'b1' => 3], $reg, '', 0.2);
    }
}
