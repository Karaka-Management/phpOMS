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
 * Abstract task class.
 *
 * @category   Framework
 * @package    phpOMS\Utils\TaskSchedule
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
abstract class TaskAbstract
{
    /**
     * Id.
     *
     * @var string
     * @since 1.0.0
     */
    protected $id = '';

    /**
     * Interval.
     *
     * @var Interval
     * @since 1.0.0
     */
    protected $interval = null;

    /**
     * Command.
     *
     * @var string
     * @since 1.0.0
     */
    protected $command = '';

    /**
     * Get id.
     *
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getId() : string
    {
        return $this->id;
    }

    /**
     * Get interval.
     *
     * @return Interval
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getInterval() : Interval
    {
        return $this->interval;
    }

    /**
     * Set interval.
     *
     * @param Interval $interval Interval
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function setInterval(Interval $interval)
    {
        $this->interval = $interval;
    }

    /**
     * Get command.
     *
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getCommand() : string
    {
        return $this->command;
    }

    /**
     * Set command.
     *
     * @param string $command Command
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function setCommand(string $command)
    {
        $this->command = $command;
    }
}
