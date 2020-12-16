/**
 * example-modal
 *
 * @copyright Copyright © 2020 Magepow. All rights reserved.
 * @author    @copyright Copyright (c) 2014 Magepow (<https://www.magepow.com>)
 * @license <https://www.magepow.com/license-agreement.html>
 * @Author: magepow<support@magepow.com>
 * @github: <https://github.com/magepow>
 */
define([
        "jquery", "Magento_Ui/js/modal/modal"
    ], function($){
        var ExampleModal = {
            initModal: function(config, element) {
                $target = $(config.target);
                $target.modal();
                $element = $(element);
                $element.click(function() {
                    $target.modal('openModal');
                });
            }
        };
        return {
            'example-modal': ExampleModal.initModal
        };
    }
);
