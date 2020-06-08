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

use phpOMS\Utils\Barcode\C128Abstract;

/**
 * @internal
 */
class C128AbstractTest extends \PHPUnit\Framework\TestCase
{
    protected $obj = null;

    protected function setUp() : void
    {
        $this->obj = new class() extends C128Abstract {};
    }

    public function testSetGet() : void
    {
        $this->obj->setContent('abc');
        self::assertEquals('abc', $this->obj->getContent());
    }

    public function testInvalidDimensionWidth() : void
    {
        $this->expectException(\OutOfBoundsException::class);

        $this->obj->setDimension(-2, 1);
    }

    public function testInvalidDimensionHeight() : void
    {
        $this->expectException(\OutOfBoundsException::class);

        $this->obj->setDimension(1, -2);
    }

    public function testInvalidOrientation() : void
    {
        $this->expectException(\phpOMS\Stdlib\Base\Exception\InvalidEnumValue::class);

        $this->obj->setOrientation(99);
    }
}
