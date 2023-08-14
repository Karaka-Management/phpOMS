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

use phpOMS\Math\Solver\Root\Bisection;

/**
 * Elo rating calculation using Glicko-2
 *
 * @package phpOMS\Algorithm\Rating
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 * @see     https://en.wikipedia.org/wiki/Glicko_rating_system
 * @see     http://www.glicko.net/glicko/glicko2.pdf
 *
 * @todo: implement
 */
final class Glicko2
{
    public float $tau = 0.5;

    public int $DEFAULT_ELO = 1500;

    public int $DEFAULT_RD = 350;

    public float $DEFAULT_VOLATILITY = 0.06;

    public int $MIN_ELO = 100;

    public int $MIN_RD = 50;

    /**
     * @example $glicko->elo(1500, 200, 0.06, [1,0,0], [1400,1550,1700], [30,100,300]) // 1464, 151, 0.059
     */
    public function rating(
        int $eloOld = 1500,
        int $rdOld = 50,
        float $volOld = 0.06,
        array $s = [],
        array $oElo = [],
        array $oRd = []
    ) : array
    {
        $tau = $this->tau;

        // Step 0:
        $rdOld = $rdOld / 173.7178;
        $eloOld = ($eloOld - $this->DEFAULT_ELO) / 173.7178;

        foreach ($oElo as $idx => $value) {
            $oElo[$idx] = ($value - $this->DEFAULT_ELO) / 173.7178;
        }

        foreach ($oRd as $idx => $value) {
            $oRd[$idx] = $value / 173.7178;
        }

        // Step 1:
        $g = [];
        foreach ($oRd as $rd) {
            $g[] = 1 / \sqrt(1 + 3 * $rd * $rd  / (\M_PI * \M_PI));
        }

        $E = [];
        foreach ($oElo as $idx => $elo) {
            $E[] = 1 / (1 + \exp(-$g[$idx] * ($eloOld - $elo)));
        }

        $v = 0;
        foreach ($g as $idx => $t) {
            $v += $t * $t * $E[$idx] * (1 - $E[$idx]);
        }
        $v = 1 / $v;

        $tDelta = 0;
        foreach ($g as $idx => $t) {
            $tDelta += $t * ($s[$idx] - $E[$idx]);
        }
        $Delta = $v * $tDelta;

        // Step 2:
        $fn = function($x) use ($Delta, $rdOld, $v, $tau, $volOld)
        {
            return 0.5 * (\exp($x) * ($Delta ** 2 - $rdOld ** 2 - $v - \exp($x))) / (($rdOld ** 2 + $v + \exp($x)) ** 2)
                - ($x - \log($volOld ** 2)) / ($tau ** 2);
        };

        $root = Bisection::bisection($fn, -100, 100, 1000);
        $vol  = \exp($root / 2);

        // Step 3:
        $RD = 1 / \sqrt(1 / ($rdOld ** 2 + $vol ** 2) + 1 / $v);
        $r  = $eloOld + $RD ** 2 * $tDelta;

        // Undo step 0:
        $RD = 173.7178 * $RD;
        $r  = 173.7178 * $r + $this->DEFAULT_ELO;

        return [
            'elo' => (int) \max($r, $this->MIN_ELO),
            'rd' => (int) \max($RD, $this->MIN_RD),
            'vol' => $vol,
        ];
    }
}
