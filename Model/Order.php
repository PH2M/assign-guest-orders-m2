<?php
/**
 * 2011-2018 PH2M
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0) that is available
 * through the world-wide-web at this URL: http://www.opensource.org/licenses/OSL-3.0
 * If you are unable to obtain it through the world-wide-web, please send an email
 * to contact@ph2m.com so we can send you a copy immediately.
 *
 * @author PH2M - contact@ph2m.com
 * @copyright 2011-2018 PH2M
 * @license http://www.opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */

namespace PH2M\AssignGuestOrders\Model;

use Magento\Sales\Model\OrderRepository;

/**
 * Class Order
 * @package PH2M\AssignGuestOrders\Model
 */
class Order
{
    /**
     * @var OrderRepository
     */
    private $orderRepository;

    /**
     * Order constructor.
     * @param OrderRepository $orderRepository
     */
    public function __construct(
        OrderRepository $orderRepository
    ) {
        $this->orderRepository = $orderRepository;
    }
    /**
     * @param OrderInterface $order
     * @param CustomerInterface $customer
     * @param bool $saveOrder
     */
    public function setCustomerDataAndSaveOrder($order, $customer, $saveOrder = false)
    {
        $order->setCustomerId($customer->getId())
            ->setCustomerIsGuest(0)
            ->setCustomerGroupId($customer->getGroupId())
            ->setCustomerFirstname($customer->getFirstname())
            ->setCustomerDob($customer->getDob())
            ->setCustomerGender($customer->getGender())
            ->setCustomerLastname($customer->getLastname())
            ->setCustomerMiddlename($customer->getMiddlename())
            ->setCustomerPrefix($customer->getPrefix())
            ->setCustomerSuffix($customer->getSuffix())
            ->setCustomerTaxvat($customer->getTaxvat())
        ;

        if ($saveOrder) {
            $this->orderRepository->save($order);
        }
    }
}