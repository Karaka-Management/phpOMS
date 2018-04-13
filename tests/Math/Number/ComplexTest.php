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

namespace phpOMS\tests\Math\Number;

use phpOMS\Math\Number\Complex;

class ComplexTest extends \PHPUnit\Framework\TestCase
{
    public function testDefault()
    {
        $cpl = new Complex();
        self::assertEquals(0, $cpl->re());
        self::assertEquals(0, $cpl->im());
        self::assertEquals('', $cpl->render());
    }

    public function testSetGet()
    {
        $cpl = new Complex(1, 2);
        self::assertEquals(1, $cpl->re());
        self::assertEquals(2, $cpl->im());
    }

    public function testBasics()
    {
        $cpl1 = new Complex(2, 3);
        $cpl2 = new Complex(3, 4);

        self::assertEquals('5.00 + 7.00i', $cpl1->add($cpl2)->render());
        self::assertEquals('6.00 + 3.00i', $cpl1->add(4)->render());

        self::assertEquals('-1.00 - 1.00i', $cpl1->sub($cpl2)->render());
        self::assertEquals('-2.00 + 3.00i', $cpl1->sub(4)->render());

        self::assertEquals('-6.00 + 17.00i', $cpl1->mult($cpl2)->render());
        self::assertEquals('8.00 + 12.00i', $cpl1->mult(4)->render());

        self::assertEquals('0.72 + 0.04i', $cpl1->div($cpl2)->render(2));
        self::assertEquals('0.50 + 0.75i', $cpl1->div(4)->render(2));
    }

    public function testSpecial()
    {
        $cpl = new Complex(4, 3);

        self::assertEquals('4 - 3i', $cpl->conjugate()->render(0));
        self::assertEquals('0.16 - 0.12i', $cpl->reciprocal()->render(2));

        self::assertEquals('7.00 + 24.00i', $cpl->square()->render());
        self::assertEquals('7.00 + 24.00i', $cpl->pow(2)->render());
        self::assertEquals('-44.00 - 117.00i', $cpl->pow(3)->render());

        self::assertEquals(5, $cpl->abs(), '', 0.01);

        self::assertEquals('2.12 + 0.71i', $cpl->sqrt()->render());

        $cpl2 = new Complex(-1, 3);
        self::assertEquals('1.04 + 1.44i', $cpl2->sqrt()->render());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidAdd()
    {
        $cpl = new Complex(4, 3);
        $cpl->add(true);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidSub()
    {
        $cpl = new Complex(4, 3);
        $cpl->sub(true);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidMult()
    {
        $cpl = new Complex(4, 3);
        $cpl->mult(true);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidDiv()
    {
        $cpl = new Complex(4, 3);
        $cpl->div(true);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidPow()
    {
        $cpl = new Complex(4, 3);
        $cpl->pow(true);
    }
}
