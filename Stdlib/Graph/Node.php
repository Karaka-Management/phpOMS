<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   phpOMS\Stdlib\Graph
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Stdlib\Graph;

/**
 * Node class.
 *
 * @package phpOMS\Stdlib\Graph
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
class Node
{
    /**
     * Node data.
     *
     * @var   mixed
     * @since 1.0.0
     */
    private $data = null;

    /**
     * Constructor.
     *
     * @param mixed $data Node data
     *
     * @since 1.0.0
     */
    public function __construct($data = null)
    {
        $this->data = $data;
    }

    /**
     * Get data.
     *
     * @return mixed
     *
     * @since 1.0.0
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Set data.
     *
     * @param mixed $data Node data
     *
     * @since 1.0.0
     */
    public function setData($data) : void
    {
        $this->data = $data;
    }
}
