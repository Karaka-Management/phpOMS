<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package    phpOMS\Event
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Event;

use phpOMS\Dispatcher\Dispatcher;

/**
 * EventManager class.
 *
 * The event manager allows to define events which can be triggered/executed in an application.
 * This implementation allows to create sub-conditions which need to be met (triggered in advance) bevore the actual
 * callback is getting executed.
 *
 * What happens after triggering an event (removing the callback, resetting the sub-conditions etc.) depends on the setup.
 *
 * @package    phpOMS\Event
 * @license    OMS License 1.0
 * @link       https://orange-management.org
 * @since      1.0.0
 */
final class EventManager implements \Countable
{
    /**
     * Events.
     *
     * @var array
     * @since 1.0.0
     */
    private array $groups = [];

    /**
     * Callbacks.
     *
     * @var array
     * @since 1.0.0
     */
    private array $callbacks = [];

    /**
     * Dispatcher.
     *
     * @var Dispatcher|Object<dispatch>
     * @since 1.0.0
     */
    private ?Dispatcher $dispatcher = null;

    /**
     * Constructor.
     *
     * @param Dispatcher $dispatcher Dispatcher. If no dispatcher is provided a simple general purpose dispatcher is used.
     *
     * @since  1.0.0
     */
    public function __construct(Dispatcher $dispatcher = null)
    {
        $this->dispatcher = $dispatcher ?? new class() {
            public function dispatch($func, ...$data) : void
            {
                $func(...$data);
            }
        };
    }

    /**
     * Add events from file.
     *
     * Files need to return a php array of the following structure:
     * return [
     *      '{EVENT_ID}' => [
     *          'callback' => [
     *              '{DESTINATION_NAMESPACE:method}', // can also be static by using :: between namespace and functio name
     *              // more callbacks here
     *          ],
     *      ],
     * ];
     *
     * @param string $path Hook file path
     *
     * @return bool
     *
     * @since  1.0.0
     */
    public function importFromFile(string $path) : bool
    {
        if (!\file_exists($path)) {
            return false;
        }

        /** @noinspection PhpIncludeInspection */
        $hooks = include $path;

        foreach ($hooks as $group => $hook) {
            foreach ($hook['callback'] as $callback) {
                $this->attach($group, $callback, $hook['remove'] ?? false, $hook['reset'] ?? true);
            }
        }

        return true;
    }

    /**
     * Attach new event
     *
     * @param string $group    Name of the event (unique)
     * @param mixed  $callback Callback or route for the event
     * @param bool   $remove   Remove event after triggering it?
     * @param bool   $reset    Reset event after triggering it? Remove must be false!
     *
     * @return bool
     *
     * @since  1.0.0
     */
    public function attach(string $group, $callback, bool $remove = false, bool $reset = false) : bool
    {
        if (!isset($this->callbacks[$group])) {
            $this->callbacks[$group] = ['remove' => $remove, 'reset' => $reset, 'callbacks' => []];
        }

        $this->callbacks[$group]['callbacks'][] = $callback;

        return true;
    }

    /**
     * Trigger event
     *
     * @param string $group Name of the event
     * @param string $id    Sub-requirement for event
     * @param mixed  $data  Data to pass to the callback
     *
     * @return bool returns true on sucessfully triggering the event, false if the event couldn't be triggered which also includes sub-requirements missing
     *
     * @since  1.0.0
     */
    public function trigger(string $group, string $id = '', $data = null) : bool
    {
        if (isset($this->callbacks[$group])) {
            return $this->triggerSingleEvent($group, $id, $data);
        }

        $allGroups = \array_keys($this->callbacks);
        $result    = false;

        foreach ($allGroups as $match) {
            if (\preg_match('~^' . $match . '$~', $group) === 1) {
                $result = $result || $this->triggerSingleEvent($match, $id, $data);
            }
        }

        return $result;
    }

    /**
     * Trigger event
     *
     * @param string $group Name of the event
     * @param string $id    Sub-requirement for event
     * @param mixed  $data  Data to pass to the callback
     *
     * @return bool returns true on sucessfully triggering the event, false if the event couldn't be triggered which also includes sub-requirements missing
     *
     * @since  1.0.0
     */
    private function triggerSingleEvent(string $group, string $id = '', $data = null) : bool
    {
        if (isset($this->groups[$group])) {
            $this->groups[$group][$id] = true;
        }

        if (!$this->hasOutstanding($group)) {
            foreach ($this->callbacks[$group]['callbacks'] as $func) {
                if (\is_array($data)) {
                    $this->dispatcher->dispatch($func, ...$data);
                } else {
                    $this->dispatcher->dispatch($func, $data);
                }
            }

            if ($this->callbacks[$group]['remove']) {
                $this->detach($group);
            } elseif ($this->callbacks[$group]['reset']) {
                $this->reset($group);
            }

            return true;
        }

        return false;
    }

    /**
     * Reset group
     *
     * @param string $group Name of the event
     *
     * @return void
     *
     * @since  1.0.0
     */
    private function reset(string $group) : void
    {
        if (!isset($this->groups[$group])) {
            return;
        }

        foreach ($this->groups[$group] as $id => $ok) {
            $this->groups[$group][$id] = false;
        }
    }

    /**
     * Check if a group has missing sub-requirements
     *
     * @param string $group Name of the event
     *
     * @return bool
     *
     * @since  1.0.0
     */
    private function hasOutstanding(string $group) : bool
    {
        if (!isset($this->groups[$group])) {
            return false;
        }

        foreach ($this->groups[$group] as $id => $ok) {
            if (!$ok) {
                return true;
            }
        }

        return false;
    }

    /**
     * Detach an event
     *
     * @param string $group Name of the event
     *
     * @return bool
     *
     * @since  1.0.0
     */
    public function detach(string $group) : bool
    {
        $result1 = $this->detachCallback($group);
        $result2 = $this->detachGroup($group);

        return $result1 || $result2;
    }

    /**
     * Detach an event
     *
     * @param string $group Name of the event
     *
     * @return bool
     *
     * @since  1.0.0
     */
    private function detachCallback(string $group) : bool
    {
        if (isset($this->callbacks[$group])) {
            unset($this->callbacks[$group]);
            return true;
        }

        return false;
    }

    /**
     * Detach an event
     *
     * @param string $group Name of the event
     *
     * @return bool
     *
     * @since  1.0.0
     */
    private function detachGroup(string $group) : bool
    {
        if (isset($this->groups[$group])) {
            unset($this->groups[$group]);
            return true;
        }

        return false;
    }

    /**
     * Add sub-requirement for event
     *
     * @param string $group Name of the event
     * @param string $id    ID of the sub-requirement
     *
     * @return void
     *
     * @since  1.0.0
     */
    public function addGroup(string $group, string $id) : void
    {
        if (!isset($this->groups[$group])) {
            $this->groups[$group] = [];
        }

        $this->groups[$group][$id] = false;
    }

    /**
     * {@inheritdoc}
     */
    public function count() : int
    {
        return \count($this->callbacks);
    }
}
