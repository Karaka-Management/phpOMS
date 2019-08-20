<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package    phpOMS\Algorithm\PathFinding
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Algorithm\PathFinding;

/**
 * Grid of nodes.
 *
 * @package    phpOMS\Algorithm\PathFinding
 * @license    OMS License 1.0
 * @link       https://orange-management.org
 * @since      1.0.0
 */
class Grid
{
    private array $nodes = [];
    private ?Node $nullNode = null;
    
    public function getNullNode() : Node
    {
        return $this->nullNode;
    }
    
    public function setNullNode(Node $nullNode) : void
    {
        $this->nullNode = $nullNode;
    }
}
