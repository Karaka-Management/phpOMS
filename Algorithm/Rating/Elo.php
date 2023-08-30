<?php
/**
 * Jingga
 *
 * PHP Version 8.1
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
     * @param int   $elo  Current player elo
     * @param int[] $oElo Current elo of all opponents
     * @param int[] $s    Match results against the opponents (1 = victor, 0 = loss, 0.5 = draw)
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

            $eloNew += $r;
        }

        return [
            'elo' => (int) \max((int) $eloNew, $this->MIN_ELO),
        ];
    }
}
