<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\tests\Algorithm\Graph;

use phpOMS\Algorithm\Graph\MarkovChain;

require_once __DIR__ . '/../../Autoloader.php';

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Algorithm\Graph\MarkovChain::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Algorithm\Graph\MarkovChainTest:')]
final class MarkovChainTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testGenerate() : void
    {
        $markov = new MarkovChain();
        $markov->setTraining(
            [
                'A' => ['A' => 0.1, 'C' => 0.6, 'E' => 0.3],
                'C' => ['A' => 0.25, 'C' => 0.05, 'E' => 0.7],
                'E' => ['A' => 0.7, 'C' => 0.3, 'E' => 0.0],
            ]
        );

        self::assertEquals(3, \count($markov->generate(3, ['A'])));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testTrainingGenerate() : void
    {
        $markov = new MarkovChain();
        $markov->train(['A', 'C', 'E', 'A', 'C', 'E', 'E', 'C', 'A', 'A', 'E', 'A']);

        self::assertEquals(5, \count($markov->generate(5, ['A'])));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testStepProbability() : void
    {
        $markov = new MarkovChain(2);
        $markov->setTraining(
            [
                'A A' => ['A' => 0.18, 'D' => 0.6, 'G' => 0.22],
                'A D' => ['A' => 0.5, 'D' => 0.5, 'G' => 0.0],
                'A G' => ['A' => 0.15, 'D' => 0.75, 'G' => 0.1],
                'D D' => ['A' => 0.0, 'D' => 0.0, 'G' => 1.0],
                'D A' => ['A' => 0.25, 'D' => 0.0, 'G' => 0.75],
                'D G' => ['A' => 0.9, 'D' => 0.1, 'G' => 0.0],
                'G G' => ['A' => 0.4, 'D' => 0.4, 'G' => 0.2],
                'G A' => ['A' => 0.5, 'D' => 0.25, 'G' => 0.25],
                'G D' => ['A' => 1.0, 'D' => 0.0, 'G' => 0.0],
            ]
        );

        self::assertEquals(0.1, $markov->stepProbability(['D', 'G'], 'D'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testPathProbability() : void
    {
        $markov = new MarkovChain(2);
        $markov->setTraining(
            [
                'A A' => ['A' => 0.18, 'D' => 0.6, 'G' => 0.22],
                'A D' => ['A' => 0.5, 'D' => 0.5, 'G' => 0.0],
                'A G' => ['A' => 0.15, 'D' => 0.75, 'G' => 0.1],
                'D D' => ['A' => 0.0, 'D' => 0.0, 'G' => 1.0],
                'D A' => ['A' => 0.25, 'D' => 0.0, 'G' => 0.75],
                'D G' => ['A' => 0.9, 'D' => 0.1, 'G' => 0.0],
                'G G' => ['A' => 0.4, 'D' => 0.4, 'G' => 0.2],
                'G A' => ['A' => 0.5, 'D' => 0.25, 'G' => 0.25],
                'G D' => ['A' => 1.0, 'D' => 0.0, 'G' => 0.0],
            ]
        );

        self::assertEquals(0.9 * 0.5 * 0.6, $markov->pathProbability(['D', 'G', 'A', 'A', 'D']));
    }
}
