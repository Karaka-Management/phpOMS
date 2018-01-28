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

namespace phpOMS\tests\Business\Finance\Forecasting\ExponentialSmoothing;

use phpOMS\Business\Finance\Forecasting\ExponentialSmoothing\TrendType;

class TrendTypeTest extends \PHPUnit\Framework\TestCase
{
    public function testEnums()
    {
        self::assertEquals(4, count(TrendType::getConstants()));
        self::assertEquals(TrendType::getConstants(), array_unique(TrendType::getConstants()));

        self::assertEquals(0, TrendType::ALL);
        self::assertEquals(1, TrendType::NONE);
        self::assertEquals(2, TrendType::ADDITIVE);
        self::assertEquals(4, TrendType::MULTIPLICATIVE);
    }
}
