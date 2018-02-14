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

namespace PH2M\AssignGuestOrders\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Sales\Model\OrderRepository;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Customer\Model\ResourceModel\CustomerRepository;
use PH2M\AssignGuestOrders\Model\Order as AssignOrder;

/**
 * Class SalesOrderPlaceAfterObserver
 * @package PH2M\AssignGuestOrders\Observer
 */
class SalesOrderPlaceAfterObserver implements ObserverInterface
{
    /**
     * @var AssignOrder
     */
    private $assignOrder;

    /**
     * @var CustomerRepository
     */
    private $customerRepository;

    /**
     * SalesOrderPlaceAfterObserver constructor.
     * @param AssignOrder $assignOrder
     * @param CustomerRepository $customerRepository
     */
    public function __construct(
        AssignOrder $assignOrder,
        CustomerRepository $customerRepository
    ) {
        $this->assignOrder          = $assignOrder;
        $this->customerRepository   = $customerRepository;
    }

    /**
     * If the order is a guest one, try to assign it to an existing customer
     *
     * @param \Magento\Framework\Event\Observer $observer
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        /** @var OrderInterface $order */
        $order = $observer->getOrder();

        if ($order->getCustomerIsGuest()) {
            try {
                $customer = $this->customerRepository->get($order->getCustomerEmail());
                $this->assignOrder->setCustomerDataAndSaveOrder($order, $customer);
            } catch (\Exception $e) {
                // Customer does not exist, don't do anything
            }

        }
    }
}