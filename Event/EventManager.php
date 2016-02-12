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
namespace phpOMS\Event;

use phpOMS\Pattern\Mediator;

/**
 * EventManager class.
 *
 * @category   Framework
 * @package    phpOMS\Event
 * @since      1.0.0
 *
 * @todo       : make cachable + database storable -> can reload user defined listeners (persistent events)
 */
class EventManager implements Mediator
{
    const DELIM = ':';

    /**
     * Events.
     *
     * @var array
     * @since 1.0.0
     */
    private $events = [];

    /**
     * Constructor.
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function __construct()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function attach(string $event, \Closure $callback, string $listener) : string
    {
        $this->events[$event][$listener] = $callback;

        return $event . '/' . $listener;
    }

    /**
     * {@inheritdoc}
     */
    public function trigger(string $event, string $source, \Closure $callback = null) : int
    {
        $count = 0;

        if (isset($this->events[$event])) {
            foreach ($this->events[$event] as $listener) {
                foreach ($listener as $closure) {
                    $closure($source);
                    $count++;
                }
            }
        }

        if (isset($callback)) {
            /** @var $callback Callable */
            $callback($count);
        }

        return $count;
    }

    /**
     * Trigger event.
     *
     * An object fires an event until it's callback returns false
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
    public function triggerUntil(string $event, string $source, \Closure $callback = null) : int
    {
        $run   = true;
        $count = 0;

        if (isset($this->events[$event])) {
            do {
                foreach ($this->events[$event] as $eventClosure) {
                    $run = $eventClosure($source);
                    $count++;
                }
            } while ($run);
        }

        if ($callback !== null) {
            $callback($count);
        }

        return $count;
    }

    /**
     * {@inheritdoc}
     */
    public function detachListener(string $event, string $listener) : bool
    {
        if (isset($this->events[$event][$listener])) {
            unset($this->events[$event][$listener]);

            return true;
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function detachEvent(string $event) : bool
    {
        if (isset($this->events[$event])) {
            unset($this->events[$event]);

            return true;
        }

        return false;
    }

    /**
     * Count event listenings.
     *
     * @return int
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function count() : int
    {
        return count($this->events);
    }

}
