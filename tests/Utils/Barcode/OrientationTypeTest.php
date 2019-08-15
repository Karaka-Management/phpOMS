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

namespace phpOMS\tests\Utils\Barcode;

use phpOMS\Utils\Barcode\OrientationType;

/**
 * @internal
 */
class OrientationTypeTest extends \PHPUnit\Framework\TestCase
{
    public function testEnums() : void
    {
        self::assertCount(2, OrientationType::getConstants());
        self::assertEquals(OrientationType::getConstants(), \array_unique(OrientationType::getConstants()));

        self::assertEquals(0, OrientationType::HORIZONTAL);
        self::assertEquals(1, OrientationType::VERTICAL);
    }
}
