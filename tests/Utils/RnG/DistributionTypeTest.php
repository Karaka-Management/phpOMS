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

namespace phpOMS\tests\Utils\RnG;

use phpOMS\Utils\RnG\DistributionType;

class DistributionTypeTest extends \PHPUnit\Framework\TestCase
{
    public function testEnums()
    {
        self::assertEquals(2, \count(DistributionType::getConstants()));
        self::assertEquals(DistributionType::getConstants(), array_unique(DistributionType::getConstants()));

        self::assertEquals(0, DistributionType::UNIFORM);
        self::assertEquals(1, DistributionType::NORMAL);
    }
}
