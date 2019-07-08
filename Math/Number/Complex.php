<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    phpOMS\Math\Number
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Math\Number;

/**
 * Complex number class.
 *
 * @package    phpOMS\Math\Number
 * @license    OMS License 1.0
 * @link       https://orange-management.org
 * @since      1.0.0
 */
final class Complex
{
    /**
     * Real part.
     *
     * @var mixed
     * @since 1.0.0
     */
    private $re = null;

    /**
     * Imaginary part.
     *
     * @var mixed
     * @since 1.0.0
     */
    private $im = null;

    /**
     * Constructor.
     *
     * @param mixed $re Real part
     * @param mixed $im Imaginary part
     *
     * @since  1.0.0
     */
    public function __construct($re = 0, $im = 0)
    {
        $this->re = $re;
        $this->im = $im;
    }

    /**
     * Get real part
     *
     * @return mixed
     *
     * @since  1.0.0
     */
    public function re()
    {
        return $this->re;
    }

    /**
     * Get imaginary part
     *
     * @return mixed
     *
     * @since  1.0.0
     */
    public function im()
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
     * @since  1.0.0
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
     * @since  1.0.0
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
     * @since  1.0.0
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
     * @return mixed
     *
     * @since  1.0.0
     */
    public function abs()
    {
        return \sqrt($this->re ** 2 + $this->im ** 2);
    }

    /**
     * Square
     *
     * @return Complex
     *
     * @since  1.0.0
     */
    public function square() : self
    {
        return $this->multComplex($this);
    }

    /**
     * Pow opperator
     *
     * @param mixed $value Value to pow
     *
     * @return Complex
     *
     * @throws \InvalidArgumentException This exception is thrown if the argument has an invalid type
     *
     * @since  1.0.0
     */
    public function pow($value) : self
    {
        if (\is_int($value)) {
            return $this->powInteger($value);
        } elseif (\is_numeric($value)) {
            return $this->powScalar($value);
        } elseif ($value instanceof self) {
            return $this->powComplex($value);
        }

        throw new \InvalidArgumentException();
    }

    public function powComplex(self $value) : self
    {

    }

    /**
     * Power with integer
     *
     * @param int $value Power
     *
     * @return Complex
     *
     * @since  1.0.0
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

    public function powScalar($value) : self
    {

    }

    /**
     * Add opperator
     *
     * @param mixed $value Value to add
     *
     * @return Complex
     *
     * @throws \InvalidArgumentException This exception is thrown if the argument has an invalid type
     *
     * @since  1.0.0
     */
    public function add($value) : self
    {
        if (\is_numeric($value)) {
            return $this->addScalar($value);
        } elseif ($value instanceof self) {
            return $this->addComplex($value);
        }

        throw new \InvalidArgumentException();
    }

    /**
     * Add opperator
     *
     * @param Complex $cpl Value to add
     *
     * @return Complex
     *
     * @since  1.0.0
     */
    private function addComplex(self $cpl) : self
    {
        return new self($this->re + $cpl->re(), $this->im + $cpl->im());
    }

    /**
     * Add opperator
     *
     * @param mixed $val Value to add
     *
     * @return Complex
     *
     * @since  1.0.0
     */
    private function addScalar($val) : self
    {
        return new self($this->re + $val, $this->im);
    }

    /**
     * Sub opperator
     *
     * @param mixed $value Value to sub
     *
     * @return Complex
     *
     * @throws \InvalidArgumentException This exception is thrown if the argument has an invalid type
     *
     * @since  1.0.0
     */
    public function sub($value) : self
    {
        if (\is_numeric($value)) {
            return $this->subScalar($value);
        } elseif ($value instanceof self) {
            return $this->subComplex($value);
        }

        throw new \InvalidArgumentException();
    }

    /**
     * Sub opperator
     *
     * @param Complex $cpl Value to sub
     *
     * @return Complex
     *
     * @since  1.0.0
     */
    private function subComplex(self $cpl) : self
    {
        return new self($this->re - $cpl->re(), $this->im - $cpl->im());
    }

    /**
     * Sub opperator
     *
     * @param mixed $val Value to sub
     *
     * @return Complex
     *
     * @since  1.0.0
     */
    private function subScalar($val) : self
    {
        return new self($this->re - $val, $this->im);
    }

    /**
     * Mult opperator
     *
     * @param mixed $value Value to mult
     *
     * @return Complex
     *
     * @throws \InvalidArgumentException This exception is thrown if the argument has an invalid type
     *
     * @since  1.0.0
     */
    public function mult($value) : self
    {
        if (\is_numeric($value)) {
            return $this->multScalar($value);
        } elseif ($value instanceof self) {
            return $this->multComplex($value);
        }

        throw new \InvalidArgumentException();
    }

    /**
     * Mult opperator
     *
     * @param Complex $cpl Value to mult
     *
     * @return Complex
     *
     * @since  1.0.0
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
     * @param mixed $val Value to mult
     *
     * @return Complex
     *
     * @since  1.0.0
     */
    private function multScalar($val) : self
    {
        return new self($this->re * $val, $this->im * $val);
    }

    /**
     * Div opperator
     *
     * @param mixed $value Value to div
     *
     * @return Complex
     *
     * @throws \InvalidArgumentException This exception is thrown if the argument has an invalid type
     *
     * @since  1.0.0
     */
    public function div($value) : self
    {
        if (\is_numeric($value)) {
            return $this->divScalar($value);
        } elseif ($value instanceof self) {
            return $this->divComplex($value);
        }

        throw new \InvalidArgumentException();
    }

    /**
     * Div opperator
     *
     * @param Complex $cpl Value to div
     *
     * @return Complex
     *
     * @since  1.0.0
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
     * @param mixed $val Value to div
     *
     * @return Complex
     *
     * @since  1.0.0
     */
    private function divScalar($val) : self
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
     * @since  1.0.0
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
