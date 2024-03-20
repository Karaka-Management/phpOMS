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
 * @internal
 */
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Algorithm\Frequency\AprioriTest:')]
final class AprioriTest extends \PHPUnit\Framework\TestCase
{
    public function testApriori() : void
    {
        self::assertEquals(
            [
                'theta'                    => 2,
                'epsilon'                  => 2,
                'epsilon:theta'            => 0,
                'beta'                     => 4,
                'beta:theta'               => 2,
                'beta:epsilon'             => 2,
                'beta:epsilon:theta'       => 0,
                'alpha'                    => 4,
                'alpha:theta'              => 2,
                'alpha:epsilon'            => 2,
                'alpha:epsilon:theta'      => 0,
                'alpha:beta'               => 4,
                'alpha:beta:theta'         => 2,
                'alpha:beta:epsilon'       => 2,
                'alpha:beta:epsilon:theta' => 0,
            ],
            Apriori::apriori([
                ['alpha', 'beta', 'epsilon'],
                ['alpha', 'beta', 'theta'],
                ['alpha', 'beta', 'epsilon'],
                ['alpha', 'beta', 'theta'],
            ])
        );

        self::assertEquals(
            [
                '4'       => 5,
                '3'       => 3,
                '3:4'     => 3,
                '2'       => 5,
                '2:4'     => 4,
                '2:3'     => 2,
                '2:3:4'   => 2,
                '1'       => 3,
                '1:4'     => 2,
                '1:3'     => 1,
                '1:3:4'   => 1,
                '1:2'     => 3,
                '1:2:4'   => 2,
                '1:2:3'   => 1,
                '1:2:3:4' => 1,
            ],
            Apriori::apriori([
                ['1', '2', '3', '4'],
                ['1', '2', '4'],
                ['1', '2'],
                ['2', '3', '4'],
                ['3', '4'],
                ['2', '4'],
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
                    ['1', '2', '3', '4'],
                    ['1', '2', '4'],
                    ['1', '2'],
                    ['2', '3', '4'],
                    ['3', '4'],
                    ['2', '4'],
                ],
                ['2', '3']
            )
        );
    }
}
