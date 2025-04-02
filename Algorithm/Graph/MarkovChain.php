<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   phpOMS\Algorithm\Graph
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.2
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Algorithm\Graph;

/**
 * Markov chain
 *
 * @package phpOMS\Algorithm\Graph
 * @license OMS License 2.2
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class MarkovChain
{
    /**
     * Order of the markov chain
     *
     * @var int
     * @since 1.0.0
     */
    private int $order = 1;

    /**
     * Trained data
     *
     * @var array
     * @since 1.0.0
     */
    private array $data = [];

    /**
     * Constructor
     *
     * @param int $order Order of the markov chain
     *
     * @since 1.0.0
     */
    public function __construct(int $order = 1)
    {
        $this->order = $order;
    }

    /**
     * Create markov chain based on input
     *
     * @param array $values Training values
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function train(array $values) : void
    {
        $temp   = [];
        $length = \count($values) - $this->order;

        $unique = \array_unique($values);

        for ($i = 0; $i < $length; ++$i) {
            $key = [];
            for ($j = 0; $j < $this->order; ++$j) {
                $key[] = $values[$i + $j];
            }

            $keyString = \implode(' ', $key);

            if (!isset($temp[$keyString])) {
                foreach ($unique as $value) {
                    $temp[$keyString][$value] = 0;
                }
            }

            ++$temp[$keyString][$values[$i + 1]];
        }

        foreach ($temp as $key => $values) {
            $sum = \array_sum($values);
            foreach ($values as $idx => $value) {
                $this->data[$key][$idx] = $value / $sum;
            }
        }
    }

    /**
     * Set training data
     *
     * @param array<array<int, int>> $values Training values
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setTraining(array $values) : void
    {
        $this->data = $values;
    }

    /**
     * Generate a markov chain based on the training data.
     *
     * @param int   $length Length of the markov chain
     * @param array $start  Start values of the markov chain
     *
     * @return array
     *
     * @since 1.0.0
     */
    public function generate(int $length, ?array $start = null) : array
    {
        $orderKeys   = \array_keys($this->data);
        $orderValues = \array_keys(\reset($this->data));

        $output = $start ?? \explode(' ', $orderKeys[\array_rand($orderKeys)]);
        $key    = $output;

        for ($i = $this->order; $i < $length; ++$i) {
            $keyString = \implode(' ', $key);

            $prob  = \mt_rand(1, 100) / 100;
            $cProb = 0.0;
            $val   = null;
            $new   = null;

            foreach (($this->data[$keyString] ?? []) as $val => $p) {
                $cProb += $p;

                if ($prob <= $cProb) {
                    $new = $val;

                    break;
                }
            }

            // Couldn't find possible key
            $new ??= $orderValues[\array_rand($orderValues)];

            $output[] = $new;
            $key[]    = $new;

            \array_shift($key);
        }

        return $output;
    }

    /**
     * Calculate the probability for a certain markov chain.
     *
     * @param array $path Markov chain
     *
     * @return float
     *
     * @since 1.0.0
     */
    public function pathProbability(array $path) : float
    {
        $length = \count($path);
        if ($length <= $this->order) {
            return 0.0;
        }

        $key = \array_slice($path, 0, $this->order);

        $prob = 1.0;
        for ($i = $this->order; $i < $length; ++$i) {
            $prob *= $this->data[\implode(' ', $key)][$path[$i]] ?? 0.0;

            $key[] = $path[$i];
            \array_shift($key);
        }

        return $prob;
    }

    /**
     * Calculate the probability for a certain state change in a markov chain
     *
     * @param array $state Current state of the markov chain
     * @param mixed $next  Next markov state
     *
     * @return float
     *
     * @since 1.0.0
     */
    public function stepProbability(array $state, mixed $next) : float
    {
        if (\count($state) !== $this->order) {
            return 0.0;
        }

        return $this->data[\implode(' ', $state)][$next] ?? 0.0;
    }
}
