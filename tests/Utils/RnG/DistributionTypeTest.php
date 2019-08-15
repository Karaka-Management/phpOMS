<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package    tests
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       https://orange-management.org
 */
 declare(strict_types=1);

namespace phpOMS\tests\Utils\RnG;

use phpOMS\Utils\RnG\DistributionType;

/**
 * @internal
 */
class DistributionTypeTest extends \PHPUnit\Framework\TestCase
{
    public function testEnums() : void
    {
        self::assertCount(2, DistributionType::getConstants());
        self::assertEquals(DistributionType::getConstants(), \array_unique(DistributionType::getConstants()));

        self::assertEquals(0, DistributionType::UNIFORM);
        self::assertEquals(1, DistributionType::NORMAL);
    }
}
