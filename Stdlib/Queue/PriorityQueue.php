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
namespace phpOMS\Stdlib\Queue;

/**
 * Router class.
 *
 * @category   Framework
 * @package    phpOMS\Stdlib
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
class PriorityQueue implements \Countable, \Serializable
{

    /**
     * Queue elements.
     *
     * @var int
     * @since 1.0.0
     */
    private $count = 0;

    /**
     * Queue.
     *
     * @var array
     * @since 1.0.0
     */
    private $queue = [];

    private $mode = 0;

    /**
     * Constructor.
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function __construct($mode = '')
    {
    }

    /**
     * Insert element into queue.
     *
     * @param mixed $data     Queue element
     * @param float $priority Priority of this element
     *
     * @return int
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function insert($data, float $priority = 1.0) : int
    {
        do {
            $key = rand();
        } while (array_key_exists($key, $this->queue));

        if ($this->count === 0) {
            $this->queue[$key] = ['key' => $key, 'job' => $job, 'priority' => $priority];
        } else {
            $pos = 0;
            foreach ($this->queue as $ele) {
                if ($ele['priority'] < $priority) {
                    break;
                }

                $pos++;
            }

            array_splice($original, $pos, 0, [$key => ['key' => $key, 'job' => $job, 'priority' => $priority]]);
        }

        return $key;
    }

    /**
     * Increase all queue priorities.
     *
     * @param float $increase Value to increase every element
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function increaseAll(float $increase = 0.1)
    {
        foreach ($this->queue as $key => &$ele) {
            $ele['priority'] += $increase;
        }
    }

    /**
     * Pop element.
     *
     * @return mixed
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function pop()
    {
        return array_pop($this->queue);
    }

    /**
     * Delete element.
     *
     * @param int $id Element to delete
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function delete(int $id = null)
    {
        if ($id === null) {
            $this->remove();
        } else {
            unset($this->queue[$id]);
        }
    }

    /**
     * Delete last element.
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function remove()
    {
        return array_pop($this->queue);
    }

    /**
     * Set element priority.
     *
     * @param int   $id       Element ID
     * @param float $priority Element priority
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function setPriority(int $id, float $priority) /* : void */
    {
        $this->queue[$id]['priority'] = $priority;
    }

    /**
     * Set element priority.
     *
     * @param int $id Element ID
     *
     * @return float
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getPriority(int $id) : float
    {
        return $this->queue[$id]['priority'];
    }

    /**
     * {@inheritdoc}
     */
    public function count() : int
    {
        return $this->count;
    }

    /**
     * {@inheritdoc}
     */
    public function serialize() : string
    {
        return json_encode($this->queue);
    }

    /**
     * Unserialize queue.
     *
     * @param string $data Data to unserialze
     *
     * @return array
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function unserialize($data) : array
    {
        $this->queue = json_decode($data);
        $this->count = count($this->queue);
    }
}
