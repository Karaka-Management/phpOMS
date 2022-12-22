<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Api\Payment
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Api\Payment;

use \Stripe\StripeClient;

/**
 * Strip generator.
 *
 * @package phpOMS\Api\Payment
 * @license OMS License 1.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class Stripe extends PaymentAbstract
{
    private Stripe\StripeClient $con;

    public function __construct(string $apiKey)
    {
        $this->con = new \Stripe\StripeClient($apiKey);
    }

    public function createCharge(int $customer, Charge $charge) {}
    public function refundCharge(int $customer, int $charge) {}
    public function listCharges(int $customer) {}

    public function addPaymentMethod(int $customer, mixed $paymentMethod) {}
    public function removePaymentMethod(int $customer, int $paymentMethod) {}
    public function modifyPaymentMethod(int $customer, mixed $paymentMethod) {}
    public function listPaymentMethods(int $customer) {}

    public function addSubscription(int $customer, mixed $subscription) {}
    public function removeSubscription(int $customer, int $subscription) {}
    public function modifySubscription(int $customer, mixed $subscription) {}
    public function listSubscriptions(int $customer) {}
}
