<?php

namespace Webatypique\GuestToCustomer\Plugin;

use Magento\Store\Model\StoreManagerInterface;
use Magento\Customer\Model\Customer;
use Magento\Customer\Model\Session;

class SuccessPlugin
{
    protected $_storeManager;
    protected $_customer;
    protected $_session;

    public function __construct(
        StoreManagerInterface $storeManager,
        Customer $customer,
        Session $session
    )
    {
        $this->_storeManager = $storeManager;
        $this->_customer = $customer;
        $this->_session = $session;
    }

    public function beforeExecute(\Magento\Checkout\Controller\Onepage\Success $subject)
    {
        if(!$this->_session->isLoggedIn()){
            $order = $subject->getOnepage()->getCheckout()->getLastRealOrder();
            $websiteId = $this->_storeManager->getStore()->getWebsiteId();
            $customer = $this->_customer->setWebsiteId($websiteId)->loadByEmail($order->getCustomerEmail());
            if($customer->getId())
                $this->_session->setCustomerAsLoggedIn($customer);
        }

    }
}
