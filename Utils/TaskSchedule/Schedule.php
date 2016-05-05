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
 * Array utils.
 *
 * @category   Framework
 * @package    Utils
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
class Schedule implements TaskInterface
{

    /**
     * Interval.
     *
     * @var Interval
     * @since 1.0.0
     */
    private $interval = null;
    private $command = '';

    public function __construct(Interval $interval = null, $cmd = '')
    {

    }

    public function setInterval(Interval $interval)
    {
        $this->interval = $interval;
    }

    public function setCommand(string $command)
    {
        $this->command = $command;
    }

    public function __toString()
    {
        return '';
    }
}
