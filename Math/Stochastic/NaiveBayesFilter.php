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
class NaiveBayesFilter
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
    private array $probabilities = [];

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
        foreach ($matched as $dataset) {
            foreach ($dataset as $attr => $value) {
                if (!isset($this->dict[$criteria][$attr])) {
                    $this->dict[$criteria][$attr] = [];
                }

                if (\is_string($value) && !isset($this->dict[$criteria][$attr][$value])) {
                    $this->dict[$criteria][$attr][$value] = 0;
                } elseif (!\is_string($value) && !isset($this->dict[$criteria][$attr])) {
                    $this->dict[$criteria][$attr] = [];
                }

                if (\is_string($value)) {
                    ++$this->dict[$criteria][$attr][$value];
                } else {
                    $this->dict[$criteria][$attr][] = $value;
                }
            }
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

        $pTotalAttribute = 1;

        $evidence = 0;
        foreach ($this->probability as $criteriaKey => $data) {
            $temp = 1;
            foreach ($data as $attr => $value) {
                $temp *= 1 / \sqrt(2 * M_PI * $this->probability[$criteriaKey][$attr]['variance'])
                    * \exp(-($value - $this->probability[$criteriaKey][$attr]['mean']) ** 2 / (2 * $this->probability[$criteriaKey][$attr]['variance'] ** 2));
            }

            $evidence += ($this->probability[$criteria] / $this->probability['criteria_all']) * $temp;
        }

        $n = 0.0;
        foreach ($toMatch as $attr => $value) {
            if (!isset($this->dict[$criteria], $this->dict[$criteria][$attr])
                || (\is_string($value) && !isset($this->dict[$criteria][$attr][$value]))
            ) {
                continue;
            }

            if (\is_array($value)) {
                /** @var string[] $value */
                foreach ($value as $word) {
                    if (isset($this->dict[$criteria][$attr][$word])
                        && $this->dict[$criteria][$attr][$word] >= $minimum
                    ) {
                        $n += \log(1 - $this->dict[$criteria][$attr][$word]
                            / $this->probability['criteria_all'][$attr]
                        )
                        - \log($this->dict[$criteria][$attr][$word]
                            / $this->probability['criteria_all'][$attr]
                        );
                    }
                }
            } else {
                $p = 1 / \sqrt(2 * M_PI * $this->probability[$criteria][$attr]['variance'])
                    * \exp(-($value - $this->probability[$criteria][$attr]['mean']) ** 2 / (2 * $this->probability[$criteria][$attr]['variance'] ** 2));

                $pTotalAttribute *= $p;

                $n += \log(1 - $p) - \log($p);
            }
        }

        $pCriteria = $pTotalAttribute / $evidence;

        var_dump($pCriteria);
        var_dump($pTotalAttribute);
        var_dump(1 / (1 + \exp($n)));
        var_dump($n);
        var_dump($evidence);
        var_dump($this->probability);

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
        foreach ($this->dict as $criteria => $subDict) {
            if (!isset($this->probability[$criteria]['count'])) {
                $this->probability[$criteria]['count'] = 0;
            }

            ++$this->probability[$criteria]['count'];

            if (!isset($this->probability['criteria_all']['count'])) {
                $this->probability['criteria_all']['count'] = 0;
            }

            ++$this->probability['criteria_all']['count'];

            foreach ($subDict as $attr => $valueArray) {
                if (\is_string(\array_key_first($valueArray))) {
                    if (!isset($this->probability['criteria_all'][$attr])) {
                        $this->probability['criteria_all'][$attr] = 0;
                    }

                    foreach ($valueArray as $value => $data) {
                        $this->probability['criteria_all'][$attr] += $data;
                    }
                } else {
                    $this->probability[$criteria][$attr] = [
                        'mean'     => Average::arithmeticMean($this->dict[$criteria][$attr]),
                        'variance' => MeasureOfDispersion::empiricalVariance($this->dict[$criteria][$attr]),
                    ];
                }
            }
        }
    }
}
