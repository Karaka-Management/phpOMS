<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   phpOMS\Math\Stochastic
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);
namespace phpOMS\Math\Stochastic;

use phpOMS\Math\Statistic\Average;
use phpOMS\Math\Statistic\MeasureOfDispersion;

/**
 * Naive bayes matching.
 *
 * @package phpOMS\Math\Stochastic
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
class NaiveBayesClassifier
{
    /**
     * Dictionary of different criterias.
     *
     * @var   array
     * @since 1.0.0
     */
    private array $dict = [];

    /**
     * Dictionary changed.
     *
     * @var   bool
     * @since 1.0.0
     */
    private bool $changed = true;

    /**
     * Cached probabilities.
     *
     * @var   array
     * @since 1.0.0
     */
    private array $probabilities = [
        'count'    => 0,
        'criteria' => [],
        'attr'     => [],
    ];

    /**
     * Train matches.
     *
     * @param string $criteria Criteria to match against
     * @param array  $matched  Matches
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function train(string $criteria, array $matched) : void
    {
        if (!isset($this->probabilities['criteria'][$criteria])) {
            $this->probabilities['criteria'][$criteria] = [
                'count' => 0,
                'attr'  => [],
            ];
        }

        foreach ($matched as $dataset) {
            foreach ($dataset as $attr => $value) {
                if (!isset($this->dict[$criteria][$attr])) {
                    $this->dict[$criteria][$attr] = [
                        'type'  => \is_array($value) ? 1 : 2,
                        'data'  => [],
                    ];
                }

                if (!isset($this->probabilities['attr'][$attr])) {
                    $this->probabilities['attr'][$attr] = [
                        'count'    => 0,
                        'data'     => [],
                    ];
                }

                if (!isset($this->probabilities['criteria'][$criteria]['attr'][$attr])) {
                    $this->probabilities['criteria'][$criteria]['attr'][$attr] = [
                        'count'    => 0,
                        'mean'     => 0,
                        'variance' => 0,
                    ];
                }

                if (\is_array($value)) {
                    foreach ($value as $word) {
                        if (!isset($this->dict[$criteria][$attr]['data'][$word])) {
                            $this->dict[$criteria][$attr]['data'][$word] = 0;
                        }

                        ++$this->dict[$criteria][$attr]['data'][$word];
                        ++$this->probabilities['attr'][$attr]['count'];
                    }
                } else {
                    $this->dict[$criteria][$attr]['data'][] = $value;

                    ++$this->probabilities['attr'][$attr]['count'];
                    ++$this->probabilities['criteria'][$criteria]['attr'][$attr]['count'];
                }
            }

            ++$this->probabilities['criteria'][$criteria]['count'];
            ++$this->probabilities['count'];
        }

        $this->changed = true;
    }

    /**
     * Check against matches.
     *
     * @param string $criteria Criteria to match against
     * @param array  $toMatch  Values to match
     * @param int    $minimum  Minimum amount of ocurances for consideration
     *
     * @return float
     *
     * @since 1.0.0
     */
    public function match(string $criteria, array $toMatch, int $minimum = 3) : float
    {
        if ($this->changed) {
            $this->cache();
        }

        $this->changed = false;

        $n = 0.0;
        foreach ($toMatch as $attr => $value) {
            if (!isset($this->dict[$criteria], $this->dict[$criteria][$attr])) {
                continue;
            }

            if (\is_array($value)) {
                /** @var string[] $value */
                foreach ($value as $word) {
                    if (isset($this->dict[$criteria][$attr]['data'][$word])
                        && $this->dict[$criteria][$attr]['data'][$word] >= $minimum
                    ) {
                        $p = ($this->dict[$criteria][$attr]['data'][$word] / \array_sum($this->dict[$criteria][$attr]['data']))
                            * ($this->probabilities['criteria'][$criteria]['count'] / $this->probabilities['count'])
                            / $this->probabilities['attr'][$attr]['data'][$word];

                        $n += \log(1 - $p) - \log($p);
                    }
                }
            } else {
                // todo: add probability of criteria / total?
                $p = 1 / \sqrt(2 * \M_PI * $this->probabilities['criteria'][$criteria]['attr'][$attr]['variance'])
                    * \exp(-($value - $this->probabilities['criteria'][$criteria]['attr'][$attr]['mean']) / (2 * $this->probabilities['criteria'][$criteria]['attr'][$attr]['variance']));

                //var_dump($p);

                $n += \log(1 - $p) - \log($p);
            }
        }

        return 1 / (1 + \exp($n));
    }

    /**
     * Cache probabilities for matching function.
     *
     * @return void
     *
     * @since 1.0.0
     */
    private function cache() : void
    {
        $this->probabilities['attr'] = [];

        foreach ($this->dict as $criteria => $subDict) {
            foreach ($subDict as $attr => $valueArray) {
                if ($valueArray['type'] === 2) {
                    $this->probabilities['criteria'][$criteria]['attr'][$attr]['mean']     = Average::arithmeticMean($this->dict[$criteria][$attr]['data']);
                    $this->probabilities['criteria'][$criteria]['attr'][$attr]['variance'] = MeasureOfDispersion::empiricalVariance($this->dict[$criteria][$attr]['data'], [], $this->probabilities['criteria'][$criteria]['attr'][$attr]['mean']);

                    // \var_dump($criteria);
                    // \var_dump($attr);
                    // \var_dump($this->probabilities['criteria'][$criteria]['attr'][$attr]['mean']); // good
                    // \var_dump($this->probabilities['criteria'][$criteria]['attr'][$attr]['variance']); // bad
                } else {
                    if (!isset( $this->probabilities['attr'][$attr])) {
                        $this->probabilities['attr'] = [$attr => ['data' => []]];
                    }

                    foreach ($valueArray['data'] as $word => $count) {
                        if (!isset($this->dict[$criteria][$attr]['data'][$word])) {
                            continue;
                        }

                        if (!isset($this->probabilities['attr'][$attr]['data'][$word])) {
                            $this->probabilities['attr'][$attr]['data'][$word] = 0.0;
                        }

                        $this->probabilities['attr'][$attr]['data'][$word] += ($this->dict[$criteria][$attr]['data'][$word] / \array_sum($this->dict[$criteria][$attr]['data']))
                            * ($this->probabilities['criteria'][$criteria]['count'] / $this->probabilities['count']);
                    }
                }
            }
        }
    }
}
