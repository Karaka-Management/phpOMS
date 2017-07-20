<?php
/**
 * Orange Management
 *
 * PHP Version 7.1
 *
 * @category   TBD
 * @package    TBD
 * @author     OMS Development Team <dev@oms.com>
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://orange-management.com
 */
declare(strict_types=1);

namespace phpOMS\Business\Marketing;

/**
 * Net Promoter Score
 *
 * @category   Framework
 * @package    phpOMS\Business
 * @author     OMS Development Team <dev@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
class NetPromoterScore {
    /**
     * Score values
     *
     * @var int[]
     * @since 1.0.0
     */
    private $scores = [];

    /**
     * Constructor.
     *
     * @since  1.0.0
     */
    public function __construct() {

    }

    /**
     * Add score.
     *
     * @param int $score Net promoter score
     *
     * @return void
     *
     * @since  1.0.0
     */
    public function add(int $score) /* : void */
    {
        $this->scores[] = $score;
    }

    /**
     * Get total NPS.
     *
     * @return int
     *
     * @since  1.0.0
     */
    public function getScore() : int
    {
        $promoters = 0;
        $passives = 0;
        $detractors = 0;

        foreach($this->scores as $score) {
            if($score > 8) {
                $promoters++;
            } elseif($score > 6) {
                $passives++;
            } else {
                $detractors++;
            }
        }

        $total = $promoters + $passives + $detractors;

        return $total === 0 ? 0 : ((int) ($promoters * 100 / $total)) - ((int) ($detractors * 100 / $total));
    }

    public function countDetractors() : int
    {
        $count = 0;
        foreach($this->scores as $score) {
            if($score < 7) {
                $count++;
            }
        }

        return $count;
    }

    public function countPassives() : int
    {
        $count = 0;
        foreach($this->scores as $score) {
            if($score > 6 && $score < 9) {
                $count++;
            }
        }

        return $count;
    }

    public function countPromoters() : int
    {
        $count = 0;
        foreach($this->scores as $score) {
            if($score > 8) {
                $count++;
            }
        }

        return $count;
    }
}