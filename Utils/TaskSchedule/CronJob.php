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
class CronJob implements TaskInterface
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
        $this->interval = $interval;
        $this->cmd      = $cmd;
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
        $minute     = $this->printValue($this->interval->getMinute());
        $hour       = $this->printValue($this->interval->getHour());
        $dayOfMonth = $this->printValue($this->interval->getDayOfMonth());
        $month      = $this->printValue($this->interval->getMonth());
        $dayOfWeek  = $this->printValue($this->interval->getDayOfWeek());

        return $minute . ' ' . $hour . ' ' . $dayOfMonth . ' ' . $month . ' ' . $dayOfWeek . ' ' . $this->command;
    }

    private function printValue(array $value) : string
    {
        if (($count = count($value['dayOfWeek'])) > 0) {
            $parsed = implode(',', $value['dayOfWeek']);
        } elseif ($value['start'] !== 0 && $value['end']) {
            $parsed = $value['start'] . '-' . $value['end'];
            $count  = 2;
        } else {
            $parsed = '*';
            $count  = 1;
        }

        if ($count === 0 && $value['step'] !== 0) {
            $parsed .= '/' . $value['step'];
        }

        return $parsed;
    }
}
