<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Math\Matrix
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Math\Matrix;

/**
 * Identity Matrix
 *
 * @package phpOMS\Math\Matrix
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class IdentityMatrix extends Matrix
{
    /**
     * Constructor.
     *
     * @param int<0, max> $n Matrix dimension
     *
     * @since 1.0.0
     */
    public function __construct(int $n)
    {
        parent::__construct($n, $n);

        for ($i = 0; $i < $n; ++$i) {
            $this->matrix[$i][$i] = 1;
        }
    }
}
