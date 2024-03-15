<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Business\Recommendation
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Business\Recommendation;

/**
 * Bayesian Personalized Ranking (BPR)
 *
 * @package phpOMS\Business\Recommendation
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @see     https://arxiv.org/ftp/arxiv/papers/1205/1205.2618.pdf
 * @since   1.0.0
 *
 * @todo Implement, current implementation probably wrong
 */
final class BayesianPersonalizedRanking
{
    private int $numFactors;

    private float $learningRate;

    private float $regularization;

    private array $userFactors = [];

    private array $itemFactors = [];

    /**
     * Constructor.
     *
     * @param int   $numFactors     Determines the dimensionality of the latent factor space.
     * @param float $learningRate   Controls the step size for updating the latent factors during optimization.
     * @param float $regularization Prevents over-fitting by adding a penalty for large parameter values.
     *
     * @since 1.0.0
    */
    public function __construct(int $numFactors, float $learningRate, float $regularization)
    {
        $this->numFactors     = $numFactors;
        $this->learningRate   = $learningRate;
        $this->regularization = $regularization;
    }

    /**
     * Calculate random factors
     *
     * @return array
     *
     * @since 1.0.0
     */
    private function generateRandomFactors() : array
    {
        $factors = [];
        for ($i = 0; $i < $this->numFactors; ++$i) {
            $factors[$i] = \mt_rand() / \mt_getrandmax();
        }

        return $factors;
    }

    /**
     * @todo implement
     */
    public function predict($userId, $itemId) {
        $userFactor = $this->userFactors[$userId];
        $itemFactor = $this->itemFactors[$itemId];
        $score      = 0;

        for ($i = 0; $i < $this->numFactors; ++$i) {
            $score += $userFactor[$i] * $itemFactor[$i];
        }

        return $score;
    }

    /**
     * @todo implement
     */
    public function updateFactors($userId, $posItemId, $negItemId) : void
    {
        if (!isset($this->userFactors[$userId])) {
            $this->userFactors[$userId] = $this->generateRandomFactors();
        }

        if (!isset($this->itemFactors[$posItemId])) {
            $this->itemFactors[$posItemId] = $this->generateRandomFactors();
        }

        if (!isset($this->itemFactors[$negItemId])) {
            $this->itemFactors[$negItemId] = $this->generateRandomFactors();
        }

        $userFactor    = $this->userFactors[$userId];
        $posItemFactor = $this->itemFactors[$posItemId];
        $negItemFactor = $this->itemFactors[$negItemId];

        for ($i = 0; $i < $this->numFactors; ++$i) {
            $userFactor[$i]    += $this->learningRate * ($posItemFactor[$i] - $negItemFactor[$i]) - $this->regularization * $userFactor[$i];
            $posItemFactor[$i] += $this->learningRate * $userFactor[$i] - $this->regularization * $posItemFactor[$i];
            $negItemFactor[$i] += $this->learningRate * (-$userFactor[$i]) - $this->regularization * $negItemFactor[$i];
        }

        $this->userFactors[$userId]    = $userFactor;
        $this->itemFactors[$posItemId] = $posItemFactor;
        $this->itemFactors[$negItemId] = $negItemFactor;
    }
}
