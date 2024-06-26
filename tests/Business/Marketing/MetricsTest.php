<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\tests\Business\Marketing;

use phpOMS\Business\Marketing\Metrics;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Business\Marketing\MetricsTest: General marketing metrics')]
final class MetricsTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Test the correctness of the customer retention calculation')]
    public function testCustomerRetention() : void
    {
        self::assertEqualsWithDelta(0.85, Metrics::getCustomerRetention(105, 20, 100), 0.01);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The profit according to Berry can be correctly calculated')]
    public function testBerrysCustomerProfits() : void
    {
        $acquisitionCost          = 30.0;
        $customers                = 100000;
        $ongoingMarketingCustomer = 10;
        $revenueCustomer          = 100;
        $cogsCustomer             = 50;
        $discountRate             = 0.15;

        self::assertEqualsWithDelta(
            4076923.08,
            Metrics::getBerrysCustomerProfits($customers, $acquisitionCost, $revenueCustomer, $cogsCustomer, $ongoingMarketingCustomer, $discountRate, 0.5)
            , 0.01
        );
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The purchase probability of customers can be calculated based on historic information using the migration model')]
    public function testMigrationModelPurchaseMatrix() : void
    {
        // Basis:
        // Someone who just bought will buy again = 30%
        // Someone who bought two years ago will buy again = 20%
        // Someone who bought three years ago will buy again = 5%

        // Result:
        // Someone who just bought will have bought in 4 years = 8.2%
        // Someone who bought 2 years ago wil have bought in 4 years = 3.7%
        // ...
        self::assertEqualsWithDelta(
            [
                [0.0823, 0.0973, 0.1288, 0.6916],
                [0.037, 0.0406, 0.056, 0.8664],
                [0.0070, 0.0080, 0.0084, 0.9766],
                [0, 0, 0, 1],
            ],
            Metrics::migrationModelPurchaseProbability(
                4,
                [0.3, 0.2, 0.05, 0]
            )->toArray(),
            0.01
        );
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The CLTV can be calculated using the migration model')]
    public function testMigrationModel() : void
    {
        // The first element in the migration model result is the CLTV
        self::assertEqualsWithDelta(
            [[231.08], [57.29], [21.01], [0.0]],
            Metrics::migrationModel(
                0.1,
                [0.5, 0.2, 0.1, 0],
                [100, 0, 0, 0]
            )->toArray(),
            0.1
        );
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The migration model can be used in order to determin which buying/none-buying customer group should receive a mailing')]
    public function testMailingSuccessEstimation() : void
    {
        self::assertEqualsWithDelta(
            [[49.4], [2.69], [-1.98], [0.0]],
            Metrics::calculateMailingSuccessEstimation(
                0.2,
                [0.3, 0.2, 0.05, 0],
                [36, -4, -4, 0]
            )->toArray(),
            0.1
        );
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The probability of a customer buying can be calculated based on his previous purchase behavior')]
    public function testCustomerActiveProbability() : void
    {
        $purchases = 10;
        $periods   = 36; // months

        self::assertEqualsWithDelta(0.017, Metrics::customerActiveProbability($purchases, $periods, 24), 0.001);
        self::assertEqualsWithDelta(1.0, Metrics::customerActiveProbability($purchases, $periods, 36), 0.001);
    }
}
