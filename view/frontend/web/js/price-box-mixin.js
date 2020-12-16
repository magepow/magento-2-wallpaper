define(['jquery'], function ($) {
    'use strict';
    return function (widget) {
        var updatePrice = $("#wallpaper_attribute").val();
        var globalOptions = {
            productId: null,
            priceConfig: null,
            prices: {},
            priceTemplate: '<span><div class="total-sum-price"></div></span><span class="price"><%- data.formatted %>/m²</span>'
        };

        $.widget('mage.priceBox', widget, {
            options: globalOptions,
            onUpdatePrice :function onUpdatePrice(event, prices) {
            if (updatePrice == '1') {
                return this;
            }else{
                return this.updatePrice(prices);
            }
            
            },
            

        });
        return $.mage.priceBox;
    }
});
