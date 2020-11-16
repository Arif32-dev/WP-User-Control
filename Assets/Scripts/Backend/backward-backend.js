"use strict";

;

(function ($) {
    var backend = {
        init: function init() {
            backend.select();
        },
        select: function select() {
            new SlimSelect({
                select: '#multiple',
                placeholder: 'Select Accessible User'
            });
        }
    };
    backend.init();
})(jQuery);