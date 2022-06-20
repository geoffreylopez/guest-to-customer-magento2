define([
    'jquery',
    'Magento_Ui/js/form/form',
    'ko'
], function($, Component, ko) {
    'use strict';

    return Component.extend({
        showPassword: ko.observable(false),

        initialize: function () {
            this._super();
            // component initialization logic
            return this;
        },

        initObservable: function () {

            this._super()
                .observe({
                    TogglePassword: ko.observable(false)
                });

            this.TogglePassword.subscribe(function (checked) {
                if(checked){
                    $('.create-account-password').show();
                }else{
                    $('.create-account-password').hide();
                }
            });

            return this;
        },
    });
});
