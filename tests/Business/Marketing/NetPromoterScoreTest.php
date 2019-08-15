<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package    tests
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       https://orange-management.org
 */
 declare(strict_types=1);

namespace phpOMS\tests\Business\Marketing;

use phpOMS\Business\Marketing\NetPromoterScore;

/**
 * @testdox phpOMS\tests\Business\Marketing\NetPromoterScoreTest: Net promoter
 *
 * @internal
 */
class NetPromoterScoreTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox The default net promoter score is 0
     */
    public function testDefault() : void
    {
        $nps = new NetPromoterScore();

        self::assertEquals(0, $nps->getScore());
    }

    /**
     * @testdox The net promotor score, detractors, passives and promotors are correct
     */
    public function testScoreDetractorPassivePromotor() : void
    {
        $nps = new NetPromoterScore();

        for ($i = 0; $i < 10; ++$i) {
            $nps->add(\mt_rand(0, 6));
        }

        for ($i = 0; $i < 30; ++$i) {
            $nps->add(\mt_rand(7, 8));
        }

        for ($i = 0; $i < 60; ++$i) {
            $nps->add(\mt_rand(9, 10));
        }

        self::assertEquals(50, $nps->getScore());
        self::assertEquals(10, $nps->countDetractors());
        self::assertEquals(30, $nps->countPassives());
        self::assertEquals(60, $nps->countPromoters());
    }
}
