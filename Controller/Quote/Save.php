<?php

namespace Webatypique\GuestToCustomer\Controller\Quote;

class Save extends \Magento\Framework\App\Action\Action
{
    protected $storeManager;
    protected $customerFactory;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Customer\Model\CustomerFactory $customerFactory
    ) {
        parent::__construct($context);
        $this->storeManager     = $storeManager;
        $this->customerFactory  = $customerFactory;
    }

    public function execute()
    {
        $post = $this->getRequest()->getPostValue();
        if ($post) {
            $createCustomer = $post['create_account'];
            $firstname = $post['firstname'];
            $lastname = $post['lastname'];
            $email = $post['email'];
            $password = $post['password'];
            if($createCustomer == 1){
                // Get Website ID
                $websiteId = $this->storeManager->getWebsite()->getWebsiteId();

                // Instantiate object (this is the most important part)
                $customer   = $this->customerFactory->create();
                $customer->setWebsiteId($websiteId);

                // Preparing data for new customer
                $customer->setEmail($email);
                $customer->setFirstname($firstname);
                $customer->setLastname($lastname);
                $customer->setPassword($password);

                // Save data
                $customer->save();
                $customer->sendNewAccountEmail();
            }
        }
    }
}
