
define(['jquery', 'uiComponent', 'ko', 'domReady'], function ($, Component, ko, domReady) {
    'use strict';
    return Component.extend({
        defaults: {
            template: 'Magepow_Wallpaper/wallpaper'
        },

        initialize: function (config) {
            var productValue = config.productValue;
            var listUnitData = config.listUnit;
            $(document).ready(function() {
                if ($("span").hasClass('special-price')) {
                    $('.special-price').find('.price-wrapper').html('<span><div class="total-sum-price"></div></span>'+'<span class="price">$'+productValue+"/m²</span>");
                }else{
                    $(".product-options-bottom").find('.price-wrapper').html('<span><div class="total-sum-price"></div></span>'+'<span class="price">$'+productValue+"/m²</span>");
                }
            });

            var self = this;
            var unitList = listUnitData.split(/[\s,]+/);
            self.materialValpro = ko.observable(productValue);
            self.widthVal = ko.observable(175);
            self.heightVal = ko.observable(125);
            self.categoryVal = ko.observableArray(unitList);
            self.categorys = ko.observableArray(['inch']);
            self.grandTotal = ko.computed(function(){
                var categorys = self.categorys()[0];
                var   widthVal = self.widthVal();
                var   heightVal = self.heightVal();
                var   materials = self.materialValpro();
                switch(categorys){
                    case 'cm':
                        let sumCm = parseFloat(widthVal) * parseFloat(heightVal) * parseFloat(materials) * 0.0001;
                        $(".total-sum-price").html("$"+sumCm.toFixed(2));
                        return sumCm.toFixed(2);
                    case 'inch':
                        let sumInch = parseFloat(widthVal) * parseFloat(heightVal) * parseFloat(materials) * 0.000645;
                        $(".total-sum-price").html("$"+sumInch.toFixed(2));
                        return sumInch.toFixed(2);
                    case 'ft' :
                        let sumFt =  parseFloat(widthVal) * parseFloat(heightVal) * parseFloat(materials) * 0.092903;
                        $(".total-sum-price").html("$"+sumFt.toFixed(2));
                        return sumFt.toFixed(2);
                }
            });

            $(document).on('keyup', "#crop_width", function (){
                var categorys = self.categorys()[0];
                if ($(this).val() >=3000 || $(this).val() < 10){
                    switch(categorys){
                        case 'cm':
                            return   $("#error-value .width-product").html("<div class='showError'>"+"Print Width must be between (30-3000) "+categorys+"</div>");
                        case 'inch':
                            return  $("#error-value .width-product").html("<div class='showError'>"+"Print Width must be between (10-3000) "+categorys+"</div>");
                        case 'ft' :
                            return $("#error-value .width-product").html("<div class='showError'>"+"Print Width must be between (1-3000) "+categorys+"</div>");
                    }
                }else {
                    $("#error-value .width-product").find(".showError").remove();
                }
            });
            $(document).on('keyup', "#crop_height", function (){
                var categorys = self.categorys()[0];
                if ($(this).val() >=3000 || $(this).val() < 10){
                    switch(categorys){
                        case 'cm':
                            return  $("#error-value .height-product").html("<div class='showError'>"+"Print Height must be between (30-3000) "+categorys+"</div>");
                        case 'inch':
                            return  $("#error-value .height-product").html("<div class='showError'>"+"Print Height must be between (10-3000) "+categorys+"</div>");
                        case 'ft' :
                            return  $("#error-value .height-product").html("<div class='showError'>"+"Print Height must be between (1-3000) "+categorys+"</div>");
                    }
                }else {
                    $("#error-value .height-product").find(".showError").remove();
                }
            });

            this._super();
        },

    });

});
