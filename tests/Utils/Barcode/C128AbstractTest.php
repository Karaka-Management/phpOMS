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

namespace phpOMS\tests\Utils\Barcode;

use phpOMS\Utils\Barcode\C128Abstract;

class C128AbstractTest extends \PHPUnit\Framework\TestCase
{
    protected $obj = null;

    protected function setUp() : void
    {
        $this->obj = new class extends C128Abstract {};
    }

    public function testSetGet() : void
    {
        $this->obj->setContent('abc');
        self::assertEquals('abc', $this->obj->getContent());
    }

    public function testInvalidDimensionWidth() : void
    {
        self::expectedException(\OutOfBoundsException::class);

        $this->obj->setDimension(-2, 1);
    }

    public function testInvalidDimensionHeight() : void
    {
        self::expectedException(\OutOfBoundsException::class);

        $this->obj->setDimension(1, -2);
    }

    public function testInvalidOrientation() : void
    {
        self::expectedException(\phpOMS\Stdlib\Base\Exception\InvalidEnumValue::class);

        $this->obj->setOrientation(99);
    }
}
