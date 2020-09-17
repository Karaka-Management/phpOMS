<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   phpOMS\Business\Marketing
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Business\Marketing;

use phpOMS\Math\Matrix\IdentityMatrix;
use phpOMS\Math\Matrix\Matrix;
use phpOMS\Math\Matrix\Vector;

/**
 * Marketing Metrics
 *
 * This class provided basic marketing metric calculations
 *
 * @package phpOMS\Business\Marketing
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
final class Metrics
{
    /**
     * Constructor
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    private function __construct()
    {
    }

    /**
     * Calculate customer retention
     *
     * @latex  r = \frac{ce - cn}{cs}
     *
     * @param int $ce Customer at the end of the period
     * @param int $cn New customers during period
     * @param int $cs Customers at the start of the period
     *
     * @return float Returns the customer retention
     *
     * @since 1.0.0
     */
    public static function getCustomerRetention(int $ce, int $cn, int $cs) : float
    {
        return ($ce - $cn) / $cs;
    }

    /**
     * Calculate the customer profits
     *
     * @param int   $customers      Amount of customers acquired
     * @param float $acquistionCost Acquisition cost per customer
     * @param float $revenue        Revenues per period per customer
     * @param float $cogs           COGS per period per customer
     * @param float $marketingCosts Ongoing marketing costs per period per customer
     * @param float $discountRate   Discount rate
     * @param float $retentionRate  Retention rate (how many customers remain)
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getBerrysCustomerProfits(
        int $customers,
        float $acquistionCost,
        float $revenue,
        float $cogs,
        float $marketingCosts,
        float $discountRate,
        float $retentionRate
    ) : float {
        return $customers * ($revenue - $cogs) * ((1 + $discountRate) / (1 + $discountRate - $retentionRate))
            - $customers * $marketingCosts * ((1 + $discountRate) / (1 + $discountRate - $retentionRate))
            - $customers * $acquistionCost;
    }

    /**
     * Calculate the profitability of customers based on their purchase behaviour
     *
     * The basis for the calculation is the migration model using a markov chain
     *
     * @param float $discountRate        Discount rate
     * @param array $purchaseProbability Purchase probabilities for different periods
     * @param array $payoffs             Payoff vector (first element = payoff - cost, other elements = -cost, last element = 0)
     *
     * @return Matrix A vector which shows in row i the return of the customer if he didn't buy i - 1 times before (=recency of the customer = how many periods has it been since he bought the last time)
     *
     * @since 1.0.0
     */
    public static function calculateMailingSuccessEstimation(float $discountRate, array $purchaseProbability, array $payoffs) : Matrix
    {
        $count  = \count($purchaseProbability);
        $profit = new Vector($count, 1);
        $G      = Vector::fromArray($payoffs);

        $P    = self::createCustomerPurchaseProbabilityMatrix($purchaseProbability);
        $newP = new IdentityMatrix($count);

        // $i = 0;
        $profit = $profit->add($G);

        for ($i = 1; $i < $count + 1; ++$i) {
            $newP   = $newP->mult($P);
            $profit = $profit->add($newP->mult($G)->mult(1 / \pow(1 + $discountRate, $i)));
        }

        return $profit;
    }

    /**
     * Calculate V of the migration model
     *
     * Pfeifer and Carraway 2000
     *
     * @param float $discountRate        Discount rate
     * @param array $purchaseProbability Purchase probabilities for different periods
     * @param array $payoffs             Payoff vector (first element = payoff - cost, other elements = -cost, last element = 0)
     *
     * @return Matrix [0][0] returns the LTV
     *
     * @since 1.0.0
     */
    public static function migrationModel(float $discountRate, array $purchaseProbability, array $payoffs) : Matrix
    {
        $P = self::createCustomerPurchaseProbabilityMatrix($purchaseProbability);
        $I = new IdentityMatrix(\count($purchaseProbability));

        return $I->sub(
                $P->mult(1 / (1 + $discountRate))
            )->inverse()
            ->mult(Vector::fromArray($payoffs));
    }

    /**
     * Calculate the purchase probability of the different purchase states.
     *
     * Pfeifer and Carraway 2000
     *
     * A customer can either buy in a certain period or not.
     * Depending on the result he either moves on to the next state (not buying) or returns to the first state (buying).
     *
     * @param int   $period              Period to evaluate (t)
     * @param array $purchaseProbability Purchase probabilities
     *
     * @return Matrix [
     *                [0][0] = probability of buying in period t if customer bought in t = 1
     *                ...
     *                ]
     */
    public static function migrationModelPurchaseProbability(int $period, array $purchaseProbability) : Matrix
    {
        $matrix    = self::createCustomerPurchaseProbabilityMatrix($purchaseProbability);
        $newMatrix = clone $matrix;

        for ($i = 0; $i < $period - 1; ++$i) {
            $newMatrix = $newMatrix->mult($matrix);
        }

        return $newMatrix;
    }

    /**
     * Create a matrix which contains the probabilities a customer will buy in period t
     *
     * @param array $purchaseProbability Purchase probabilities for the different periods
     *
     * @latex \begin{bmatrix}
     *      p_1 & 1 - p_1 & 0 \\
     *      p_2 & 0 & 1 - p_2 \\
     *      p_3 & 0 & 1 - p_3 \\
     * \end{bmatrix}
     *
     * @return Matrix [
     *                p1, 1-p1, 0,
     *                p2, 0,    1-p2,
     *                p3, 0,    1-p3,
     *                ] where pi = Probability that customer buys in period i / moves from one state to the next state
     *
     * @since 1.0.0
     */
    private static function createCustomerPurchaseProbabilityMatrix(array $purchaseProbability) : Matrix
    {
        $matrix = [];

        $count = \count($purchaseProbability);
        for ($i = 0; $i < $count; ++$i) {
            $matrix[$i]         = \array_fill(0, $count, 0);
            $matrix[$i][0]      = $purchaseProbability[$i];

            $matrix[$i][
                $i === $count - 1 ? $i : $i + 1
            ] = 1 - $purchaseProbability[$i];
        }

        return Matrix::fromArray($matrix);
    }
}
