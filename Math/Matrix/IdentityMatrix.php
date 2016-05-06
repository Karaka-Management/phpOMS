<?php
/**
 * Orange Management
 *
 * PHP Version 7.0
 *
 * @category   TBD
 * @package    TBD
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @copyright  2013 Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://orange-management.com
 */

namespace phpOMS\Math\Matrix;

/**
 * Matrix class
 *
 * @category   Framework
 * @package    phpOMS\Math\Matrix
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
class IdentityMatrix extends Matrix
{
    /**
     * Constructor.
     *
     * @param int $n Matrix dimension
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function __constrcut(int $n)
    {
        $this->n = $n;
        $this->m = $n;

        for ($i = 0; $i < $n; $i++) {
            $this->matrix[$i]     = array_fill(0, $n, 0);
            $this->matrix[$i][$i] = 1;
        }
    }
}