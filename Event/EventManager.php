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
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://orange-management.com
 */
declare(strict_types=1);

namespace phpOMS\Event;

/**
 * EventManager class.
 *
 * @category   Framework
 * @package    phpOMS\Event
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 *
 * @todo       : make cachable + database storable -> can reload user defined listeners (persistent events)
 */
class EventManager
{
    /**
     * Events.
     *
     * @var array
     * @since 1.0.0
     */
    private $groups = [];

    /**
     * Callbacks.
     *
     * @var array
     * @since 1.0.0
     */
    private $callbacks = [];

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
    public function attach(string $group, \Closure $callback, bool $remove = false, bool $reset = false) : bool
    {
        if (isset($this->callbacks[$group])) {
            return false;
        }

        $this->callbacks[$group] = ['remove' => $remove, 'reset' => $reset, 'func' => $callback];

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function trigger(string $group, string $id = '', $data) /* : void */
    {
        if(!isset($this->callbacks[$group])) {
            return;
        }

        if (isset($this->groups[$group])) {
            $this->groups[$group][$id] = true;
        }

        if (!$this->hasOutstanding($group)) {
            $this->callbacks[$group]['func']($data);

            if ($this->callbacks[$group]['remove']) {
                $this->detach($group);
            } elseif($this->callbacks[$group]['reset']) {
                $this->reset($group);
            }
        }
    }

    private function reset(string $group) /* : void */
    {
        foreach($this->groups[$group] as $id => $ok) {
            $this->groups[$group][$id] = false;
        }
    }

    /**
     * {@inheritdoc}
     */
    private function hasOutstanding(string $group) : bool
    {
        if(!isset($this->groups[$group])) {
            return false;
        }

        foreach($this->groups[$group] as $id => $ok) {
            if(!$ok) {
                return true;
            }
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function detach(string $group) : bool
    {
        if (isset($this->callbacks[$group])) {
            unset($this->callbacks[$group]);
        }

        if (isset($this->groups[$group])) {
            unset($this->groups[$group]);
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function addGroup(string $group, string $id) /* : void */
    {
        if (!isset($this->groups[$group])) {
            $this->groups[$group] = [];
        }

        $this->groups[$group][$id] = false;
    }

    /**
     * Count events.
     *
     * @return int
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function count() : int
    {
        return count($this->callbacks);
    }

}
