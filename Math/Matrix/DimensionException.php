<?php
/**
 * Orange Management
 *
 * PHP Version 7.1
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
 * Permission exception class.
 *
 * @category   Framework
 * @package    phpOMS\System\File
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
class DimensionException extends \RuntimeException
{
    /**
     * Constructor.
     *
     * @param int     $m Dimension M
     * @param int     $n Dimension N
     * @param int        $code    Exception code
     * @param \Exception Previous exception
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function __construct($m, $n, int $code = 0, \Exception $previous = null)
    {
        parent::__construct('Dimension of "' . $n . '-' . $m . '" invalid.', $code, $previous);
    }
}
