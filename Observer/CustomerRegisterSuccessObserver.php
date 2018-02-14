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
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Model\ResourceModel\Order\Collection as OrderCollection;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory as OrderCollectionFactory;
use PH2M\AssignGuestOrders\Model\Order as AssignOrder;

/**
 * Class CustomerRegisterSuccessObserver
 * @package PH2M\AssignGuestOrders\Observer
 */
class CustomerRegisterSuccessObserver implements ObserverInterface
{
    /**
     * @var AssignOrder
     */
    private $assignOrder;

    /**
     * @var OrderCollectionFactory
     */
    private $orderCollectionFactory;

    /**
     * CustomerRegisterSuccessObserver constructor.
     * @param AssignOrder $assignOrder
     * @param OrderCollectionFactory $orderCollectionFactory
     */
    public function __construct(
        AssignOrder $assignOrder,
        OrderCollectionFactory $orderCollectionFactory
    ) {
        $this->assignOrder              = $assignOrder;
        $this->orderCollectionFactory   = $orderCollectionFactory;
    }

    /**
     * Assign guest orders to the newly created customer
     *
     * @param \Magento\Framework\Event\Observer $observer
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        /** @var CustomerInterface $customer */
        $customer = $observer->getCustomer();

        if ($customer && $customer->getId()) {
            $orders = $this->getOrdersWithEmail($customer->getEmail());
            $orders->walk([$this->assignOrder, 'setCustomerDataAndSaveOrder'], [$customer, true]);
        }
    }

    /**
     * @param string $email
     * @return OrderCollection
     */
    private function getOrdersWithEmail(string $email)
    {
        return $this->orderCollectionFactory->create()
                    ->addFieldToFilter('customer_id', ['null' => true])
                    ->addFieldToFilter('customer_email', $email);
    }
}