<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Api\Payment
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Api\Payment;

/**
 * Strip generator.
 *
 * @package phpOMS\Api\Payment
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class Stripe extends PaymentAbstract
{
    private \Stripe\StripeClient $con;

    /**
     * Constructor.
     *
     * @param string $apiKey Api key
     *
     * @since 1.0.0
     */
    public function __construct(string $apiKey)
    {
        $this->con = new \Stripe\StripeClient($apiKey);
    }

    /**
     * {@inheritdoc}
     */
    public function createCharge(int $customer, Charge $charge) : void {}

    /**
     * {@inheritdoc}
     */
    public function refundCharge(int $customer, Charge $charge) : void {}

    /**
     * {@inheritdoc}
     */
    public function listCharges(int $customer, \DateTime $start, \DateTime $end) : void {}

    /**
     * {@inheritdoc}
     */
    public function addPaymentMethod(int $customer, mixed $paymentMethod) : void
    {
        $this->con->paymentMethods->attach();
    }

    /**
     * {@inheritdoc}
     */
    public function removePaymentMethod(int $customer, mixed $paymentMethod) : void {}

    /**
     * {@inheritdoc}
     */
    public function modifyPaymentMethod(int $customer, mixed $paymentMethod) : void {}

    /**
     * {@inheritdoc}
     */
    public function listPaymentMethods(int $customer) : void {}

    /**
     * {@inheritdoc}
     */
    public function addSubscription(int $customer, mixed $subscription) : void {}

    /**
     * {@inheritdoc}
     */
    public function removeSubscription(int $customer, mixed $subscription) : void {}

    /**
     * {@inheritdoc}
     */
    public function modifySubscription(int $customer, mixed $subscription) : void {}

    /**
     * {@inheritdoc}
     */
    public function listSubscriptions(int $customer) : void {}
}
