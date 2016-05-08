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
namespace phpOMS\Utils\TaskSchedule;

/**
 * Schedule class.
 *
 * @category   Framework
 * @package    phpOMS\Utils\TaskSchedule
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
class Schedule extends TaskAbstract
{
    /**
     * Constructor.
     *
     * @param Interval $interval Interval
     * @param string   $cmd      Command to execute
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function __construct(Interval $interval = null, string $cmd = '')
    {
        if (!isset($interval)) {
            $this->interval = new Interval();
        } else {
            $this->interval = $interval;
        }

        $this->command = $cmd;
    }

    public function __toString()
    {
        return '';
    }
}
