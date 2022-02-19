<?php
/**
 * Karaka
 *
 * PHP Version 8.0
 *
 * @package   phpOMS\Math\Number
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://karaka.app
 */
declare(strict_types=1);

namespace phpOMS\Math\Number;

/**
 * Complex number class.
 *
 * @package phpOMS\Math\Number
 * @license OMS License 1.0
 * @link    https://karaka.app
 * @since   1.0.0
 */
final class Complex
{
    /**
     * Real part.
     *
     * @var int|float
     * @since 1.0.0
     */
    private $re;

    /**
     * Imaginary part.
     *
     * @var int|float
     * @since 1.0.0
     */
    private $im;

    /**
     * Constructor.
     *
     * @param int|float $re Real part
     * @param int|float $im Imaginary part
     *
     * @since 1.0.0
     */
    public function __construct(int | float $re = 0, int | float $im = 0)
    {
        $this->re = $re;
        $this->im = $im;
    }

    /**
     * Get real part
     *
     * @return int|float
     *
     * @since 1.0.0
     */
    public function re() : int | float
    {
        return $this->re;
    }

    /**
     * Get imaginary part
     *
     * @return int|float
     *
     * @since 1.0.0
     */
    public function im() : int | float
    {
        return $this->im;
    }

    /**
     * Conjugate
     *
     * @latex z = a - b*i
     *
     * @return Complex
     *
     * @since 1.0.0
     */
    public function conjugate() : self
    {
        return new self($this->re, -$this->im);
    }

    /**
     * Reciprocal
     *
     * @return Complex
     *
     * @since 1.0.0
     */
    public function reciprocal() : self
    {
        return new self(
            $this->re / ($this->re ** 2 + $this->im ** 2),
            -$this->im / ($this->re ** 2 + $this->im ** 2)
        );
    }

    /**
     * Square root
     *
     * @return Complex
     *
     * @since 1.0.0
     */
    public function sqrt() : self
    {
        return new self(
            \sqrt(($this->re + \sqrt($this->re ** 2 + $this->im ** 2)) / 2),
            ($this->im <=> 0) * \sqrt((-$this->re + \sqrt($this->re ** 2 + $this->im ** 2)) / 2)
        );
    }

    /**
     * Absolute
     *
     * @return int|float
     *
     * @since 1.0.0
     */
    public function abs() : int | float
    {
        return \sqrt($this->re ** 2 + $this->im ** 2);
    }

    /**
     * Square
     *
     * @return Complex
     *
     * @since 1.0.0
     */
    public function square() : self
    {
        return $this->multComplex($this);
    }

    /**
     * Pow opperator
     *
     * @param int|float|self $value Value to pow
     *
     * @return Complex
     *
     * @since 1.0.0
     */
    public function pow(int | float | self $value) : self
    {
        if (\is_int($value)) {
            return $this->powInteger($value);
        } elseif (\is_float($value)) {
            return $this->powScalar($value);
        }

        return $this->powComplex($value);
    }

    /**
     * Power with complex number
     *
     * @param Complex $value Power
     *
     * @return Complex
     *
     * @since 1.0.0
     */
    public function powComplex(self $value) : self
    {
        return $this;
    }

    /**
     * Power with integer
     *
     * @param int $value Power
     *
     * @return Complex
     *
     * @since 1.0.0
     */
    public function powInteger(int $value) : self
    {
        if ($value === 0) {
            return new self(1, 0);
        } elseif ($value === 1) {
            return $this;
        }

        return $this->multComplex($this->powInteger(--$value));
    }

    /**
     * Power with scalar
     *
     * @param int|float $value Power
     *
     * @return Complex
     *
     * @since 1.0.0
     */
    public function powScalar(int | float $value) : self
    {
        return $this;
    }

    /**
     * Add opperator
     *
     * @param int|float|self $value Value to add
     *
     * @return Complex
     *
     * @since 1.0.0
     */
    public function add(int | float | self $value) : self
    {
        if (\is_numeric($value)) {
            return $this->addScalar($value);
        }

        return $this->addComplex($value);
    }

    /**
     * Add opperator
     *
     * @param Complex $cpl Value to add
     *
     * @return Complex
     *
     * @since 1.0.0
     */
    private function addComplex(self $cpl) : self
    {
        return new self($this->re + $cpl->re(), $this->im + $cpl->im());
    }

    /**
     * Add opperator
     *
     * @param int|float $val Value to add
     *
     * @return Complex
     *
     * @since 1.0.0
     */
    private function addScalar(int | float $val) : self
    {
        return new self($this->re + $val, $this->im);
    }

    /**
     * Sub opperator
     *
     * @param int|float|self $value Value to sub
     *
     * @return Complex
     *
     * @since 1.0.0
     */
    public function sub(int | float | self $value) : self
    {
        if (\is_numeric($value)) {
            return $this->subScalar($value);
        }

        return $this->subComplex($value);
    }

    /**
     * Sub opperator
     *
     * @param Complex $cpl Value to sub
     *
     * @return Complex
     *
     * @since 1.0.0
     */
    private function subComplex(self $cpl) : self
    {
        return new self($this->re - $cpl->re(), $this->im - $cpl->im());
    }

    /**
     * Sub opperator
     *
     * @param int|float $val Value to sub
     *
     * @return Complex
     *
     * @since 1.0.0
     */
    private function subScalar(int | float $val) : self
    {
        return new self($this->re - $val, $this->im);
    }

    /**
     * Mult opperator
     *
     * @param int|float|self $value Value to mult
     *
     * @return Complex
     *
     * @since 1.0.0
     */
    public function mult(int | float | self $value) : self
    {
        if (\is_numeric($value)) {
            return $this->multScalar($value);
        }

        return $this->multComplex($value);
    }

    /**
     * Mult opperator
     *
     * @param Complex $cpl Value to mult
     *
     * @return Complex
     *
     * @since 1.0.0
     */
    private function multComplex(self $cpl) : self
    {
        return new self(
            $this->re * $cpl->re() - $this->im * $cpl->im(),
            $this->re * $cpl->im() + $this->im * $cpl->re()
        );
    }

    /**
     * Mult opperator
     *
     * @param int|float $val Value to mult
     *
     * @return Complex
     *
     * @since 1.0.0
     */
    private function multScalar(int | float $val) : self
    {
        return new self($this->re * $val, $this->im * $val);
    }

    /**
     * Div opperator
     *
     * @param int|float|self $value Value to div
     *
     * @return Complex
     *
     * @throws \InvalidArgumentException This exception is thrown if the argument has an invalid type
     *
     * @since 1.0.0
     */
    public function div(int | float | self $value) : self
    {
        if (\is_numeric($value)) {
            return $this->divScalar($value);
        }

        return $this->divComplex($value);
    }

    /**
     * Div opperator
     *
     * @param Complex $cpl Value to div
     *
     * @return Complex
     *
     * @since 1.0.0
     */
    private function divComplex(self $cpl) : self
    {
        return new self(
            ($this->re * $cpl->re() + $this->im * $cpl->im()) / ($cpl->re() ** 2 + $cpl->im() ** 2),
            ($this->im * $cpl->re() - $this->re * $cpl->im()) / ($cpl->re() ** 2 + $cpl->im() ** 2)
        );
    }

    /**
     * Div opperator
     *
     * @param int|float $val Value to div
     *
     * @return Complex
     *
     * @since 1.0.0
     */
    private function divScalar(int | float $val) : self
    {
        return new self($this->re / $val, $this->im / $val);
    }

    /**
     * Render complex number
     *
     * @param int $precision Output precision
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function render(int $precision = 2) : string
    {
        return ($this->re !== 0 ? \number_format($this->re, $precision) : '')
        . ($this->im > 0 && $this->re !== 0 ? ' +' : '')
        . ($this->im < 0 && $this->re !== 0 ? ' -' : '')
        . ($this->im !== 0 ? (
            ($this->re !== 0 ? ' ' : '') . \number_format(
                ($this->im < 0 && $this->re === 0 ? $this->im : \abs($this->im)), $precision
                ) . 'i'
            ) : '');
    }
}
