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
namespace phpOMS\DataStorage\Database\Schema\Exception;

/**
 * Path exception class.
 *
 * @category   System
 * @package    Framework
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
class TableException extends \PDOException
{
    /**
     * Constructor.
     *
     * @param string     $message Exception message
     * @param int        $code    Exception code
     * @param \Exception Previous exception
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function __construct(string $message, int $code = 0, \Exception $previous = null)
    {
        parent::__construct('The table "' . $message . '" doesn\'t exist.', $code, $previous);
    }

    public static function findTable(string $message) : string
    {
        $pos1 = strpos($message, '\'');

        if ($pos1 === false) {
            return $message;
        }

        $pos2 = strpos($message, '\'', $pos1 + 1);

        if ($pos2 === false) {
            return $message;
        }

        return substr($message, $pos1 + 1, $pos2 - $pos1 - 1);
    }
}
