<?php

namespace Webatypique\GuestToCustomer\Observer;

class ConvertGuest implements \Magento\Framework\Event\ObserverInterface
{
    protected $_storeManager;
    protected $orderCustomerService;
    protected $_orderFactory;
    protected $_orderRepository;
    protected $_customer;
    protected $_orderCollectionFactory;

    public function __construct(
        \Magento\Store\Model\StoreManagerInterface                  $storeManager,
        \Magento\Sales\Api\OrderCustomerManagementInterface         $orderCustomerService,
        \Magento\Sales\Model\OrderFactory                           $orderFactory,
        \Magento\Sales\Api\OrderRepositoryInterface                 $orderRepository,
        \Magento\Customer\Model\CustomerFactory                     $customer,
        \Magento\Sales\Model\ResourceModel\Order\CollectionFactory  $orderCollectionFactory
    )
    {
        $this->_storeManager = $storeManager;
        $this->orderCustomerService = $orderCustomerService;
        $this->_orderFactory = $orderFactory;
        $this->_orderRepository = $orderRepository;
        $this->_customer = $customer;
        $this->_orderCollectionFactory = $orderCollectionFactory;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $orderIds = $observer->getEvent()->getOrderIds();
        $orderId = $orderIds[0];
        $currentOrder = $this->_orderFactory->create()->load($orderId);
        $orders = $this->_orderCollectionFactory->create()
            ->addAttributeToFilter('customer_email', $currentOrder->getCustomerEmail());
        $customer = $this->_customer->create();
        $customer->setWebsiteId($this->_storeManager->getStore()->getWebsiteId());
        $customer->loadByEmail($currentOrder->getCustomerEmail());
        foreach ($orders as $order){
            if ($order->getId() && $customer->getId()) {
                //if customer Registered and checkout as guest
                $order->setCustomerId($customer->getId());
                $order->setCustomerIsGuest(0);
                $this->_orderRepository->save($order);
            }
        }
    }
}
