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

namespace phpOMS\tests\Business\Marketing;

use phpOMS\Business\Marketing\CustomerValue;

/**
 * @testdox phpOMS\tests\Business\Marketing\CustomerValueTest: Customer value
 *
 * @internal
 */
class CustomerValueTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox The simple customer life time value is correctly calculated
     * @group framework
     */
    public function testSimpleCLV() : void
    {
        $margin    = 3000;
        $years     = 10;
        $retention = $years / (1 + $years);
        self::assertEqualsWithDelta(30000, CustomerValue::getSimpleCLV($margin, $retention, 0.0), 0.1);
    }


    /**
     * @testdox The monthly recurring revenue (MRR) is correctly calculated
     * @group framework
     */
    public function testMRR() : void
    {
        $revenues = [
            1, 2, 4, 8, 16, 32, 64, 128, 256, 512, 1024, 2048, 4096
        ];

        self::assertEqualsWithDelta(77.53846, CustomerValue::getMRR($revenues, 13, 10, 1000), 0.01);
        self::assertEqualsWithDelta(630.07692307, CustomerValue::getMRR($revenues, 13, 0.0, 0.0), 0.01);
    }
}
