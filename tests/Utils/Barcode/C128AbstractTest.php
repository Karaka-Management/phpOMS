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
use phpOMS\Utils\Barcode\OrientationType;

class C128AbstractTest extends \PHPUnit\Framework\TestCase
{
    protected $obj = null;

    protected function setUp()
    {
        $this->obj = new class extends C128Abstract {};
    }

    public function testSetGet()
    {
        $this->obj->setContent('abc');
        self::assertEquals('abc', $this->obj->getContent());
    }

    /**
     * @expectedException \OutOfBoundsException
     */
    public function testInvalidDimensionWidth()
    {
        $this->obj->setDimension(-2, 1);
    }

    /**
     * @expectedException \OutOfBoundsException
     */
    public function testInvalidDimensionHeight()
    {
        $this->obj->setDimension(1, -2);
    }

    /**
     * @expectedException \phpOMS\Stdlib\Base\Exception\InvalidEnumValue
     */
    public function testInvalidOrientation()
    {
        $this->obj->setOrientation(99);
    }
}
