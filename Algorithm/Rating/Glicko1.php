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
 * Elo rating calculation using Glicko-1
 *
 * @package phpOMS\Algorithm\Rating
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @see     https://en.wikipedia.org/wiki/Glicko_rating_system
 * @see     http://www.glicko.net/glicko/glicko.pdf
 * @since   1.0.0
 */
final class Glicko1
{
    /**
     * Helper constant
     *
     * @latex Q = ln(10) / 400
     *
     * @var int
     * @since 1.0.0
     */
    private const Q = 0.00575646273;

    /**
     * Default elo to use for new players
     *
     * @var int
     * @since 1.0.0
     */
    public int $DEFAULT_ELO = 1500;

    /**
     * Default rd to use for new players
     *
     * @var int
     * @since 1.0.0
     */
    public int $DEFAULT_RD = 350;

    /**
     * C (constant) for RD caclulation
     *
     * This is used to adjust the RD value based on the time from the last time a player played a match
     *
     * @latex RD = min\left(\sqrt{RD_0^2 + c^2t}, 350\right)
     *
     * @see calculateC();
     *
     * @var int
     * @since 1.0.0
     */
    public float $DEFAULT_C = 34.6;

    /**
     * Lowest elo allowed
     *
     * @var int
     * @since 1.0.0
     */
    public int $MIN_ELO = 100;

    /**
     * Lowest rd allowed
     *
     * @example 50 means that the player rating is probably between -100 / +100 of the current rating
     *
     * @var int
     * @since 1.0.0
     */
    public int $MIN_RD = 50;

    /**
     * Calculate the C value.
     *
     * This is only necessary if you change the DEFAULT_RD, want a different rating period or have significantly different average RD values.
     *
     * @param int $ratingPeriods Time without matches until the RD returns to the default RD
     * @param int $avgRD         Average RD
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function calculateC(int $ratingPeriods = 100, int $avgRD = 50) : void
    {
        $this->DEFAULT_C = \sqrt(($this->DEFAULT_RD ** 2 - $avgRD ** 2) / $ratingPeriods);
    }

    /**
     * Calcualte the glicko-1 elo
     *
     * @param int     $elo           Current player "elo"
     * @param int     $rdOld         Current player deviation (RD)
     * @param int     $lastMatchDate Last match date used to calculate the time difference (can be days, months, ... depending on your match interval)
     * @param int     $matchDate     Match date (usually day)
     * @param float[] $s             Match results (1 = victor, 0 = loss, 0.5 = draw)
     * @param int[]   $oElo          Opponent "elo"
     * @param int[]   $oRd           Opponent deviation (RD)
     *
     * @return array{elo:int, rd:int}
     *
     * @since 1.0.0
     */
    public function rating(
        int $elo = 1500,
        int $rdOld = 50,
        int $lastMatchDate = 0,
        int $matchDate = 0,
        array $s = [],
        array $oElo = [],
        array $oRd = []
    ) : array
    {
        // Step 1:
        $s   = [];
        $E   = [];
        $gRD = [];

        $RD = \min(
            350,
            \max(
                \sqrt(
                    $rdOld * $rdOld
                    + $this->DEFAULT_C * $this->DEFAULT_C * \max(0, $matchDate - $lastMatchDate)
                ),
                $this->MIN_RD
            )
        );

        // Step 2:
        foreach ($oElo as $id => $e) {
            $gRD_t = 1 / (\sqrt(1 + 3 * self::Q * self::Q * $oRd[$id] * $oRd[$id] / (\M_PI * \M_PI)));
            $gRD[] = $gRD_t;
            $E[]   = 1 / (1 + \pow(10, $gRD_t * ($elo - $e) / -400));
        }

        $d = 0;
        foreach ($E as $id => $_) {
            $d += $gRD[$id] * $gRD[$id] * $E[$id] * (1 - $E[$id]);
        }
        $d2 = 1 / (self::Q * self::Q * $d);

        $r = 0;
        foreach ($E as $id => $_) {
            $r += $gRD[$id] * ($s[$id] - $E[$id]);
        }
        $r = $elo + self::Q / (1 / ($RD * $RD) + 1 / $d2) * $r;

        // Step 3:
        $RD_ = \sqrt(1 / (1 / ($RD * $RD) + 1 / $d2));

        return [
            'elo' => (int) \max((int) $r, $this->MIN_ELO),
            'rd'  => (int) \max($RD_, $this->MIN_RD),
        ];
    }
}
