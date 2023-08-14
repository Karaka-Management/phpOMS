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
 * @since   1.0.0
 * @see     https://en.wikipedia.org/wiki/Elo_rating_system
 */
final class Elo
{
    public int $K = 32;

    public int $DEFAULT_ELO = 1500;

    public int $MIN_ELO = 100;

    public function rating(int $elo, array $oElo, array $s)
    {
        $eloNew = $elo;
        foreach ($oElo as $idx => $o) {
            $expected = 1 / (1 + 10 ** (($o - $elo) / 400));
            $r = $this->K * ($s[$idx] - $expected);

            $eloNew += $r;
        }

        return [
            'elo' => (int) \max((int) $eloNew, $this->MIN_ELO),
        ];
    }
}
