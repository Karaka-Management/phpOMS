<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\tests\Algorithm\PathFinding;

use phpOMS\Algorithm\PathFinding\Grid;
use phpOMS\Algorithm\PathFinding\MovementType;
use phpOMS\Algorithm\PathFinding\HeuristicType;
use phpOMS\Algorithm\PathFinding\JumpPointNode;
use phpOMS\Algorithm\PathFinding\JumpPointSearch;

require_once __DIR__ . '/../../Autoloader.php';

/**
 * @testdox phpOMS\tests\Algorithm\PathFinding: jump point search test
 *
 * @internal
 */
class JumpPointSearchTest extends \PHPUnit\Framework\TestCase
{
    private array $gridArray = [
        [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0,],
        [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 9, 0, 0, 0, 0,],
        [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0,],
        [0, 0, 9, 9, 9, 0, 0, 0, 0, 0, 9, 0, 0, 0, 0,],
        [0, 0, 0, 0, 9, 9, 9, 9, 9, 0, 9, 0, 0, 0, 0,],
        [0, 0, 1, 0, 9, 0, 0, 0, 0, 0, 9, 0, 9, 9, 9,],
        [0, 0, 0, 0, 9, 0, 0, 9, 9, 9, 9, 0, 0, 0, 0,],
        [0, 0, 9, 9, 9, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0,],
        [0, 0, 0, 9, 0, 0, 0, 9, 0, 0, 9, 9, 9, 9, 0,],
        [0, 0, 0, 9, 0, 0, 0, 9, 0, 0, 0, 0, 0, 0, 0,],
        [0, 0, 0, 9, 0, 0, 0, 9, 0, 0, 9, 9, 0, 0, 0,],
        [0, 0, 0, 0, 0, 9, 9, 9, 0, 0, 9, 2, 0, 0, 0,],
        [0, 0, 0, 0, 0, 9, 0, 0, 0, 0, 9, 9, 0, 0, 0,],
        [0, 0, 0, 0, 0, 9, 0, 0, 0, 0, 0, 0, 0, 0, 0,],
        [0, 0, 0, 0, 0, 9, 0, 0, 0, 0, 0, 0, 0, 0, 0,],
    ];

    public function testPathFinding() : void
    {
        $grid = Grid::createGridFromArray($this->gridArray, JumpPointNode::class);
        /*$path = JumpPointSearch::findPath(
            2, 5,
            11, 11,
            $grid, HeuristicType::EUCLIDEAN, MovementType::DIAGONAL
        );*/
    }
}
