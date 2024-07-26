/**
* Mavenbird Technologies Private Limited
*
* NOTICE OF LICENSE
*
* This source file is subject to the EULA
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://mavenbird.com/Mavenbird-Module-License.txt
*
* =================================================================
*
* @category   Mavenbird
* @package    Mavenbird_Payrestriction
* @author     Mavenbird Team
* @copyright  Copyright (c) 2018-2024 Mavenbird Technologies Private Limited ( http://mavenbird.com )
* @license    http://mavenbird.com/Mavenbird-Module-License.txt
*/

define([
    'jquery',
    'mage/utils/wrapper',
    'Magento_Checkout/js/model/quote',
    'Magento_SalesRule/js/model/payment/discount-messages',
    'Magento_Checkout/js/action/set-payment-information',
    'Magento_Checkout/js/action/get-totals',
    'Magento_SalesRule/js/model/coupon'
], function ($, wrapper, quote, messageContainer, setPaymentInformationAction, getTotalsAction, coupon) {
    'use strict';

    return function (selectPaymentMethodAction) {

        return wrapper.wrap(selectPaymentMethodAction, function (originalSelectPaymentMethodAction, paymentMethod) {
            originalSelectPaymentMethodAction(paymentMethod);

            //fix start
            if (!paymentMethod) {
                return;
            }
            //fix finish

            $.when(
                setPaymentInformationAction(
                    messageContainer,
                    {
                        method: paymentMethod.method
                    }
                )
            ).done(
                function () {
                    var deferred = $.Deferred(),

                        /**
                         * Update coupon form.
                         */
                        updateCouponCallback = function () {
                            if (quote.totals() && !quote.totals()['coupon_code']) {
                                coupon.setCouponCode('');
                                coupon.setIsApplied(false);
                            }
                        };

                    getTotalsAction([], deferred);
                    $.when(deferred).done(updateCouponCallback);
                }
            );
        });
    };

});
