<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   phpOMS\Business\Recommendation
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.2
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Business\Recommendation;

use phpOMS\Math\Statistic\Correlation;

/**
 * Article Affinity
 *
 * You can consider this as a "purchased with" or "customers also purchased" algorithm
 *
 * @package phpOMS\Business\Recommendation
 * @license OMS License 2.2
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class ArticleCorrelationAffinity
{
    /**
     * Affinity between items
     *
     * @var array[]
     * @since 1.0.0
     */
    private array $affinity = [];

    /**
     * Item order behavior (when are which items ordered)
     *
     * In terms of the pearson correlation these are our random variables
     *
     * @var array
     * @since 1.0.0
     */
    private array $items = [];

    /**
     * Constructor
     *
     * @param array[] $orders           Array of orders which contains as elements the items ordered in the respective order
     * @param bool    $considerQuantity NOT_IMPLEMENTED!!! Should the quantity be considered
     *
     * @since 1.0.0
     */
    public function __construct(array $orders, bool $considerQuantity = false)
    {
        // find all possible items
        $possibleItems = [];
        foreach ($orders as $items) {
            foreach ($items as $item => $quantity) {
                if (!\in_array($item, $possibleItems)) {
                    $possibleItems[] = $item;
                }
            }
        }

        // create the random variables
        foreach ($orders as $items) {
            foreach ($possibleItems as $item) {
                $this->items[$item][] = $considerQuantity ? $items[$item] : (int) ($items[$item] > 0);
            }
        }

        // create the affinity table
        foreach ($possibleItems as $item1) {
            foreach ($possibleItems as $item2) {
                if ($item1 !== $item2
                    && !isset($this->affinity[$item1][$item2])
                    && !isset($this->affinity[$item2][$item1])
                ) {
                    $this->affinity[$item1][$item2] = Correlation::bravaisPersonCorrelationCoefficientPopulation($this->items[$item1], $this->items[$item2]);
                    $this->affinity[$item2][$item1] = $this->affinity[$item1][$item2]; // php automatically creates references!
                }
            }
        }

        // sort correlations
        foreach ($possibleItems as $item) {
            \arsort($this->affinity[$item]);
        }
    }

    /**
     * Get the affinity between items
     *
     * @param mixed $item       Item to check for possible affinities
     * @param int   $resultSize How many top matches should be returned
     *
     * @return array
     *
     * @since 1.0.0
     */
    public function getAffinity(mixed $item, int $resultSize = 0) : array
    {
        if (!isset($this->affinity[$item])) {
            return [];
        }

        return $resultSize < 1 ? $this->affinity[$item] : \array_slice($this->affinity[$item], 0, $resultSize);
    }
}
