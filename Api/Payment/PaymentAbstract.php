<?php
/**
 * Jingga
 *
 * PHP Version 8.2
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
 * Abstract payment.
 *
 * @package phpOMS\Api\Payment
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
abstract class PaymentAbstract
{
    /**
     * Create/execute a charge
     *
     * @param int    $customer Customer id
     * @param Charge $charge   Charge to execute
     *
     * @return void
     *
     * @since 1.0.0
     */
    abstract public function createCharge(int $customer, Charge $charge) : void;

    /**
     * Refund a charge
     *
     * @param int    $customer Customer id
     * @param Charge $charge   Charge to execute
     *
     * @return void
     *
     * @since 1.0.0
     */
    abstract public function refundCharge(int $customer, Charge $charge) : void;

    /**
     * Get all charges of a customer
     *
     * @param int       $customer Customer id
     * @param \DateTime $start    Start date
     * @param \DateTime $end      End date
     *
     * @return void
     *
     * @since 1.0.0
     */
    abstract public function listCharges(int $customer, \DateTime $start, \DateTime $end) : void;

    /**
     * Create/add a new payment method
     *
     * @param int   $customer      Customer id
     * @param mixed $paymentMethod Payment method (e.g. cc card)
     *
     * @return void
     *
     * @since 1.0.0
     */
    abstract public function addPaymentMethod(int $customer, mixed $paymentMethod) : void;

    /**
     * Remove a new payment method
     *
     * @param int   $customer      Customer id
     * @param mixed $paymentMethod Payment method (e.g. cc card)
     *
     * @return void
     *
     * @since 1.0.0
     */
    abstract public function removePaymentMethod(int $customer, mixed $paymentMethod) : void;

    /**
     * Modify a new payment method
     *
     * @param int   $customer      Customer id
     * @param mixed $paymentMethod Payment method (e.g. cc card)
     *
     * @return void
     *
     * @since 1.0.0
     */
    abstract public function modifyPaymentMethod(int $customer, mixed $paymentMethod) : void;

    /**
     * Get all payment methods of a customer
     *
     * @param int $customer Customer id
     *
     * @return void
     *
     * @since 1.0.0
     */
    abstract public function listPaymentMethods(int $customer) : void;

    /**
     * Create/add a new subscription
     *
     * @param int   $customer     Customer id
     * @param mixed $subscription Subscription details
     *
     * @return void
     *
     * @since 1.0.0
     */
    abstract public function addSubscription(int $customer, mixed $subscription) : void;

    /**
     * Remove a new subscription
     *
     * @param int   $customer     Customer id
     * @param mixed $subscription Subscription details
     *
     * @return void
     *
     * @since 1.0.0
     */
    abstract public function removeSubscription(int $customer, mixed $subscription) : void;

    /**
     * Modify a new subscription
     *
     * @param int   $customer     Customer id
     * @param mixed $subscription Subscription details
     *
     * @return void
     *
     * @since 1.0.0
     */
    abstract public function modifySubscription(int $customer, mixed $subscription) : void;

    /**
     * Get all subscriptions of a customer
     *
     * @param int $customer Customer id
     *
     * @return void
     *
     * @since 1.0.0
     */
    abstract public function listSubscriptions(int $customer) : void;
}
