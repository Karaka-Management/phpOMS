<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Algorithm\Rating
 * @copyright Microsoft
 * @license   This algorithm may be patented by Microsoft, verify and acquire a license if necessary
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Algorithm\Rating;

use phpOMS\Math\Stochastic\Distribution\NormalDistribution;

/**
 * Elo rating calculation using Elo rating
 *
 * @package phpOMS\Algorithm\Rating
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 * @see     https://www.moserware.com/assets/computing-your-skill/The%20Math%20Behind%20TrueSkill.pdf
 *
 * @todo Implement https://github.com/sublee/trueskill/blob/master/trueskill/__init__.py
 *      https://github.com/Karaka-Management/phpOMS/issues/337
 */
class TrueSkill
{
    public const DEFAULT_MU = 25;

    public const DEFAULT_SIGMA = 25 / 3;

    public const DEFAULT_BETA = 25 / 3 / 2;

    public const DEFAULT_TAU = 25 / 3 / 100;

    public const DEFAULT_DRAW_PROBABILITY = 0.1;

    private float $mu = 0.0;

    private float $sigma = 0.0;

    private float $beta = 0.0;

    private float $tau = 0.0;

    private float $drawProbability = 0.0;

    public function __construct(
        ?float $mu = null,
        ?float $sigma = null,
        ?float $beta = null,
        ?float $tau = null,
        ?float $drawProbability = null)
    {
        $this->mu              = $mu ?? self::DEFAULT_MU;
        $this->sigma           = $sigma ?? self::DEFAULT_SIGMA;
        $this->beta            = $beta ?? self::DEFAULT_BETA;
        $this->tau             = $tau ?? self::DEFAULT_TAU;
        $this->drawProbability = $drawProbability ?? self::DEFAULT_DRAW_PROBABILITY;
    }

    public function winProbability(array $team1, array $team2, float $drawMargin = 0.0)
    {
        $sigmaSum = 0.0;
        $mu1      = 0.0;
        foreach ($team1 as $player) {
            $mu1      += $player->mu;
            $sigmaSum += $player->sigma * $player->sigma;
        }

        $mu2 = 0.0;
        foreach ($team2 as $player) {
            $mu2      += $player->mu;
            $sigmaSum += $player->sigma * $player->sigma;
        }

        $deltaMu = $mu1 - $mu2;

        return NormalDistribution::getCdf(
            ($deltaMu - $drawMargin) / \sqrt((\count($team1) + \count($team2)) * ($this->beta * $this->beta) + $sigmaSum),
            0,
            1
        );
    }

    // Draw margin = epsilon
    /**
     * P_{draw} = 2\Phi\left(\dfrac{\epsilon}{\sqrt{n_1 + n_2} * \beta}\right) - 1
     */
    public function drawProbability(float $drawMargin, int $n1, int $n2, float $beta)
    {
        return 2 * NormalDistribution::getCdf($drawMargin / (\sqrt($n1 + $n2) * $beta), 0.0, 1.0) - 1;
    }

    /**
     * \epsilon = \Phi^{-1}\left(\dfrac{P_{draw} + 1}{2}\right) * \sqrt{n_1 + n_2} * \beta
     */
    public function drawMargin(float $drawProbability, int $n1, int $n2, float $beta)
    {
        return NormalDistribution::getIcdf(($drawProbability + 1) / 2.0, 0.0, 1.0) * \sqrt($n1 + $n2) * $beta;
    }

    /**
     * Mean additive truncated gaussion function "v" for wins
     *
     * @latex c = \sqrt{2 * \beta^2 + \sigma_{winner}^2 + \sigma_{loser}^2}
     * @latex \mu_{winner} = \mu_{winner} + \dfrac{\sigma_{winner}^2}{c} * \nu \left(\dfrac{\mu_{winner} - \mu_{loser}}{c}, \dfrac{\epsilon}{c}\right)
     * @latex \mu_{loser} = \mu_{loser} + \dfrac{\sigma_{loser}^2}{c} * \nu \left(\dfrac{\mu_{winner} - \mu_{loser}}{c}, \dfrac{\epsilon}{c}\right)
     * @latex t = \dfrac{\mu_{winner} - \mu_{loser}}{c}
     *
     * @latex \nu = \dfrac{\mathcal{N}(t - \epsilon)}{\Phi(t - \epsilon)}
     *
     * @param float $t       Difference winner and loser mu
     * @param float $epsilon Draw margin
     *
     * @return float
     *
     * @since 1.0.0
     */
    private function vWin(float $t, float $epsilon) : float
    {
        return NormalDistribution::getPdf($t - $epsilon, 0, 1.0) / NormalDistribution::getCdf($t - $epsilon, 0.0, 1.0);
    }

    /**
     * Mean additive truncated gaussion function "v" for draws
     *
     * @latex c = \sqrt{2 * \beta^2 + \sigma_{winner}^2 + \sigma_{loser}^2}
     * @latex \mu_{winner} = \mu_{winner} + \dfrac{\sigma_{winner}^2}{c} * \nu \left(\dfrac{\mu_{winner} - \mu_{loser}}{c}, \dfrac{\epsilon}{c}\right)
     * @latex \mu_{loser} = \mu_{loser} + \dfrac{\sigma_{loser}^2}{c} * \nu \left(\dfrac{\mu_{winner} - \mu_{loser}}{c}, \dfrac{\epsilon}{c}\right)
     * @latex t = \dfrac{\mu_{winner} - \mu_{loser}}{c}
     * @latex \dfrac{\mathcal{N}(t - \epsilon)}{\Phi(t - \epsilon)}
     *
     * @latex \nu = \dfrac{\mathcal{N}(-\epsilon - t) - \mathcal{N}(\epsilon - t)}{\Phi(\epsilon - t) - \Phi(-\epsilon - t)}
     *
     * @param float $t       Difference winner and loser mu
     * @param float $epsilon Draw margin
     *
     * @return float
     *
     * @since 1.0.0
     */
    private function vDraw(float $t, float $epsilon) : float
    {
        $tAbs = \abs($t);
        $a    = $epsilon - $tAbs;
        $b    = -$epsilon - $tAbs;

        $aPdf  = NormalDistribution::getPdf($a, 0.0, 1.0);
        $bPdf  = NormalDistribution::getPdf($b, 0.0, 1.0);
        $numer = $bPdf - $aPdf;

        $aCdf  = NormalDistribution::getCdf($a, 0.0, 1.0);
        $bCdf  = NormalDistribution::getCdf($b, 0.0, 1.0);
        $denom = $aCdf - $bCdf;

        return $numer / $denom;
    }

    /**
     * Variance multiplicative function "w" for draws
     *
     * @latex w = \nu * (\nu + t - \epsilon)
     *
     * @param float $t       Difference winner and loser mu
     * @param float $epsilon Draw margin
     *
     * @return float
     *
     * @since 1.0.0
     */
    private function wWin(float $t, float $epsilon) : float
    {
        $v = $this->vWin($t, $epsilon);

        return $v * ($v + $t - $epsilon);
    }

    /**
     * Variance multiplicative function "w" for draws
     *
     * @latex w = \nu^2 + \dfrac{(\epsilon - t) * \mathcal{N}(\epsilon - t) + (\epsilon + t) * \mathcal{N}(\epsilon + t)}{\Phi(\epsilon - t) - \Phi(-\epsilon - t)}
     *
     * @param float $t       Difference winner and loser mu
     * @param float $epsilon Draw margin
     *
     * @return float
     *
     * @since 1.0.0
     */
    private function wDraw(float $t, float $epsilon) : float
    {
        $tAbs = \abs($t);

        $v = $this->vDraw($t, $epsilon);

        return $v * $v
            + (($epsilon - $t) * NormalDistribution::getPdf($epsilon - $tAbs, 0.0, 1.0) + ($epsilon + $tAbs) * NormalDistribution::getPdf($epsilon + $tAbs, 0.0, 1.0))
                / (NormalDistribution::getCdf($epsilon - $tAbs, 0.0, 1.0) - NormalDistribution::getCdf(-$epsilon - $tAbs, 0.0, 1.0));
    }

    private function buildRatingLayer() : void
    {
    }

    private function buildPerformanceLayer() : void
    {
    }

    private function buildTeamPerformanceLayer() : void
    {
    }

    private function buildTruncLayer() : void
    {
    }

    private function factorGraphBuilders()
    {
        // Rating layer

        // Performance layer

        // Team Performance layer

        // Trunc layer

        return [
            'rating_layer'           => $ratingLayer,
            'performance_layer'      => $ratingLayer,
            'team_performance_layer' => $ratingLayer,
            'trunc_layer'            => $ratingLayer,
        ];
    }

    public function rating() : void
    {
        // Start values
        $mu    = 25;
        $sigma = $mu / 3;
        $beta  = $sigma / 2;
        $tau   = $sigma / 100;
        $Pdraw = 0.1;

        $alpha = 0.25;

        // Partial update
        $sigmaPartial = $sigmaOld * $sigmaNew / \sqrt($alpha * $sigmaOld * $sigmaOld - ($alpha - 1) * $sigmaNew * $sigmaNew);
        $muPartial    = $muOld * ($alpha - 1) * $sigmaNew * $sigmaNew - $muNew * $alpha * $sigmaOld * $sigmaOld
            / (($alpha - 1) * $sigmaNew * $sigmaNew - $alpha * $sigmaOld * $sigmaOld);

        // New
        $tau = $pi * $mu;

        $P     = NormalDistribution::getCdf(($s1 - $s2) / (\sqrt(2) * $beta));
        $Delta = $alpha * $beta * \sqrt($pi) * (($y + 1) / 2 - $P);

        $K = NormalDistribution::getCdf();

        $pi = 1 / ($sigma * $sigma);
    }
}
