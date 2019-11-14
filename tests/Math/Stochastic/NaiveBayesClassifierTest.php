<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\tests\Math\Stochastic;

use phpOMS\Math\Stochastic\NaiveBayesClassifier;

/**
 * @testdox phpOMS\tests\Math\Stochastic\NaiveBayesClassifierTest: Naive bayes classifier for numeric values and strings/attributes
 *
 * @internal
 */
class NaiveBayesClassifierTest extends \PHPUnit\Framework\TestCase
{
    const PLAY = [
        ['weather' => ['Overcast']],
        ['weather' => ['Rainy']],
        ['weather' => ['Sunny']],
        ['weather' => ['Sunny']],
        ['weather' => ['Overcast']],
        ['weather' => ['Sunny']],
        ['weather' => ['Rainy']],
        ['weather' => ['Overcast']],
        ['weather' => ['Overcast']],
    ];

    const NO_PLAY = [
        ['weather' => ['Sunny']],
        ['weather' => ['Rainy']],
        ['weather' => ['Rainy']],
        ['weather' => ['Sunny']],
        ['weather' => ['Rainy']],
    ];

    const MALE = [
        ['height' => 6, 'weight' => 180, 'foot' => 12],
        ['height' => 5.92, 'weight' => 190, 'foot' => 11],
        ['height' => 5.58, 'weight' => 170, 'foot' => 12],
        ['height' => 5.92, 'weight' => 165, 'foot' => 10],
    ];

    const FEMALE = [
        ['height' => 5, 'weight' => 100, 'foot' => 6],
        ['height' => 5.5, 'weight' => 150, 'foot' => 8],
        ['height' => 5.42, 'weight' => 130, 'foot' => 7],
        ['height' => 5.75, 'weight' => 150, 'foot' => 9],
    ];

    /**
     * @testdox The classification of strings/attributes is correct
     */
    public function testTextClassifier() : void
    {
        $filter = new NaiveBayesClassifier();
        $filter->train('play', self::PLAY);
        $filter->train('noplay', self::NO_PLAY);

        self::assertEqualsWithDelta(
            0.6,
            $filter->match('play', ['weather' => ['Sunny']], 1),
            0.01
        );
    }

    /**
     * @testdox The classification of nimeric values is correct
     */
    public function testNumericClassifier() : void
    {
        $filter = new NaiveBayesClassifier();
        $filter->train('male', self::MALE);
        $filter->train('female', self::FEMALE);

        self::assertEqualsWithDelta(
            0.999988,
            $filter->match('female', ['height' => 6, 'weight' => 130, 'foot' => 8]),
            0.01
        );
    }
}
