define([
    'jquery',
    'mage/utils/wrapper',
    'Magento_CheckoutAgreements/js/model/agreements-assigner',
    'Magento_Checkout/js/model/quote',
    'Magento_Customer/js/model/customer',
    'Magento_Checkout/js/model/url-builder',
    'mage/url',
    'Magento_Checkout/js/model/error-processor'
], function (
    $,
    wrapper,
    agreementsAssigner,
    quote,
    customer,
    urlBuilder,
    urlFormatter,
    errorProcessor
) {
    'use strict';

    return function (placeOrderAction) {

        /** Override default place order action and add agreement_ids to request */
        return wrapper.wrap(placeOrderAction, function (originalAction, paymentData, messageContainer) {
            agreementsAssigner(paymentData);
            var isCustomer = customer.isLoggedIn();

            var url = urlFormatter.build('guesttocustomer/quote/save');

            var createAccount = $('[name="create-account"]').val();
            var firstname = $('[name="firstname"]').val();
            var lastname = $('[name="lastname"]').val();
            var email = document.getElementById('customer-email').value;
            var password = $('[name="create-account-password"]').val();

            var payload = {
                'create_account': createAccount ? 1 : 0,
                'firstname': firstname,
                'lastname': lastname,
                'email':  email,
                'password': password,
            };

            if (!payload.create_account) {
                return true;
            }

            var result = true;

            $.ajax({
                url: url,
                data: payload,
                dataType: 'text',
                type: 'POST',
            }).done(
                function (response) {
                    result = true;
                }
            ).fail(
                function (response) {
                    result = false;
                    errorProcessor.process(response);
                }
            );

            return originalAction(paymentData, messageContainer);
        });
    };
});
