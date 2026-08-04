<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.2
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\tests\Math\Number;

use phpOMS\Math\Number\Complex;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Math\Number\Complex::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Math\Number\ComplexTest: Complex number operations')]
final class ComplexTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The complex number has the expected default values after initialization')]
    public function testDefault() : void
    {
        $cpl = new Complex();
        self::assertEquals(0, $cpl->re());
        self::assertEquals(0, $cpl->im());
        self::assertEquals('', $cpl->render());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The real and imaginary part can be set during initialization and returned')]
    public function testConstructorInputOutput() : void
    {
        $cpl = new Complex(1, 2);
        self::assertEquals(1, $cpl->re());
        self::assertEquals(2, $cpl->im());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A complex number can be added to a complex number')]
    public function testAddComplex() : void
    {
        $cpl1 = new Complex(2, 3);
        $cpl2 = new Complex(3, 4);

        self::assertEquals('5.00 + 7.00i', $cpl1->add($cpl2)->render());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A real number can be added to a complex number')]
    public function testAddReal() : void
    {
        $cpl1 = new Complex(2, 3);
        self::assertEquals('6.00 + 3.00i', $cpl1->add(4)->render());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A complex number can be subtracted from a complex number')]
    public function testSubComplex() : void
    {
        $cpl1 = new Complex(2, 3);
        $cpl2 = new Complex(3, 4);

        self::assertEquals('-1.00 - 1.00i', $cpl1->sub($cpl2)->render());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A real number can be subtracted from a complex number')]
    public function testSubReal() : void
    {
        $cpl1 = new Complex(2, 3);
        self::assertEquals('-2.00 + 3.00i', $cpl1->sub(4)->render());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A complex number can be multiplied with a complex number')]
    public function testMultComplex() : void
    {
        $cpl1 = new Complex(2, 3);
        $cpl2 = new Complex(3, 4);

        self::assertEquals('-6.00 + 17.00i', $cpl1->mult($cpl2)->render());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A real number can be multiplied with a complex number')]
    public function testMultReal() : void
    {
        $cpl1 = new Complex(2, 3);
        self::assertEquals('8.00 + 12.00i', $cpl1->mult(4)->render());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A complex number can be divided by a complex number number')]
    public function testDivComplex() : void
    {
        $cpl1 = new Complex(2, 3);
        $cpl2 = new Complex(3, 4);

        self::assertEquals('0.72 + 0.04i', $cpl1->div($cpl2)->render(2));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A complex number can be divided by a real number')]
    public function testDivReal() : void
    {
        $cpl1 = new Complex(2, 3);
        self::assertEquals('0.50 + 0.75i', $cpl1->div(4)->render(2));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A complex number can be conjugated')]
    public function testConjugate() : void
    {
        $cpl = new Complex(4, 3);

        self::assertEquals('4 - 3i', $cpl->conjugate()->render(0));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The reciprocal of a complex number can be calculated')]
    public function testReciprocal() : void
    {
        $cpl = new Complex(4, 3);

        self::assertEquals('0.16 - 0.12i', $cpl->reciprocal()->render(2));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A complex number can be squared')]
    public function testSquare() : void
    {
        $cpl = new Complex(4, 3);

        self::assertEquals('7.00 + 24.00i', $cpl->square()->render());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The real power of a complex number can be calculated')]
    public function testPower() : void
    {
        $cpl = new Complex(4, 3);

        self::assertEquals('7.00 + 24.00i', $cpl->pow(2)->render());
        self::assertEquals('-44.00 + 117.00i', $cpl->pow(3)->render());
        self::assertEquals('1.00', $cpl->pow(0)->render());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The absolute value of a complex number can be calculated')]
    public function testAbs() : void
    {
        $cpl = new Complex(4, 3);

        self::assertEqualsWithDelta(5, $cpl->abs(), 0.01);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The square root of a complex number can be calculated')]
    public function testSqrt() : void
    {
        $cpl = new Complex(4, 3);
        self::assertEquals('2.12 + 0.71i', $cpl->sqrt()->render());

        $cpl2 = new Complex(-1, 3);
        self::assertEquals('1.04 + 1.44i', $cpl2->sqrt()->render());
    }
}
