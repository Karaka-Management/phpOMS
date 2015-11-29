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
use phpOMS\Utils\ArrayUtils;

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
    public function attach(\string $event, \Closure $callback = null, \string $listener = null) : \string
    {
        $this->events[$event][$listener] = $callback;

        return $event . '/' . $listener;
    }

    /**
     * {@inheritdoc}
     */
    public function trigger(\string $event, \Closure $callback = null, \string $source = null) : \int
    {
        $count = 0;
        foreach ($this->events[$event] as $event) {
            $event($source);
            $count++;
        }

        if (isset($callback)) {
            /** @var $callback Callable */
            $callback();
        }

        return $count;
    }

    /**
     * Trigger event.
     *
     * An object fires an event until it's callback returns false
     *
     * @param \string  $event    Event ID
     * @param \Closure $callback Callback function of the event. This will get triggered after firering all listener callbacks.
     * @param \string  $source   What class is invoking this event
     *
     * @return \int
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function triggerUntil(\string $event, \Closure $callback = null, \string $source = null) : \int
    {
        $run   = true;
        $count = 0;

        do {
            foreach ($this->events[$event] as $event) {
                $run = $event($source);
                $count++;
            }
        } while ($run);

        if ($callback !== null) {
            $callback();
        }

        return $count;
    }

    /**
     * {@inheritdoc}
     */
    public function detach(\int $event)
    {
        $this->events = ArrayUtils::unsetArray($event, $this->events, '/');
    }

    /**
     * Count event listenings.
     *
     * @return \int
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function count() : \int
    {
        return count($this->events);
    }

}
