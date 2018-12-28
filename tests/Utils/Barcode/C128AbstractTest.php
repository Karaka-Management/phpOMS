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

    /**
     * @expectedException \OutOfBoundsException
     */
    public function testInvalidDimensionWidth() : void
    {
        $this->obj->setDimension(-2, 1);
    }

    /**
     * @expectedException \OutOfBoundsException
     */
    public function testInvalidDimensionHeight() : void
    {
        $this->obj->setDimension(1, -2);
    }

    /**
     * @expectedException \phpOMS\Stdlib\Base\Exception\InvalidEnumValue
     */
    public function testInvalidOrientation() : void
    {
        $this->obj->setOrientation(99);
    }
}
