<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   phpOMS\Algorithm\Rating
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Algorithm\Rating;

/**
 * Elo rating calculation using Elo rating
 *
 * @package phpOMS\Algorithm\Rating
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @see     https://en.wikipedia.org/wiki/Elo_rating_system
 * @since   1.0.0
 */
final class Elo
{
    /**
     * ELO change rate
     *
     * @var int
     * @since 1.0.0
     */
    public int $K = 32;

    /**
     * Default elo to use for new players
     *
     * @var int
     * @since 1.0.0
     */
    public int $DEFAULT_ELO = 1500;

    /**
     * Lowest elo allowed
     *
     * @var int
     * @since 1.0.0
     */
    public int $MIN_ELO = 100;

    /**
     * Calculate the elo rating
     *
     * @param int     $elo  Current player elo
     * @param int[]   $oElo Current elo of all opponents
     * @param float[] $s    Match results against the opponents (1 = victor, 0 = loss, 0.5 = draw)
     *
     * @return array{elo:int}
     *
     * @since 1.0.0
     */
    public function rating(int $elo, array $oElo, array $s) : array
    {
        $eloNew = $elo;
        foreach ($oElo as $idx => $o) {
            $expected = 1 / (1 + 10 ** (($o - $elo) / 400));
            $r        = $this->K * ($s[$idx] - $expected);

            $eloNew += (int) \round($r);
        }

        return [
            'elo' => (int) \max($eloNew, $this->MIN_ELO),
        ];
    }

    /**
     * Calculate an approximated win probability based on elo points.
     *
     * @param int  $elo1    Elo of the player we want to calculate the win probability for
     * @param int  $elo2    Opponent elo
     * @param bool $canDraw Is a draw possible?
     *
     * @return float
     *
     * @since 1.0.0
     */
    public function winProbability(int $elo1, int $elo2, bool $canDraw = false) : float
    {
        return $canDraw
            ? -1.0 // @todo implement
            : 1 / (1 + \pow(10, ($elo2 - $elo1) / 400));
    }

    /**
     * Calculate an approximated draw probability based on elo points.
     *
     * @param int $elo1 Elo of the player we want to calculate the win probability for
     * @param int $elo2 Opponent elo
     *
     * @return float
     *
     * @since 1.0.0
     */
    public function drawProbability(int $elo1, int $elo2) : float
    {
        return -1.0; // @todo implement
    }
}
