<?php
/**
 * Orange Management
 *
 * PHP Version 7.1
 *
 * @package    TBD
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */
declare(strict_types = 1);

namespace phpOMS\Stdlib\Graph;

/**
 * Tree class.
 *
 * @package    Framework
 * @license    OMS License 1.0
 * @link       http://website.orange-management.de
 * @since      1.0.0
 */
class Edge
{
    private $node1 = null;

    private $node2 = null;

    private $directed = false;

    public function __construct(Node $node1, Node $node2, bool $directed = false) 
    {
        $this->node1 = $node1;
        $this->node2 = $node2;
        $this->directed = $directed;
    }

    public function getNodes() : array 
    {
        return [$this->node1, $this->node2];
    }

    public function isDirected() : bool
    {
        return $this->directed;
    }
}