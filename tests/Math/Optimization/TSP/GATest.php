<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    TBD
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */

namespace phpOMS\tests\Math\Optimization\TSP;

use phpOMS\Math\Optimization\TSP\GA;
use phpOMS\Math\Optimization\TSP\CityPool;
use phpOMS\Math\Optimization\TSP\City;
use phpOMS\Math\Optimization\TSP\Population;

class GATest extends \PHPUnit\Framework\TestCase
{
    public function testTsp()
    {
        $cities   = [new City()];
        $cityPool = new CityPool($cities);
        $ga       = new Ga($cityPool);

        $basePopulation = new Population($cityPool, 1000, true);
        $population     = $ga->evolvePopulation($basePopulation);

    }
}
