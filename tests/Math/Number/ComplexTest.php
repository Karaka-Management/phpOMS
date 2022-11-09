<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://karaka.app
 */
declare(strict_types=1);

namespace phpOMS\tests\Math\Number;

use phpOMS\Math\Number\Complex;

/**
 * @testdox phpOMS\tests\Math\Number\ComplexTest: Complex number operations
 *
 * @internal
 */
final class ComplexTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox The complex number has the expected default values after initialization
     * @covers phpOMS\Math\Number\Complex
     * @group framework
     */
    public function testDefault() : void
    {
        $cpl = new Complex();
        self::assertEquals(0, $cpl->re());
        self::assertEquals(0, $cpl->im());
        self::assertEquals('', $cpl->render());
    }

    /**
     * @testdox The real and imaginary part can be set during initialization and returned
     * @covers phpOMS\Math\Number\Complex
     * @group framework
     */
    public function testConstructorInputOutput() : void
    {
        $cpl = new Complex(1, 2);
        self::assertEquals(1, $cpl->re());
        self::assertEquals(2, $cpl->im());
    }

    /**
     * @testdox A complex number can be added to a complex number
     * @covers phpOMS\Math\Number\Complex
     * @group framework
     */
    public function testAddComplex() : void
    {
        $cpl1 = new Complex(2, 3);
        $cpl2 = new Complex(3, 4);

        self::assertEquals('5.00 + 7.00i', $cpl1->add($cpl2)->render());
    }

    /**
     * @testdox A real number can be added to a complex number
     * @covers phpOMS\Math\Number\Complex
     * @group framework
     */
    public function testAddReal() : void
    {
        $cpl1 = new Complex(2, 3);
        self::assertEquals('6.00 + 3.00i', $cpl1->add(4)->render());
    }

    /**
     * @testdox A complex number can be subtracted from a complex number
     * @covers phpOMS\Math\Number\Complex
     * @group framework
     */
    public function testSubComplex() : void
    {
        $cpl1 = new Complex(2, 3);
        $cpl2 = new Complex(3, 4);

        self::assertEquals('-1.00 - 1.00i', $cpl1->sub($cpl2)->render());
    }

    /**
     * @testdox A real number can be subtracted from a complex number
     * @covers phpOMS\Math\Number\Complex
     * @group framework
     */
    public function testSubReal() : void
    {
        $cpl1 = new Complex(2, 3);
        self::assertEquals('-2.00 + 3.00i', $cpl1->sub(4)->render());
    }

    /**
     * @testdox A complex number can be multiplied with a complex number
     * @covers phpOMS\Math\Number\Complex
     * @group framework
     */
    public function testMultComplex() : void
    {
        $cpl1 = new Complex(2, 3);
        $cpl2 = new Complex(3, 4);

        self::assertEquals('-6.00 + 17.00i', $cpl1->mult($cpl2)->render());
    }

    /**
     * @testdox A real number can be multiplied with a complex number
     * @covers phpOMS\Math\Number\Complex
     * @group framework
     */
    public function testMultReal() : void
    {
        $cpl1 = new Complex(2, 3);
        self::assertEquals('8.00 + 12.00i', $cpl1->mult(4)->render());
    }

    /**
     * @testdox A complex number can be divided by a complex number number
     * @covers phpOMS\Math\Number\Complex
     * @group framework
     */
    public function testDivComplex() : void
    {
        $cpl1 = new Complex(2, 3);
        $cpl2 = new Complex(3, 4);

        self::assertEquals('0.72 + 0.04i', $cpl1->div($cpl2)->render(2));
    }

    /**
     * @testdox A complex number can be divided by a real number
     * @covers phpOMS\Math\Number\Complex
     * @group framework
     */
    public function testDivReal() : void
    {
        $cpl1 = new Complex(2, 3);
        self::assertEquals('0.50 + 0.75i', $cpl1->div(4)->render(2));
    }

    /**
     * @testdox A complex number can be conjugated
     * @covers phpOMS\Math\Number\Complex
     * @group framework
     */
    public function testConjugate() : void
    {
        $cpl = new Complex(4, 3);

        self::assertEquals('4 - 3i', $cpl->conjugate()->render(0));
    }

    /**
     * @testdox The reciprocal of a complex number can be calculated
     * @covers phpOMS\Math\Number\Complex
     * @group framework
     */
    public function testReciprocal() : void
    {
        $cpl = new Complex(4, 3);

        self::assertEquals('0.16 - 0.12i', $cpl->reciprocal()->render(2));
    }

    /**
     * @testdox A complex number can be squared
     * @covers phpOMS\Math\Number\Complex
     * @group framework
     */
    public function testSquare() : void
    {
        $cpl = new Complex(4, 3);

        self::assertEquals('7.00 + 24.00i', $cpl->square()->render());
    }

    /**
     * @testdox The real power of a complex number can be calculated
     * @covers phpOMS\Math\Number\Complex
     * @group framework
     */
    public function testPower() : void
    {
        $cpl = new Complex(4, 3);

        self::assertEquals('7.00 + 24.00i', $cpl->pow(2)->render());
        self::assertEquals('-44.00 + 117.00i', $cpl->pow(3)->render());
        self::assertEquals('1.00', $cpl->pow(0)->render());
    }

    /**
     * @testdox The absolute value of a complex number can be calculated
     * @covers phpOMS\Math\Number\Complex
     * @group framework
     */
    public function testAbs() : void
    {
        $cpl = new Complex(4, 3);

        self::assertEqualsWithDelta(5, $cpl->abs(), 0.01);
    }

    /**
     * @testdox The square root of a complex number can be calculated
     * @covers phpOMS\Math\Number\Complex
     * @group framework
     */
    public function testSqrt() : void
    {
        $cpl = new Complex(4, 3);
        self::assertEquals('2.12 + 0.71i', $cpl->sqrt()->render());

        $cpl2 = new Complex(-1, 3);
        self::assertEquals('1.04 + 1.44i', $cpl2->sqrt()->render());
    }
}
