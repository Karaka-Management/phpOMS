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
namespace phpOMS\Pattern;

/**
 * Mediator.
 *
 * @category   Pattern
 * @package    Framework
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
interface Mediator extends \Countable
{

    /**
     * Attach a listener.
     *
     * Listeners will get called if a certain event gets triggered
     *
     * @param string   $event    Event ID
     * @param \Closure $callback Function to call if the event gets triggered
     * @param string   $listener What class is attaching this listener
     *
     * @return string UID for the listener
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function attach(string $event, \Closure $callback, string $listener) : string;

    /**
     * Removing a event.
     *
     * @param string $event ID of the event
     *
     * @return bool
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function detachEvent(string $event) : bool;

    /**
     * Removing a listener.
     *
     * @param string $event    ID of the event
     * @param string $listener ID of the listener
     *
     * @return bool
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function detachListener(string $event, string $listener) : bool;

    /**
     * Trigger event.
     *
     * An object fires an event
     *
     * @param string   $event    Event ID
     * @param string   $source   What class is invoking this event
     * @param \Closure $callback Callback function of the event. This will get triggered after firering all listener callbacks.
     *
     * @return int
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function trigger(string $event, string $source, \Closure $callback = null) : int;
}
