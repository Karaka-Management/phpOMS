<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.2
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\tests\Algorithm\Clustering;

use phpOMS\Algorithm\Clustering\Kmeans;
use phpOMS\Algorithm\Clustering\Point;

include_once __DIR__ . '/../../Autoloader.php';

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Algorithm\Clustering\Kmeans::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Algorithm\Clustering\KmeansTest: Clustering points/elements with the K-means algorithm')]
final class KmeansTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The clustering of points and dynamic check of new points works as expected')]
    public function testKmeans() : void
    {
        $seed = \mt_rand(\PHP_INT_MIN, \PHP_INT_MAX);
        \mt_srand($seed);

        // The following seed + putting the loop to 1 would fail the test
        //\mt_srand(1788576141);

        $result = false;

        // due to the random nature this can be false sometimes?!
        for ($i = 0; $i < 10; ++$i) {
            $points = [
                new Point([1.0, 1.0], '1'),
                new Point([1.5, 2.0], '2'),
                new Point([3.0, 4.0], '3'),
                new Point([5.0, 7.0], '4'),
                new Point([3.5, 5.0], '5'),
                new Point([4.5, 5.0], '6'),
                new Point([3.5, 4.5], '7'),
            ];

            $kmeans = new Kmeans();
            $kmeans->generateClusters($points, 2);

            if ($kmeans->cluster($points[0])->group === 0
                && $kmeans->cluster($points[1])->group === 0
                && $kmeans->cluster($points[2])->group === 1
                && $kmeans->cluster($points[3])->group === 1
                && $kmeans->cluster($points[4])->group === 1
                && $kmeans->cluster($points[5])->group === 1
                && $kmeans->cluster($points[6])->group === 1
            ) {
                $result = true;

                break;
            }
        }

        self::assertTrue($result);
    }
}
