<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Math\Number
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Math\Number;

/**
 * Basic operation interface.
 *
 * @package phpOMS\Math\Number
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
interface OperationInterface
{
    /**
     * Add value.
     *
     * @param mixed $x Value
     *
     * @return mixed
     *
     * @since 1.0.0
     */
    public function add(mixed $x);

    /**
     * Subtract value.
     *
     * @param mixed $x Value
     *
     * @return mixed
     *
     * @since 1.0.0
     */
    public function sub(mixed $x);

    /**
     * Right multiplicate value.
     *
     * @param mixed $x Value
     *
     * @return mixed
     *
     * @since 1.0.0
     */
    public function mult(mixed $x);

    /**
     * Right devision value.
     *
     * @param mixed $x Value
     *
     * @return mixed
     *
     * @since 1.0.0
     */
    public function div(mixed $x);

    /**
     * Power of value.
     *
     * @param mixed $p Power
     *
     * @return mixed
     *
     * @since 1.0.0
     */
    public function pow(mixed $p);

    /**
     * Abs of value.
     *
     * @return mixed
     *
     * @since 1.0.0
     */
    public function abs() : mixed;
}
