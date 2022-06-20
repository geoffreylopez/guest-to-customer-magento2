var config = {
    config: {
        mixins: {
            'Magento_Checkout/js/action/place-order': {
                'Webatypique_GuestToCustomer/js/order/place-order-mixin': true
            }
        }
    },
    map: {
        '*': {
            'Magento_Checkout/js/view/form/element/email':'Webatypique_GuestToCustomer/js/view/form/element/email'
        }
    }
};
