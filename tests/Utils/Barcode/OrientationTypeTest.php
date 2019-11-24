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

namespace phpOMS\tests\Utils\Barcode;

use phpOMS\Utils\Barcode\OrientationType;

/**
 * @internal
 */
class OrientationTypeTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @coversNothing
     */
    public function testEnumCount() : void
    {
        self::assertCount(2, OrientationType::getConstants());
    }

    /**
     * @coversNothing
     */
    public function testUnique() : void
    {
        self::assertEquals(OrientationType::getConstants(), \array_unique(OrientationType::getConstants()));
    }

    /**
     * @coversNothing
     */
    public function testEnums() : void
    {
        self::assertEquals(0, OrientationType::HORIZONTAL);
        self::assertEquals(1, OrientationType::VERTICAL);
    }
}
