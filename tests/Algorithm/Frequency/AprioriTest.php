<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\tests\Algorithm\Frequency;

use phpOMS\Algorithm\Frequency\Apriori;

require_once __DIR__ . '/../../Autoloader.php';

/**
 * @testdox phpOMS\tests\Algorithm\Frequency\AprioriTest:
 *
 * @internal
 */
final class AprioriTest extends \PHPUnit\Framework\TestCase
{
    public function testApriori() : void
    {
        self::assertEquals(
            [],
            Apriori::apriori([
                ['alpha', 'beta', 'epsilon'],
                ['alpha', 'beta', 'theta'],
                ['alpha', 'beta', 'epsilon'],
                ['alpha', 'beta', 'theta'],
            ])
        );

        self::assertEquals(
            [],
            Apriori::apriori([
                [1, 2, 3, 4],
                [1, 2, 4],
                [1, 2],
                [2, 3, 4],
                [3, 4],
                [2, 4],
            ])
        );
    }

    public function testAprioriSubset() : void
    {
        self::assertEquals(
            ['beta:theta' => 2],
            Apriori::apriori(
                [
                    ['alpha', 'beta', 'epsilon'],
                    ['alpha', 'beta', 'theta'],
                    ['alpha', 'beta', 'epsilon'],
                    ['alpha', 'beta', 'theta'],
                ],
                ['beta', 'theta']
            )
        );

        self::assertEquals(
            ['2:3' => 2],
            Apriori::apriori(
                [
                    [1, 2, 3, 4],
                    [1, 2, 4],
                    [1, 2],
                    [2, 3, 4],
                    [3, 4],
                    [2, 4],
                ],
                [2, 3]
            )
        );
    }
}
