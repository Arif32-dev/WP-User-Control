; (function ($) {
    const backend = {
        init: () => {
            backend.events();
            backend.select();
        },

        events: () => {
        },
        select: () => {
            new SlimSelect({
                select: '#multiple',
                placeholder: 'Select Accessible User'
            })
        }
    };

    backend.init();

})(jQuery);