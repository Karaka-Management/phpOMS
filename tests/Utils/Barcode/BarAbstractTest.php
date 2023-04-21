<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\tests\Utils\Barcode;

use phpOMS\Utils\Barcode\BarAbstract;

/**
 * @internal
 */
final class BarAbstractTest extends \PHPUnit\Framework\TestCase
{
    protected $obj = null;

    /**
     * {@inheritdoc}
     */
    protected function setUp() : void
    {
        $this->obj = new class() extends BarAbstract {};
    }

    /**
     * @covers phpOMS\Utils\Barcode\C128Abstract<extended>
     * @group framework
     */
    public function testSetGet() : void
    {
        $this->obj->setContent('abc');
        self::assertEquals('abc', $this->obj->getContent());
    }

    /**
     * @covers phpOMS\Utils\Barcode\C128Abstract<extended>
     * @group framework
     */
    public function testInvalidDimensionWidth() : void
    {
        $this->expectException(\OutOfBoundsException::class);

        $this->obj->setDimension(-2, 1);
    }

    /**
     * @covers phpOMS\Utils\Barcode\C128Abstract<extended>
     * @group framework
     */
    public function testInvalidDimensionHeight() : void
    {
        $this->expectException(\OutOfBoundsException::class);

        $this->obj->setDimension(1, -2);
    }

    /**
     * @covers phpOMS\Utils\Barcode\C128Abstract<extended>
     * @group framework
     */
    public function testInvalidOrientation() : void
    {
        $this->expectException(\phpOMS\Stdlib\Base\Exception\InvalidEnumValue::class);

        $this->obj->setOrientation(99);
    }
}
